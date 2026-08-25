#!/usr/bin/env python3
"""
render_zenuml.py — Auto-render ZenUML sequence diagram di docs/ERD_AND_DIAGRAMS.md
ke PNG, siap di-paste ke skripsi Word.

==========================================================
KENAPA SCRIPT INI?
==========================================================
ZenUML syntax ringkas (if/else) tapi GitHub markdown BELUM support
rendering native. Section 9 di docs/ERD_AND_DIAGRAMS.md cuma
muncul sebagai raw code di GitHub.

Supaya dosen penguji bisa lihat diagram-nya, render ke PNG
lalu embed di .md (atau di Word skripsi).

==========================================================
CARA PAKAI
==========================================================
Setup sekali:
  pip install playwright pillow
  python -m playwright install chromium

Render semua section 9.1-9.4:
  python scripts/render_zenuml.py

Render section tertentu:
  python scripts/render_zenuml.py --section "9.2"

List sections (tanpa render):
  python scripts/render_zenuml.py --list

Output:
  docs/ERD_AND_DIAGRAMS/images/zenuml/01_9_1_alur_peminjaman.png
  docs/ERD_AND_DIAGRAMS/images/zenuml/02_9_2_alur_pengembalian.png
  dst.

==========================================================
ALTERNATIF TANPA SCRIPT (5 menit)
==========================================================
Kalau ga mau install Playwright, render manual:
  1. Buka https://zenuml.com
  2. Copy code block section (misal 9.2) dari docs/ERD_AND_DIAGRAMS.md
  3. Paste di editor zenuml.com
  4. Screenshot / download PNG
  5. Simpan dengan nama 02_9_2_alur_pengembalian.png di
     docs/ERD_AND_DIAGRAMS/images/zenuml/

==========================================================
EMBED KE .MD (setelah PNG ada)
==========================================================
Ganti code block di .md dari:

    ```zenuml
    title Peminjaman Barang
    ...
    ```

Jadi:

    ![Sequence Diagram Peminjaman](docs/ERD_AND_DIAGRAMS/images/zenuml/01_9_1_alur_peminjaman.png)
"""

import argparse
import base64
import re
import sys
import time
from pathlib import Path
from typing import Optional

REPO = Path(__file__).resolve().parent.parent
DOC_FILE = REPO / "docs" / "ERD_AND_DIAGRAMS.md"
OUT_DIR = REPO / "docs" / "ERD_AND_DIAGRAMS" / "images" / "zenuml"
OUT_DIR.mkdir(parents=True, exist_ok=True)


def extract_zenuml_blocks(md_path: Path) -> list[dict]:
    """Extract semua ```zenuml``` block + section heading."""
    if not md_path.exists():
        raise FileNotFoundError(f"{md_path} not found")

    text = md_path.read_text(encoding="utf-8")
    sections = []
    current_heading = None

    for line in text.split("\n"):
        m = re.match(r"^(#{2,4})\s+(.+)$", line)
        if m:
            current_heading = m.group(2).strip()

    pattern = re.compile(r"```zenuml\n(.*?)```", re.DOTALL)
    for idx, match in enumerate(pattern.finditer(text), 1):
        before = text[: match.start()]
        last_heading = None
        for hline in before.split("\n"):
            hm = re.match(r"^(#{2,4})\s+(.+)$", hline)
            if hm:
                last_heading = hm.group(2).strip()

        code = match.group(1).strip()
        sections.append({
            "index": idx,
            "heading": last_heading or f"Section {idx}",
            "code": code,
        })

    return sections


def render_via_api(zenuml_code: str) -> Optional[bytes]:
    """Coba zenuml render API. Return PNG bytes atau None."""
    # ZenUML tidak punya public render API yang reliable.
    # (render.zenuml.com down, zenuml.com/render 404)
    # User harus run via Playwright atau manual di zenuml.com.
    return None


def render_via_playwright(zenuml_code: str, out_png: Path) -> bool:
    """Fallback: headless Chromium ke zenuml.com → screenshot."""
    try:
        from playwright.sync_api import sync_playwright
    except ImportError:
        print("  Playwright not installed. Run: pip install playwright", file=sys.stderr)
        return False

    encoded = base64.b64encode(zenuml_code.encode("utf-8")).decode("ascii")
    url = f"https://zenuml.com/?dsl={encoded}"

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page(viewport={"width": 1400, "height": 1000})
        page.goto(url, wait_until="networkidle", timeout=30000)
        # Tunggu render selesai
        page.wait_for_timeout(2000)
        # Cari element diagram (mungkin <svg> atau div class sequence-diagram)
        try:
            elem = page.query_selector("svg") or page.query_selector(".sequence-diagram")
            if elem:
                elem.screenshot(path=str(out_png))
            else:
                page.screenshot(path=str(out_png), full_page=True)
        except Exception as e:
            print(f"  Screenshot fail: {e}", file=sys.stderr)
            page.screenshot(path=str(out_png), full_page=True)
        browser.close()
    return out_png.exists() and out_png.stat().st_size > 0


def sanitize_filename(name: str) -> str:
    return re.sub(r"[^\w\-]+", "_", name).strip("_").lower()


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--section", help="Filter: hanya render section yg mengandung keyword")
    ap.add_argument("--list", action="store_true", help="List sections tanpa render")
    ap.add_argument("--no-playwright", action="store_true",
                    help="Skip Playwright fallback (API only)")
    args = ap.parse_args()

    print(f"📄 Parsing {DOC_FILE}...")
    sections = extract_zenuml_blocks(DOC_FILE)
    print(f"   Found {len(sections)} ZenUML blocks\n")

    if args.list:
        for s in sections:
            print(f"  [{s['index']}] {s['heading']}  ({len(s['code'])} chars)")
        return 0

    # Filter
    if args.section:
        sections = [s for s in sections if args.section.lower() in s["heading"].lower()]
        print(f"🔍 Filter: '{args.section}' → {len(sections)} sections\n")

    print(f"📁 Output: {OUT_DIR}\n")

    for s in sections:
        fname = f"{s['index']:02d}_{sanitize_filename(s['heading'])}.png"
        out = OUT_DIR / fname

        if out.exists() and out.stat().st_size > 0:
            print(f"  ⏩ [{s['index']}] {s['heading']} (cached)")
            continue

        print(f"  🎨 [{s['index']}] {s['heading']}  ({len(s['code'])} chars)")

        # Try API
        png = render_via_api(s["code"])
        if png:
            out.write_bytes(png)
            print(f"     ✅ via API → {out.name} ({len(png)} bytes)")
            continue

        if args.no_playwright:
            print(f"     ❌ API fail, Playwright disabled")
            continue

        # Fallback Playwright
        if render_via_playwright(s["code"], out):
            print(f"     ✅ via Playwright → {out.name}")
        else:
            print(f"     ❌ fail")

        time.sleep(1)  # rate limit

    print(f"\n✨ Done. PNG files di: {OUT_DIR}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
