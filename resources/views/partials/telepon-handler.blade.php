<script>
document.addEventListener('input', function (e) {
    if (e.target && e.target.classList && e.target.classList.contains('telepon-input')) {
        // Strip karakter yang bukan digit, spasi, +, -, ()
        var before = e.target.value;
        var after  = before.replace(/[^0-9+\-\s()]/g, '');
        if (before !== after) {
            e.target.value = after;
        }
    }
});
</script>
