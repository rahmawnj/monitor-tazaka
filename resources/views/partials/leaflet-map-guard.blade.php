<script>
(() => {
    const installGuard = L => {
        if (!L || L.__monitorMapGuardInstalled) return L;
        const originalMap = L.map;
        const maps = new WeakMap();

        L.map = function(container, options) {
            const el = typeof container === 'string' ? document.getElementById(container) : container;
            if (el) {
                const existing = maps.get(el);
                if (existing) return existing;
            }

            const map = originalMap.call(this, container, options);
            if (el && map) maps.set(el, map);
            return map;
        };

        L.__monitorMapGuardInstalled = true;
    };

    if (window.L) {
        installGuard(window.L);
        return;
    }

    let leaflet;
    Object.defineProperty(window, 'L', {
        configurable: true,
        get() { return leaflet; },
        set(value) {
            leaflet = value;
            installGuard(value);
        }
    });
})();
</script>
