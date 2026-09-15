<style>
    .detail-backdrop { padding:16px !important; }
    .detail-modal { width:min(1200px, calc(100vw - 32px)) !important; max-width:1200px !important; height:min(760px, calc(100vh - 32px)) !important; max-height:calc(100vh - 32px) !important; overflow:hidden !important; padding:32px !important; }
    .detail-modal > .detail-grid,
    .detail-modal > .detail-progress,
    .detail-modal > .detail-section,
    .detail-modal > .detail-hint { width:52%; max-width:52%; }
    .detail-media-grid { position:absolute; top:96px; right:32px; width:43%; display:grid; grid-template-columns:1fr; gap:14px; margin:0 !important; }
    .detail-gallery, .detail-location-map { position:relative; min-width:0; overflow:hidden; border:1px solid #1e293b; border-radius:16px; background:#080f1c; }
    .detail-gallery { height:300px; min-height:300px; }
    .detail-gallery img { display:block; width:100%; height:300px; object-fit:cover; cursor:zoom-in; }
    .detail-gallery-empty { height:300px; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:12px; }
    .detail-gallery-nav { position:absolute; inset:0; display:flex; align-items:center; justify-content:space-between; padding:0 12px; pointer-events:none; }
    .detail-gallery-nav button { pointer-events:auto; width:36px; height:36px; border:1px solid rgba(255,255,255,.2); border-radius:50%; background:rgba(2,6,23,.72); color:#fff; cursor:pointer; font-size:20px; }
    .detail-gallery-count { position:absolute; right:10px; bottom:10px; padding:5px 8px; border-radius:8px; background:rgba(2,6,23,.78); color:#e2e8f0; font-size:10px; }
    .detail-location-map { height:210px; min-height:210px; }
    #detailProjectMap { width:100%; height:210px; }
    .detail-lightbox { position:fixed; inset:0; z-index:7000; display:none; align-items:center; justify-content:center; padding:24px; background:rgba(0,0,0,.9); }
    .detail-lightbox.open { display:flex; }
    .detail-lightbox img { max-width:94vw; max-height:90vh; object-fit:contain; border-radius:12px; }
    .detail-lightbox-close { position:absolute; top:18px; right:22px; width:40px; height:40px; border:1px solid rgba(255,255,255,.25); border-radius:50%; background:rgba(15,23,42,.8); color:#fff; font-size:24px; cursor:pointer; }
    .detail-map-note { height:210px; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:12px; }
    @media(max-width:900px) {
        .detail-modal { height:auto !important; max-height:calc(100vh - 20px) !important; overflow:auto !important; }
        .detail-modal > .detail-grid,
        .detail-modal > .detail-progress,
        .detail-modal > .detail-section,
        .detail-modal > .detail-hint { width:100%; max-width:100%; }
        .detail-media-grid { position:static; width:100%; display:grid; grid-template-columns:1fr; margin-top:22px !important; }
    }
    @media(max-width:700px) {
        .detail-modal { width:calc(100vw - 20px) !important; padding:22px !important; }
    }
</style>
<script>
(() => {
    let detailMap = null;
    let detailMarker = null;
    let galleryIndex = 0;
    const projects = () => Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];
    const currentProject = () => { const modal=document.getElementById('projectDetail'); const id=Number(modal?.dataset.projectId||0); return projects().find(p=>Number(p.id)===id)||null; };
    const imageUrl = image => { if(!image)return ''; if(image.url)return String(image.url); if(!image.path)return ''; return '/storage/'+String(image.path).replace(/^\/+/, '').split('/').map(encodeURIComponent).join('/'); };
    const sortedImages = project => Array.isArray(project?.images) ? project.images.slice().sort((a,b)=>Number(a.sort_order||0)-Number(b.sort_order||0)) : [];
    const ensureMedia = () => {
        const modal=document.getElementById('projectDetail'), grid=modal?.querySelector('.detail-grid');
        if(!modal||!grid||modal.querySelector('.detail-media-grid'))return;
        const media=document.createElement('div'); media.className='detail-media-grid';
        media.innerHTML='<div class="detail-gallery" id="detailGallery"><div class="detail-gallery-empty">Belum ada image project.</div></div><div class="detail-location-map"><div id="detailProjectMap"></div></div>';
        grid.insertAdjacentElement('afterend',media);
        media.querySelector('.detail-gallery').addEventListener('click',e=>{const image=e.target.closest('img');if(image)openLightbox(image.src);});
    };
    const renderGallery=()=>{const project=currentProject(),gallery=document.getElementById('detailGallery');if(!gallery)return;const images=sortedImages(project);if(!images.length){gallery.innerHTML='<div class="detail-gallery-empty">Belum ada image project.</div>';return;}galleryIndex=Math.max(0,Math.min(galleryIndex,images.length-1));gallery.innerHTML=`<img src="${imageUrl(images[galleryIndex])}" alt="Project image" loading="eager"><div class="detail-gallery-nav"><button type="button" data-gallery-prev>‹</button><button type="button" data-gallery-next>›</button></div><div class="detail-gallery-count">${galleryIndex+1} / ${images.length}</div>`;gallery.querySelector('[data-gallery-prev]').onclick=e=>{e.stopPropagation();changeImage(-1)};gallery.querySelector('[data-gallery-next]').onclick=e=>{e.stopPropagation();changeImage(1)};};
    const changeImage=direction=>{const images=sortedImages(currentProject());if(!images.length)return;galleryIndex=(galleryIndex+direction+images.length)%images.length;renderGallery();};
    const renderMap=()=>{const project=currentProject(),el=document.getElementById('detailProjectMap');if(!el||!window.L)return;let coords=project?.latlong;if(typeof coords==='string'){try{coords=JSON.parse(coords)}catch(_){coords=null}}const lat=Number(coords?.lat??project?.lat),lng=Number(coords?.lng??project?.lng);if(!Number.isFinite(lat)||!Number.isFinite(lng)){el.innerHTML='<div class="detail-map-note">Lokasi project belum tersedia.</div>';if(detailMap){detailMap.remove();detailMap=null;detailMarker=null}return;}if(!detailMap){el.innerHTML='';detailMap=L.map(el,{zoomControl:true,attributionControl:true,dragging:true,scrollWheelZoom:true,doubleClickZoom:true,touchZoom:true,boxZoom:true}).setView([lat,lng],13);L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap contributors'}).addTo(detailMap);}else{detailMap.setView([lat,lng],13);detailMap.invalidateSize(true);}if(detailMarker)detailMarker.remove();detailMarker=L.marker([lat,lng]).addTo(detailMap).bindPopup(project?.name||'Project').openPopup();setTimeout(()=>detailMap?.invalidateSize(true),100);setTimeout(()=>detailMap?.invalidateSize(true),400);};
    const openLightbox=src=>{let box=document.getElementById('detailImageLightbox');if(!box){box=document.createElement('div');box.id='detailImageLightbox';box.className='detail-lightbox';box.innerHTML='<button type="button" class="detail-lightbox-close">×</button><img alt="Project image fullscreen">';document.body.appendChild(box);box.onclick=e=>{if(e.target===box||e.target.closest('.detail-lightbox-close'))box.classList.remove('open')}}box.querySelector('img').src=src;box.classList.add('open')};
    const sync=()=>{ensureMedia();const modal=document.getElementById('projectDetail');if(!modal?.classList.contains('open'))return;galleryIndex=0;renderGallery();renderMap();};
    const modal=document.getElementById('projectDetail');
    if(modal)new MutationObserver(()=>setTimeout(sync,30)).observe(modal,{attributes:true,attributeFilter:['class','data-project-id']});
    document.addEventListener('click',e=>{const card=e.target.closest('.project[data-project-id]');if(card&&modal)modal.dataset.projectId=card.dataset.projectId;});
    window.addEventListener('monitor:projects-updated',()=>setTimeout(sync,80));
    document.addEventListener('keydown',e=>{if(e.key==='Escape')document.getElementById('detailImageLightbox')?.classList.remove('open');if(e.key==='ArrowLeft')changeImage(-1);if(e.key==='ArrowRight')changeImage(1)});
    setTimeout(sync,100);
})();
</script>
