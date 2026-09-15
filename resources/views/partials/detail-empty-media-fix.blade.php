<style>
    /* Only remove empty media placeholders. Real project image/map stay visible. */
    .detail-media-grid.detail-media-empty-hidden {
        display: none !important;
    }

    .detail-media-grid .detail-gallery.detail-media-hidden,
    .detail-media-grid .detail-location-map.detail-media-hidden {
        display: none !important;
    }
</style>
<script>
(() => {
    const projects = () => Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];

    const currentProject = () => {
        const modal = document.getElementById('projectDetail');
        const id = Number(modal?.dataset.projectId || 0);
        return projects().find(p => Number(p.id) === id) || null;
    };

    const hasImages = project => Array.isArray(project?.images) && project.images.some(image => image && (image.url || image.path));

    const hasMap = project => {
        let coords = project?.latlong;
        if (typeof coords === 'string') {
            try { coords = JSON.parse(coords); } catch (_) { coords = null; }
        }

        const lat = Number(coords?.lat ?? project?.lat);
        const lng = Number(coords?.lng ?? project?.lng);
        return Number.isFinite(lat) && Number.isFinite(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
    };

    const syncEmptyMedia = () => {
        const modal = document.getElementById('projectDetail');
        const media = modal?.querySelector('.detail-media-grid');
        if (!modal || !media) return;

        const project = currentProject();
        const gallery = media.querySelector('.detail-gallery');
        const map = media.querySelector('.detail-location-map');

        const showGallery = hasImages(project);
        const showMap = hasMap(project);

        gallery?.classList.toggle('detail-media-hidden', !showGallery);
        map?.classList.toggle('detail-media-hidden', !showMap);
        media.classList.toggle('detail-media-empty-hidden', !showGallery && !showMap);
    };

    const watch = () => {
        syncEmptyMedia();
        const modal = document.getElementById('projectDetail');
        if (modal && !modal.__emptyMediaWatcher) {
            modal.__emptyMediaWatcher = new MutationObserver(() => setTimeout(syncEmptyMedia, 20));
            modal.__emptyMediaWatcher.observe(modal, {
                attributes: true,
                attributeFilter: ['class', 'data-project-id']
            });
        }
    };

    window.addEventListener('monitor:projects-updated', () => setTimeout(syncEmptyMedia, 80));
    setTimeout(watch, 100);
    setInterval(syncEmptyMedia, 500);
})();
</script>
