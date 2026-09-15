<script>
(() => {
    const getProjects = () => Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];

    const getOpenProject = () => {
        const modal = document.getElementById('projectDetail');
        if (!modal?.classList.contains('open')) return null;
        const id = Number(modal.dataset.projectId || 0);
        if (!id) return null;
        return getProjects().find(project => Number(project.id) === id) || null;
    };

    const imageUrl = image => {
        if (!image) return '';
        if (image.url) return String(image.url);
        const path = image.path;
        if (!path) return '';
        return '/storage/' + String(path).replace(/^\/+/, '').split('/').map(encodeURIComponent).join('/');
    };

    const syncGalleryImage = () => {
        const project = getOpenProject();
        const gallery = document.getElementById('detailGallery');
        if (!project || !gallery) return;

        const images = Array.isArray(project.images)
            ? project.images.slice().sort((a, b) => Number(a.sort_order || 0) - Number(b.sort_order || 0))
            : [];
        const image = gallery.querySelector('img');
        if (!image || !images.length) return;

        const count = gallery.querySelector('.detail-gallery-count');
        const match = String(count?.textContent || '').match(/(\d+)\s*\/\s*(\d+)/);
        const index = Math.max(0, Math.min(images.length - 1, Number(match?.[1] || 1) - 1));
        const url = imageUrl(images[index]);
        if (url && image.src !== new URL(url, window.location.href).href) image.src = url;
    };

    const syncWhenOpen = () => setTimeout(syncGalleryImage, 30);

    const modal = document.getElementById('projectDetail');
    if (modal) {
        new MutationObserver(syncWhenOpen).observe(modal, {
            attributes: true,
            attributeFilter: ['class', 'data-project-id'],
            childList: true,
            subtree: true,
        });
    }

    const projectsContainer = document.getElementById('projects');
    if (projectsContainer) {
        projectsContainer.addEventListener('click', syncWhenOpen);
    }

    window.addEventListener('monitor:projects-updated', syncWhenOpen);

    new MutationObserver(syncGalleryImage).observe(document.body, {
        childList: true,
        subtree: true,
    });

    syncWhenOpen();
})();
</script>
