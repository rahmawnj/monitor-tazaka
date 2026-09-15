<style>
    .detail-media-grid { display:grid; grid-template-columns:minmax(0,1.15fr) minmax(0,.85fr); gap:14px; margin-top:22px; }
    .detail-gallery, .detail-location-map { position:relative; min-width:0; overflow:hidden; border:1px solid #1e293b; border-radius:16px; background:#080f1c; }
    .detail-gallery { min-height:250px; }
    .detail-gallery img { display:block; width:100%; height:250px; object-fit:cover; cursor:zoom-in; }
    .detail-gallery-empty { height:250px; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:12px; }
    .detail-gallery-nav { position:absolute; inset:0; display:flex; align-items:center; justify-content:space-between; padding:0 10px; pointer-events:none; }
    .detail-gallery-nav button { pointer-events:auto; width:34px; height:34px; border:1px solid rgba(255,255,255,.2); border-radius:50%; background:rgba(2,6,23,.72); color:#fff; cursor:pointer; font-size:20px; }
    .detail-gallery-count { position:absolute; right:10px; bottom:10px; padding:5px 8px; border-radius:8px; background:rgba(2,6,23,.78); color:#e2e8f0; font-size:10px; }
    .detail-location-map { min-height:250px; }
    #detailProjectMap { width:100%; height:250px; }
    .detail-lightbox { position:fixed; inset:0; z-index:7000; display:none; align-items:center; justify-content:center; padding:24px; background:rgba(0,0,0,.9); }
    .detail-lightbox.open { display:flex; }
    .detail-lightbox img { max-width:94vw; max-height:90vh; object-fit:contain; border-radius:12px; }
    .detail-lightbox-close { position:absolute; top:18px; right:22px; width:40px; height:40px; border:1px solid rgba(255,255,255,.25); border-radius:50%; background:rgba(15,23,42,.8); color:#fff; font-size:24px; cursor:pointer; }
    @media(max-width:700px) { .detail-media-grid { grid-template-columns:1fr; } }
</style>
<script>
(() => {
    let detailMap = null;
    let detailMarker = null;
    let galleryIndex = 0;

    const projects = () => Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];
    const currentProject = () => {
        const modal = document.getElementById('projectDetail');
        const id = Number(modal?.dataset.projectId || 0);
        return projects().find(p => Number(p.id) === id) || null;
    };
    const imageUrl = image => {
        if (!image) return '';
        if (image.url) return String(image.url);
        if (!image.path) return '';
        return '/storage/' + String(image.path).replace(/^\/+/, '').split('/').map(encodeURIComponent).join('/');
    };
    const ensureMedia = () => {
        const modal = document.getElementById('projectDetail');
        const grid = modal?.querySelector('.detail-grid');
        if (!modal || !grid || modal.querySelector('.detail-media-grid')) return;
        const media = document.createElement('div');
        media.className = 'detail-media-grid';
        media.innerHTML = `
            <div class="detail-gallery" id="detailGallery">
                <div class="detail-gallery-empty">Belum ada image project.</div>
                <div class="detail-gallery-nav" hidden>
                    <button type="button" data-gallery-prev aria-label="Previous image">‹</button>
                    <button type="button" data-gallery-next aria-label="Next image">›</button>
                </div>
                <div class="detail-gallery-count" hidden></div>
            </div>
            <div class="detail-location-map"><div id="detailProjectMap"></div></div>
        `;
        grid.insertAdjacentElement('afterend', media);
        media.querySelector('[data-gallery-prev]').addEventListener('click', e => { e.stopPropagation(); changeImage(-1); });
        media.querySelector('[data-gallery-next]').addEventListener('click', e => { e.stopPropagation(); changeImage(1); });
        document.getElementById('detailGallery').addEventListener('click', e => {
            const image = e.target.closest('img');
            if (image) openLightbox(image.src);
        });
    };
    const sortedImages = project => Array.isArray(project?.images) ? project.images.slice().sort((a,b) => Number(a.sort_order || 0) - Number(b.sort_order || 0)) : [];
    const renderGallery = () => {
        const project = currentProject();
        const gallery = document.getElementById('detailGallery');
        if (!gallery) return;
        const images = sortedImages(project);
        if (!images.length) {
            gallery.innerHTML = '<div class="detail-gallery-empty">Belum ada image project.</div>';
            return;
        }
        galleryIndex = Math.max(0, Math.min(galleryIndex, images.length - 1));
        const url = imageUrl(images[galleryIndex]);
        gallery.innerHTML = `<img src="${url}" alt="Project image" loading="eager"><div class="detail-gallery-nav"><button type="button" data-gallery-prev aria-label="Previous image">‹</button><button type="button" data-gallery-next aria-label="Next image">›</button></div><div class="detail-gallery-count">${galleryIndex + 1} / ${images.length}</div>`;
        gallery.querySelector('[data-gallery-prev]').addEventListener('click', e => { e.stopPropagation(); changeImage(-1); });
        gallery.querySelector('[data-gallery-next]').addEventListener('click', e => { e.stopPropagation(); changeImage(1); });
        gallery.querySelector('img').addEventListener('click', e => { e.stopPropagation(); openLightbox(e.currentTarget.src); });
    };
    const changeImage = direction => {
        const images = sortedImages(currentProject());
        if (!images.length) return;
        galleryIndex = (galleryIndex + direction + images.length) % images.length;
        renderGallery();
    };
    const renderMap = () => {
        const project = currentProject();
        const mapElement = document.getElementById('detailProjectMap');
        if (!mapElement || !window.L) return;
        let lat = Number(project?.latlong?.lat ?? project?.lat);
        let lng = Number(project?.latlong?.lng ?? project?.lng);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
            mapElement.innerHTML = '<div style="height:250px;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:12px">Lokasi project belum tersedia.</div>';
            return;
        }
        if (!detailMap) {
            detailMap = L.map(mapElement, { zoomControl: true, attributionControl: true }).setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(detailMap);
        } else {
            detailMap.setView([lat, lng], 13);
            detailMap.invalidateSize();
        }
        if (detailMarker) detailMarker.remove();
        detailMarker = L.marker([lat, lng]).addTo(detailMap).bindPopup(project?.name || 'Project').openPopup();
        setTimeout(() => detailMap?.invalidateSize(), 80);
    };
    const openLightbox = src => {
        let box = document.getElementById('detailImageLightbox');
        if (!box) {
            box = document.createElement('div');
            box.id = 'detailImageLightbox';
            box.className = 'detail-lightbox';
            box.innerHTML = '<button type="button" class="detail-lightbox-close" aria-label="Close">×</button><img alt="Project image fullscreen">';
            document.body.appendChild(box);
            box.addEventListener('click', e => { if (e.target === box || e.target.closest('.detail-lightbox-close')) box.classList.remove('open'); });
        }
        box.querySelector('img').src = src;
        box.classList.add('open');
    };
    const sync = () => {
        ensureMedia();
        const modal = document.getElementById('projectDetail');
        if (!modal?.classList.contains('open')) return;
        galleryIndex = 0;
        renderGallery();
        renderMap();
    };
    const modal = document.getElementById('projectDetail');
    if (modal) new MutationObserver(() => setTimeout(sync, 30)).observe(modal, { attributes:true, attributeFilter:['class','data-project-id'] });
    window.addEventListener('monitor:projects-updated', () => setTimeout(sync, 50));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') document.getElementById('detailImageLightbox')?.classList.remove('open'); if (e.key === 'ArrowLeft') changeImage(-1); if (e.key === 'ArrowRight') changeImage(1); });
    setTimeout(sync, 100);
})();
</script>
