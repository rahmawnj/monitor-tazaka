<script>
(() => {
    const refreshMapSize = () => {
        const modal = document.getElementById('projectDetail');
        const mapEl = document.getElementById('detailProjectMap');
        if (!modal?.classList.contains('open') || !mapEl) return;
        window.dispatchEvent(new Event('resize'));
        modal.dispatchEvent(new Event('transitionend', { bubbles: false }));
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
