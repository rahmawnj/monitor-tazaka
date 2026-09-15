<script>
(() => {
    let progressAnimationFrame = null;
    let previousProjects = [];
    let notificationTimer = null;

    const clampProgress = value => Math.max(0, Math.min(100, Number(value) || 0));

    const decodeHtml = value => {
        const textarea = document.createElement('textarea');
        textarea.innerHTML = String(value ?? '');
        return textarea.value;
    };

    const animateNumber = (element, from, to, duration = 900) => {
        if (!element) return;
        const start = clampProgress(from);
        const end = clampProgress(to);
        if (start === end) {
            element.textContent = end + '%';
            element.dataset.progressValue = String(end);
            return;
        }
        const started = performance.now();
        const ease = t => 1 - Math.pow(1 - t, 3);
        const step = now => {
            const t = Math.min(1, (now - started) / duration);
            const value = Math.round(start + (end - start) * ease(t));
            element.textContent = value + '%';
            element.dataset.progressValue = String(value);
            if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    const animateCardProgress = (card, from, to) => {
        if (!card) return;
        const bar = card.querySelector('.bar');
        const text = card.querySelector('.progress-label strong');
        const start = clampProgress(from);
        const end = clampProgress(to);
        if (bar) {
            bar.style.transition = 'none';
            bar.style.width = start + '%';
            void bar.offsetWidth;
            requestAnimationFrame(() => {
                bar.style.transition = 'width 900ms cubic-bezier(.22,1,.36,1)';
                bar.style.width = end + '%';
            });
        }
        if (text) {
            text.textContent = start + '%';
            text.dataset.progressValue = String(start);
            animateNumber(text, start, end, 900);
        }
    };

    const getNotification = () => {
        let el = document.getElementById('monitorChangeNotification');
        if (el) return el;
        el = document.createElement('div');
        el.id = 'monitorChangeNotification';
        el.style.cssText = 'position:fixed;right:26px;top:26px;z-index:6000;min-width:300px;max-width:430px;padding:15px 17px;border:1px solid rgba(56,189,248,.38);border-radius:16px;background:rgba(10,18,32,.96);box-shadow:0 18px 50px rgba(0,0,0,.42);backdrop-filter:blur(14px);transform:translateY(-14px) scale(.97);opacity:0;pointer-events:none;transition:opacity .25s ease,transform .3s cubic-bezier(.22,1,.36,1);font-family:Inter,ui-sans-serif,system-ui,sans-serif';
        el.innerHTML = '<div style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#7dd3fc;font-weight:800;margin-bottom:5px">Project Updated</div><div data-notification-project style="font-size:14px;color:#f8fafc;font-weight:800"></div><div data-notification-detail style="font-size:11px;color:#94a3b8;margin-top:4px"></div>';
        document.body.appendChild(el);
        return el;
    };

    const showNotification = (project, action = 'updated', oldProject = null) => {
        const el = getNotification();
        const projectName = project?.name || oldProject?.name || 'Project';
        const labels = { created:'Project ditambahkan', updated:'Project diperbarui', progress:'Progress diperbarui', reordered:'Urutan project diperbarui', deleted:'Project dihapus' };
        el.querySelector('[data-notification-project]').textContent = projectName;
        el.querySelector('[data-notification-detail]').textContent = labels[action] || 'Project diperbarui';
        clearTimeout(notificationTimer);
        requestAnimationFrame(() => { el.style.opacity='1'; el.style.transform='translateY(0) scale(1)'; });
        notificationTimer = setTimeout(() => { el.style.opacity='0'; el.style.transform='translateY(-14px) scale(.97)'; }, 3200);
    };

    const getChange = (oldProjects, newProjects) => {
        const oldById = new Map(oldProjects.map(p => [Number(p.id), p]));
        const newById = new Map(newProjects.map(p => [Number(p.id), p]));
        for (const project of newProjects) {
            const oldProject = oldById.get(Number(project.id));
            if (!oldProject) return { project, oldProject:null, action:'created' };
            if (clampProgress(oldProject.progress) !== clampProgress(project.progress)) return { project, oldProject, action:'progress' };
            if (Number(oldProject.sort_order) !== Number(project.sort_order)) return { project, oldProject, action:'reordered' };
            if (JSON.stringify(oldProject) !== JSON.stringify(project)) return { project, oldProject, action:'updated' };
        }
        for (const oldProject of oldProjects) {
            if (!newById.has(Number(oldProject.id))) return { project:null, oldProject, action:'deleted' };
        }
        return null;
    };

    const animateChangedCards = () => {
        const current = Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];
        const oldById = new Map(previousProjects.map(p => [Number(p.id), p]));
        current.forEach(project => {
            const oldProject = oldById.get(Number(project.id));
            if (!oldProject) return;
            const oldProgress = clampProgress(oldProject.progress);
            const newProgress = clampProgress(project.progress);
            if (oldProgress === newProgress) return;
            const card = document.querySelector(`.project[data-project-id="${Number(project.id)}"]`);
            animateCardProgress(card, oldProgress, newProgress);
        });
    };

    const syncDetailFields = (projects) => {
        const modal = document.getElementById('projectDetail');
        if (!modal?.classList.contains('open')) return;
        const id = Number(modal.dataset.projectId || 0);
        if (!id) return;
        const project = (projects || []).find(p => Number(p.id) === id);
        if (!project) return;
        const setText = (selector, value) => { const el=document.querySelector(selector); if(el) el.textContent=value ?? '-'; };
        const setRich = (selector, value) => { const el=document.querySelector(selector); if(el) el.innerHTML=decodeHtml(value || '-'); };
        const typeLabels = { tazaka_order:'Tazaka Order', subcontract:'Subcontract', external:'External' };
        const formatDate = value => { if(!value) return '-'; const raw=String(value).slice(0,10); const date=new Date(raw+'T00:00:00'); if(Number.isNaN(date.getTime())) return String(value); return new Intl.DateTimeFormat('id-ID',{day:'2-digit',month:'long',year:'numeric'}).format(date); };
        const formatMonth = value => { if(!value) return '-'; const raw=String(value).slice(0,7); const date=new Date(raw+'-01T00:00:00'); if(Number.isNaN(date.getTime())) return String(value); return new Intl.DateTimeFormat('id-ID',{month:'long',year:'numeric'}).format(date); };
        setText('#detailTitle', project.name || '-');
        setText('#detailClient', project.client || '-');
        setText('#detailType', typeLabels[project.project_type] || project.project_type || '-');
        setText('#detailMonth', formatMonth(project.project_month));
        setText('#detailTarget', formatDate(project.target_completion_date));
        setText('#detailLocation', project.location || '-');
        setRich('#detailDescription', project.description || '-');
        setRich('#detailNotes', project.notes || '-');
        animateDetailProgress(project.progress);
    };

    const animateDetailProgress = target => {
        const progressText=document.getElementById('detailProgressText');
        const progressBar=document.getElementById('detailProgressBar');
        if(!progressText && !progressBar) return;
        const end=clampProgress(target);
        const start=clampProgress(Number(progressText?.dataset.progressValue ?? String(progressText?.textContent || '').replace(/[^0-9.-]/g,'')) || 0);
        if(progressAnimationFrame) cancelAnimationFrame(progressAnimationFrame);
        if(start===end){ if(progressText){progressText.textContent=end+'%';progressText.dataset.progressValue=String(end)} if(progressBar)progressBar.style.width=end+'%'; return; }
        const duration=850,started=performance.now(),ease=t=>1-Math.pow(1-t,3);
        if(progressBar){progressBar.style.transition='none';progressBar.style.width=start+'%';void progressBar.offsetWidth;progressBar.style.transition=`width ${duration}ms cubic-bezier(.22,1,.36,1)`;progressBar.style.width=end+'%';}
        const step=now=>{const t=Math.min(1,(now-started)/duration),value=Math.round(start+(end-start)*ease(t));if(progressText){progressText.textContent=value+'%';progressText.dataset.progressValue=String(value)}if(t<1)progressAnimationFrame=requestAnimationFrame(step);else{progressAnimationFrame=null;if(progressText){progressText.textContent=end+'%';progressText.dataset.progressValue=String(end)}}};
        progressAnimationFrame=requestAnimationFrame(step);
    };

    let projects=Array.isArray(window.__monitorProjects)?window.__monitorProjects:[];
    previousProjects=projects.slice();
    try {
        Object.defineProperty(window,'__monitorProjects',{configurable:true,get:()=>projects,set:value=>{const next=Array.isArray(value)?value:[],old=projects.slice(),change=getChange(old,next);previousProjects=old;projects=next;if(change&&old.length>0)showNotification(change.project,change.action,change.oldProject);window.dispatchEvent(new CustomEvent('monitor:projects-updated',{detail:{previous:old,projects:next}}));}});
    } catch(e){ console.warn('Realtime detail state hook gagal:',e); }
    window.addEventListener('monitor:projects-updated',event=>{ const detail=event.detail||{}; syncDetailFields(detail.projects||[]); });
    const projectsContainer=document.getElementById('projects');
    if(projectsContainer){const observer=new MutationObserver(()=>requestAnimationFrame(animateChangedCards));observer.observe(projectsContainer,{childList:true});}
    const originalFetch=window.fetch.bind(window);
    window.fetch=async(...args)=>{const response=await originalFetch(...args);try{const requestUrl=typeof args[0]==='string'?args[0]:(args[0]?.url||'');if(requestUrl.includes('/monitor/data'))response.clone().json().then(data=>syncDetailFields(data?.projects||[])).catch(()=>{});}catch(e){console.warn('Realtime detail fetch sync gagal:',e)}return response};
    syncDetailFields(window.__monitorProjects||[]);
})();
</script>
