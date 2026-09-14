<style>
    .header { position: sticky; top: 0; z-index: 900; padding: 14px 0; margin-bottom: 24px; background: rgba(7, 11, 20, .88); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); }
    .project.water-update { animation: waterCardFloat .75s cubic-bezier(.22, .8, .25, 1); }
    .project.water-update::before { animation: waterDropRipple 1.05s cubic-bezier(.16, .8, .3, 1); }
    .project.water-update .bar { box-shadow: 0 0 16px rgba(255,255,255,.45); }
    .detail-bar span.realtime-update { box-shadow: 0 0 18px rgba(255,255,255,.7); }
    .detail-gallery { margin-top: 24px; }
    .detail-gallery-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:10px; }
    .detail-gallery-title { font-size:12px; margin:0; color:#cbd5e1; text-transform:uppercase; letter-spacing:.1em; }
    .detail-gallery-count { font-size:10px; color:#64748b; }
    .gallery-stage { position:relative; overflow:hidden; border:1px solid #1e293b; border-radius:16px; background:#050914; aspect-ratio:16/9; min-height:220px; display:flex; align-items:center; justify-content:center; }
    .gallery-main { width:100%; height:100%; object-fit:cover; display:block; cursor:zoom-in; transition:transform .28s cubic-bezier(.2,.8,.2,1), filter .28s ease; }
    .gallery-stage:hover .gallery-main { transform:scale(1.055); filter:brightness(1.06); }
    .gallery-empty { color:#64748b; font-size:12px; text-align:center; padding:30px; }
    .gallery-arrow { position:absolute; z-index:2; top:50%; transform:translateY(-50%); width:40px; height:40px; border:1px solid rgba(148,163,184,.3); border-radius:50%; background:rgba(2,6,23,.72); color:#f8fafc; font-size:25px; line-height:1; cursor:pointer; backdrop-filter:blur(8px); transition:.18s ease; }
    .gallery-arrow:hover { background:rgba(15,23,42,.94); border-color:#38bdf8; transform:translateY(-50%) scale(1.06); }
    .gallery-arrow.prev { left:12px; }
    .gallery-arrow.next { right:12px; }
    .gallery-thumbs { display:flex; gap:8px; overflow-x:auto; padding:9px 2px 2px; scrollbar-width:thin; }
    .gallery-thumb { flex:0 0 68px; width:68px; height:48px; padding:0; border:1px solid #334155; border-radius:9px; overflow:hidden; background:#0f172a; cursor:pointer; opacity:.62; transition:.18s ease; }
    .gallery-thumb:hover,.gallery-thumb.active { opacity:1; border-color:#38bdf8; box-shadow:0 0 0 2px rgba(56,189,248,.12); }
    .gallery-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
    .image-lightbox { position:fixed; inset:0; z-index:3000; display:none; align-items:center; justify-content:center; padding:24px; background:rgba(0,0,0,.92); backdrop-filter:blur(10px); }
    .image-lightbox.open { display:flex; }
    .lightbox-image-wrap { position:relative; max-width:96vw; max-height:92vh; display:flex; align-items:center; justify-content:center; }
    .lightbox-image { max-width:96vw; max-height:92vh; object-fit:contain; display:block; border-radius:8px; box-shadow:0 30px 90px rgba(0,0,0,.7); cursor:zoom-out; }
    .lightbox-close { position:fixed; top:18px; right:20px; width:42px; height:42px; border:1px solid rgba(255,255,255,.2); border-radius:50%; background:rgba(15,23,42,.8); color:#fff; font-size:25px; cursor:pointer; z-index:3; }
    .lightbox-close:hover { background:#1e293b; border-color:#38bdf8; }
    .lightbox-arrow { position:fixed; top:50%; transform:translateY(-50%); width:52px; height:52px; border:1px solid rgba(255,255,255,.2); border-radius:50%; background:rgba(15,23,42,.78); color:#fff; font-size:32px; line-height:1; cursor:pointer; z-index:3; }
    .lightbox-arrow:hover { background:#1e293b; border-color:#38bdf8; }
    .lightbox-arrow.prev { left:22px; }
    .lightbox-arrow.next { right:22px; }
    .lightbox-caption { position:fixed; left:50%; bottom:18px; transform:translateX(-50%); color:#cbd5e1; font-size:11px; background:rgba(2,6,23,.7); padding:7px 11px; border-radius:999px; white-space:nowrap; }
    @keyframes waterCardFloat { 0% { transform: translateY(0) scale(1); } 18% { transform: translateY(-3px) scale(1.008); } 55% { transform: translateY(1px) scale(.998); } 100% { transform: translateY(0) scale(1); } }
    @keyframes waterDropRipple { 0% { transform: scale(.72); opacity: .05; } 20% { opacity: .34; } 100% { transform: scale(1.65); opacity: 0; } }
    @media (max-width:700px) { .gallery-stage{min-height:190px}.gallery-arrow{width:34px;height:34px;font-size:21px}.lightbox-arrow{width:42px;height:42px;font-size:27px}.lightbox-arrow.prev{left:10px}.lightbox-arrow.next{right:10px}.image-lightbox{padding:12px} }
    @media (prefers-reduced-motion: reduce) { .project.water-update, .project.water-update::before, .detail-bar span.realtime-update, .gallery-main { animation: none !important; transition:none !important; } }
</style>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
<script>
(() => {
    const previousProgress = new Map();
    let galleryProjects = [];
    let galleryIndex = 0;

    const progressGradient = value => {
        const p = Math.max(0, Math.min(100, Number(value) || 0));
        if (p >= 100) return 'linear-gradient(90deg,#10b981,#34d399)';
        if (p >= 80) return 'linear-gradient(90deg,#22c55e,#84cc16)';
        if (p >= 60) return 'linear-gradient(90deg,#eab308,#facc15)';
        if (p >= 30) return 'linear-gradient(90deg,#f97316,#fbbf24)';
        return 'linear-gradient(90deg,#ef4444,#fb7185)';
    };

    const applyProgressColors = projects => projects.forEach(project => {
        const card = document.querySelector(`.project[data-project-id="${Number(project.id)}"]`);
        const bar = card?.querySelector('.bar');
        if (bar) bar.style.background = progressGradient(project.progress);
    });

    const updateDetailModal = (project, oldProgress, newProgress) => {
        const backdrop = document.getElementById('projectDetail');
        if (!backdrop?.classList.contains('open')) return;
        const openProjectId = Number(backdrop.dataset.projectId || 0);
        if (openProjectId !== Number(project.id)) return;
        const text = document.getElementById('detailProgressText');
        const bar = document.getElementById('detailProgressBar');
        if (!bar || !text) return;
        const from = Math.max(0, Math.min(100, Number(oldProgress) || 0));
        const to = Math.max(0, Math.min(100, Number(newProgress) || 0));
        bar.classList.add('realtime-update');
        bar.style.transition = 'none'; bar.style.width = `${from}%`; bar.style.background = progressGradient(to); text.textContent = `${from}%`;
        void bar.offsetWidth;
        requestAnimationFrame(() => {
            bar.style.transition = 'width 1.5s cubic-bezier(.16,1,.3,1), background 1.2s ease, box-shadow .8s ease';
            bar.style.width = `${to}%`;
            const start = performance.now();
            const tick = now => { const progress=Math.min(1,(now-start)/1500); const eased=1-Math.pow(1-progress,3); text.textContent=`${Math.round(from+(to-from)*eased)}%`; if(progress<1)requestAnimationFrame(tick);else text.textContent=`${to}%`; };
            requestAnimationFrame(tick);
        });
        setTimeout(() => { bar.classList.remove('realtime-update'); bar.style.transition='width .4s ease'; bar.style.background=progressGradient(to); },1700);
    };

    const animateProgressBar = (projectId, oldProgress, newProgress) => {
        const card = document.querySelector(`.project[data-project-id="${Number(projectId)}"]`);
        if (!card || oldProgress === undefined || oldProgress === newProgress) return;
        const bar=card.querySelector('.bar'),label=card.querySelector('.progress-label strong');
        if(!bar)return;
        const from=Math.max(0,Math.min(100,Number(oldProgress)||0)),to=Math.max(0,Math.min(100,Number(newProgress)||0));
        bar.style.transition='none';bar.style.width=`${from}%`;bar.style.background=progressGradient(to);if(label)label.textContent=`${from}%`;void bar.offsetWidth;
        requestAnimationFrame(()=>{bar.style.transition='width 1.5s cubic-bezier(.16,1,.3,1), box-shadow .8s ease';bar.style.boxShadow='0 0 16px rgba(255,255,255,.65)';bar.style.width=`${to}%`;if(label){const start=performance.now();const tick=now=>{const p=Math.min(1,(now-start)/1500),eased=1-Math.pow(1-p,3);label.textContent=`${Math.round(from+(to-from)*eased)}%`;if(p<1)requestAnimationFrame(tick);else label.textContent=`${to}%`};requestAnimationFrame(tick)}});
        setTimeout(()=>{bar.style.boxShadow='';bar.style.transition='width .4s ease';bar.style.background=progressGradient(to)},1700);
        card.classList.remove('water-update');void card.offsetWidth;card.classList.add('water-update');setTimeout(()=>card.classList.remove('water-update'),1200);
    };

    const imageUrl = image => {
        const path = String(image?.path || '');
        if (!path) return '';
        if (/^https?:\/\//i.test(path)) return path;
        return `/storage/${path.replace(/^\/+/, '')}`;
    };

    const ensureLightbox = () => {
        if (document.getElementById('projectImageLightbox')) return;
        const box=document.createElement('div');
        box.id='projectImageLightbox'; box.className='image-lightbox'; box.setAttribute('aria-hidden','true');
        box.innerHTML=`<button class="lightbox-close" type="button" aria-label="Tutup">×</button><button class="lightbox-arrow prev" type="button" aria-label="Gambar sebelumnya">‹</button><div class="lightbox-image-wrap"><img class="lightbox-image" alt="Project image"></div><button class="lightbox-arrow next" type="button" aria-label="Gambar berikutnya">›</button><div class="lightbox-caption"></div>`;
        document.body.appendChild(box);
        box.addEventListener('click',e=>{if(e.target===box||e.target.classList.contains('lightbox-image')||e.target.classList.contains('lightbox-close'))closeLightbox()});
        box.querySelector('.prev').addEventListener('click',()=>moveGallery(-1,true));
        box.querySelector('.next').addEventListener('click',()=>moveGallery(1,true));
        box.querySelector('.lightbox-close').addEventListener('click',closeLightbox);
    };

    const renderLightbox = () => {
        ensureLightbox();
        const box=document.getElementById('projectImageLightbox');
        const image=galleryProjects[galleryIndex];
        if(!image)return;
        const img=box.querySelector('.lightbox-image');
        img.src=imageUrl(image); img.alt=image.original_name||'Project image';
        box.querySelector('.lightbox-caption').textContent=`${galleryIndex+1} / ${galleryProjects.length}${image.original_name?' · '+image.original_name:''}`;
        box.querySelector('.prev').style.display=galleryProjects.length>1?'block':'none';
        box.querySelector('.next').style.display=galleryProjects.length>1?'block':'none';
    };

    const openLightbox = index => {
        if(!galleryProjects.length)return;
        galleryIndex=Math.max(0,Math.min(galleryProjects.length-1,index));
        renderLightbox();
        const box=document.getElementById('projectImageLightbox'); box.classList.add('open'); box.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden';
    };
    const closeLightbox = () => { const box=document.getElementById('projectImageLightbox');if(!box)return;box.classList.remove('open');box.setAttribute('aria-hidden','true');document.body.style.overflow=''; };
    const moveGallery = (direction, lightbox=false) => { if(galleryProjects.length<2)return; galleryIndex=(galleryIndex+direction+galleryProjects.length)%galleryProjects.length; if(lightbox)renderLightbox(); else renderGallery(); };

    const renderGallery = () => {
        const mount=document.getElementById('detailGallery'); if(!mount)return;
        if(!galleryProjects.length){mount.innerHTML='<div class="gallery-stage"><div class="gallery-empty">Belum ada gambar project.</div></div>';return;}
        const image=galleryProjects[galleryIndex];
        mount.innerHTML=`<div class="detail-gallery-head"><h3 class="detail-gallery-title">Project Images</h3><span class="detail-gallery-count">${galleryIndex+1} / ${galleryProjects.length}</span></div><div class="gallery-stage"><img class="gallery-main" src="${escapeHtml(imageUrl(image))}" alt="${escapeHtml(image.original_name||'Project image')}"><button class="gallery-arrow prev" type="button" aria-label="Gambar sebelumnya">‹</button><button class="gallery-arrow next" type="button" aria-label="Gambar berikutnya">›</button></div><div class="gallery-thumbs">${galleryProjects.map((item,i)=>`<button class="gallery-thumb ${i===galleryIndex?'active':''}" type="button" data-gallery-index="${i}" aria-label="Gambar ${i+1}"><img src="${escapeHtml(imageUrl(item))}" alt=""></button>`).join('')}</div>`;
        mount.querySelector('.gallery-main').addEventListener('click',()=>openLightbox(galleryIndex));
        mount.querySelector('.prev').addEventListener('click',()=>moveGallery(-1));
        mount.querySelector('.next').addEventListener('click',()=>moveGallery(1));
        mount.querySelectorAll('.gallery-thumb').forEach(btn=>btn.addEventListener('click',()=>{galleryIndex=Number(btn.dataset.galleryIndex||0);renderGallery()}));
    };

    const loadGalleryForProject = async projectId => {
        let project = Array.isArray(window.__monitorProjects) ? window.__monitorProjects.find(p=>Number(p.id)===Number(projectId)) : null;
        if(!project && typeof initialProjects !== 'undefined') project=initialProjects.find(p=>Number(p.id)===Number(projectId));
        if(!project){
            try { const response=await fetch(@json(route('monitor.data')),{headers:{'Accept':'application/json'},cache:'no-store'});const data=await response.json();project=data.projects?.find(p=>Number(p.id)===Number(projectId)); } catch(e) { console.error(e); }
        }
        galleryProjects=Array.isArray(project?.images)?project.images.slice().sort((a,b)=>Number(a.sort_order||0)-Number(b.sort_order||0)):[];
        galleryIndex=0;
        renderGallery();
    };

    const injectGallery = () => {
        const modal=document.querySelector('#projectDetail .detail-modal'); if(!modal || document.getElementById('detailGallery'))return;
        const section=document.createElement('div');section.id='detailGallery';section.className='detail-gallery';
        const notes=document.getElementById('detailNotes')?.closest('.detail-section');
        if(notes)notes.parentNode.insertBefore(section,notes);else modal.querySelector('.detail-hint')?.before(section);
    };

    const watchModal = () => {
        const backdrop=document.getElementById('projectDetail');if(!backdrop)return;
        injectGallery();
        const observer=new MutationObserver(()=>{
            if(backdrop.classList.contains('open')){
                const id=Number(backdrop.dataset.projectId||0);
                if(id)loadGalleryForProject(id);
            }
        });
        observer.observe(backdrop,{attributes:true,attributeFilter:['class','data-project-id']});
    };

    const refreshMonitor = async (event = {}) => {
        try {
            const response=await fetch(@json(route('monitor.data')),{headers:{'Accept':'application/json'},cache:'no-store'});if(!response.ok)return;
            const data=await response.json();if(!Array.isArray(data.projects))return;
            window.__monitorProjects=data.projects;
            const changedId=Number(event.project_id||0),changedProject=data.projects.find(p=>Number(p.id)===changedId),oldProgress=previousProgress.get(changedId),newProgress=changedProject?Number(changedProject.progress||0):undefined,progressChanged=changedProject&&oldProgress!==undefined&&oldProgress!==newProgress;
            render(data.projects,data.summary);renderMap(data.projects);applyProgressColors(data.projects);data.projects.forEach(p=>previousProgress.set(Number(p.id),Number(p.progress||0)));
            if(changedProject&&progressChanged)requestAnimationFrame(()=>{animateProgressBar(changedId,oldProgress,newProgress);updateDetailModal(changedProject,oldProgress,newProgress)});
            const backdrop=document.getElementById('projectDetail');if(backdrop?.classList.contains('open')){injectGallery();const id=Number(backdrop.dataset.projectId||0);if(id)loadGalleryForProject(id)}
        } catch(error){console.error('Gagal memperbarui monitor realtime:',error)}
    };

    document.addEventListener('click',event=>{
        const card=event.target.closest?.('.project[data-project-id]');
        if(card){const id=Number(card.dataset.projectId||0);setTimeout(()=>{const backdrop=document.getElementById('projectDetail');if(backdrop?.classList.contains('open')){backdrop.dataset.projectId=id;injectGallery();loadGalleryForProject(id)}},0)}
    },true);
    document.addEventListener('keydown',event=>{if(event.key==='Escape'){const box=document.getElementById('projectImageLightbox');if(box?.classList.contains('open')){event.stopPropagation();closeLightbox()}}if((event.key==='ArrowLeft'||event.key==='ArrowRight')&&document.getElementById('projectImageLightbox')?.classList.contains('open')){event.preventDefault();moveGallery(event.key==='ArrowLeft'?-1:1,true)}});

    const start=()=>{try{window.__monitorProjects=typeof initialProjects!=='undefined'?initialProjects:[];window.__monitorProjects.forEach(p=>previousProgress.set(Number(p.id),Number(p.progress||0)));}catch(e){}watchModal();applyProgressColors(window.__monitorProjects||[]);};
    if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',start);else start();

    const pusher=new Pusher(@json(env('REVERB_APP_KEY')),{cluster:'mt1',wsHost:@json(env('REVERB_HOST','127.0.0.1')),wsPort:Number(@json(env('REVERB_PORT',8085))),wssPort:Number(@json(env('REVERB_PORT',8085))),forceTLS:@json(env('REVERB_SCHEME','http'))==='https',enabledTransports:['ws','wss'],disableStats:true});
    const channel=pusher.subscribe('monitor');channel.bind('project.updated',refreshMonitor);pusher.connection.bind('connected',()=>console.info('Monitor realtime terhubung ke Reverb.'));pusher.connection.bind('error',error=>console.error('Koneksi Reverb gagal:',error));
})();
</script>
