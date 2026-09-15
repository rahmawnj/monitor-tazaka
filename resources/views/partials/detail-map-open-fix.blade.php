<script>
(() => {
    // Reverb/detail scripts can share the same detail map container.
    // Leaflet throws when a second script calls L.map() for that container.
    // Keep one map instance per DOM element for this page.
    const installLeafletMapGuard = () => {
        const L = window.L;
        if (!L || L.__monitorDetailMapGuard) return;
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
        L.__monitorDetailMapGuard = true;
    };

    installLeafletMapGuard();

    const refreshMapSize = () => {
        const modal = document.getElementById('projectDetail');
        const mapEl = document.getElementById('detailProjectMap');
        if (!modal?.classList.contains('open') || !mapEl) return;
        window.dispatchEvent(new Event('resize'));
    };

    const schedule = () => {
        [0, 80, 180, 350, 600, 900].forEach(delay => setTimeout(refreshMapSize, delay));
    };

    const modal = document.getElementById('projectDetail');
    if (modal) {
        new MutationObserver(() => {
            if (modal.classList.contains('open')) schedule();
        }).observe(modal, { attributes: true, attributeFilter: ['class', 'data-project-id'] });

        modal.addEventListener('transitionend', () => {
            if (modal.classList.contains('open')) schedule();
        });
    }

    document.addEventListener('click', e => {
        if (e.target.closest('.project[data-project-id]')) schedule();
    });
})();
</script>
