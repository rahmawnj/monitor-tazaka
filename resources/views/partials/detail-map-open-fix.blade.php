<script>
(() => {
    const refreshMapSize = () => {
        const modal = document.getElementById('projectDetail');
        const mapEl = document.getElementById('detailProjectMap');
        if (!modal?.classList.contains('open') || !mapEl) return;

        if (window.L && mapEl._leaflet_id) {
            try {
                const map = mapEl._leaflet_map || null;
                if (map) map.invalidateSize(true);
            } catch (_) {}
        }

        // detail-media-fix owns the Leaflet instance. Trigger resize events
        // after the modal has finished its open animation so the map is never
        // calculated while its container is still hidden/zero-sized.
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
