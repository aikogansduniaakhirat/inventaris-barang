"""
render_mermaid.py — Render semua file .mmd di docs/diagrams/ ke PNG + SVG.
Output: docs/diagrams/renders/{name}.png dan {name}.svg
Pakai Mermaid Live (mermaid.live) via Playwright Chromium headless.
"""

import asyncio
import sys
from pathlib import Path
from playwright.async_api import async_playwright

DIAGRAMS_DIR = Path("docs/diagrams")
OUTPUT_DIR = DIAGRAMS_DIR / "renders"

MERMAID_TEMPLATE = """
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body {{
    margin: 0;
    padding: 20px;
    background: white;
    font-family: 'Arial', sans-serif;
  }}
  .mermaid {{
    display: flex;
    justify-content: center;
    align-items: center;
  }}
</style>
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
</head>
<body>
<div class="mermaid">
{mermaid_code}
</div>
<script>
  mermaid.initialize({{
    startOnLoad: true,
    theme: 'default',
    flowchart: {{
      useMaxWidth: false,
      htmlLabels: true,
      curve: 'basis'
    }},
    er: {{
      useMaxWidth: false
    }},
    securityLevel: 'loose'
  }});
</script>
</body>
</html>
"""


async def render_mermaid(mermaid_code: str, output_png: Path, output_svg: Path):
    html_content = MERMAID_TEMPLATE.format(mermaid_code=mermaid_code)
    html_file = output_png.parent / f"_temp_{output_png.stem}.html"
    html_file.write_text(html_content, encoding="utf-8")

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page(viewport={"width": 1600, "height": 1200})
        await page.goto(f"file://{html_file.resolve()}")
        # Tunggu Mermaid selesai render
        await page.wait_for_selector(".mermaid svg", timeout=30000)
        await page.wait_for_timeout(2000)  # Extra time for full render

        # Screenshot element SVG
        svg_element = await page.query_selector(".mermaid svg")
        if svg_element:
            # PNG
            await svg_element.screenshot(path=str(output_png), omit_background=False)
            # SVG (export from browser)
            svg_content = await svg_element.evaluate("el => el.outerHTML")
            output_svg.write_text(svg_content, encoding="utf-8")
            return True
        return False


async def main():
    OUTPUT_DIR.mkdir(parents=True, exist_ok=True)

    mmd_files = sorted(DIAGRAMS_DIR.glob("*.mmd"))
    print(f"Found {len(mmd_files)} Mermaid files")

    success_count = 0
    fail_count = 0

    for mmd_file in mmd_files:
        name = mmd_file.stem
        print(f"\n[{name}]")
        mermaid_code = mmd_file.read_text(encoding="utf-8")

        # Strip YAML frontmatter (---\n...\n---) - Mermaid tidak butuh
        if mermaid_code.startswith("---"):
            parts = mermaid_code.split("---", 2)
            if len(parts) >= 3:
                mermaid_code = parts[2].strip()

        output_png = OUTPUT_DIR / f"{name}.png"
        output_svg = OUTPUT_DIR / f"{name}.svg"

        try:
            ok = await render_mermaid(mermaid_code, output_png, output_svg)
            if ok:
                # Cleanup temp HTML
                temp_html = output_png.parent / f"_temp_{name}.html"
                if temp_html.exists():
                    temp_html.unlink()
                print(f"  ✓ PNG: {output_png.relative_to(DIAGRAMS_DIR)}")
                print(f"  ✓ SVG: {output_svg.relative_to(DIAGRAMS_DIR)}")
                success_count += 1
            else:
                print(f"  ✗ SVG element not found")
                fail_count += 1
        except Exception as e:
            print(f"  ✗ Error: {e}")
            fail_count += 1

    print(f"\n=== Summary ===")
    print(f"Success: {success_count}")
    print(f"Failed:  {fail_count}")
    print(f"Output:  {OUTPUT_DIR}")


if __name__ == "__main__":
    asyncio.run(main())
