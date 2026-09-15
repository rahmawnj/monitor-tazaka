<style>
    .map-card .map-wrap { height:360px; min-height:360px; max-height:360px; }
    .map-card .map { height:100%; max-height:360px; }
    .project .project-label, .project .client { display:none !important; }
    .project .project-bottom { display:flex !important; }
    .project .project-bottom > div:last-child { text-align:right !important; }
    .project .chip { width:92px; height:42px; margin-top:16px; padding:7px 10px; border-radius:9px; background:rgba(15,23,42,.78); border:1px solid rgba(56,189,248,.22); box-shadow:none; color:#94a3b8; font-size:8px; line-height:1.25; letter-spacing:.12em; text-transform:uppercase; }
    .project .chip::before, .project .chip::after { display:none; }
    .project .chip strong { display:block; margin-top:2px; color:#e2e8f0; font-size:10px; letter-spacing:.02em; text-transform:none; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .project .project-id { text-align:right; }
    .project .project-id strong { display:block; margin-top:3px; color:#e2e8f0; font-size:11px; letter-spacing:0; text-transform:none; }
    #projects.monitor-projects-collapsed .project:nth-child(n + 7) { display:none !important; }
    .projects-more-wrap { display:flex; justify-content:center; margin:18px 0 4px; }
    .projects-more-button { border:1px solid rgba(56,189,248,.28); background:rgba(15,23,42,.72); color:#e2e8f0; border-radius:10px; padding:9px 18px; font-size:12px; font-weight:700; cursor:pointer; transition:.2s ease; }
    .projects-more-button:hover { background:rgba(30,41,59,.9); border-color:rgba(56,189,248,.48); }
</style>

<script>
(() => {
    const formatDate = value => {
        if (!value) return '-';
        const raw = String(value).slice(0,10);
        const date = new Date(raw + 'T00:00:00');
        if (Number.isNaN(date.getTime())) return String(value);
        return new Intl.DateTimeFormat('id-ID', {day:'2-digit', month:'short', year:'numeric'}).format(date);
    };
    const remainingDays = value => {
        if (!value) return '-';
        const raw = String(value).slice(0,10);
        const target = new Date(raw + 'T23:59:59');
        if (Number.isNaN(target.getTime())) return '-';
        const today = new Date();
        const start = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        const end = new Date(target.getFullYear(), target.getMonth(), target.getDate());
        return Math.ceil((end - start) / 86400000);
    };
    const projects = () => Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];
    const syncMoreButton = () => {
        const container = document.getElementById('projects'); if (!container) return;
        const cards = container.querySelectorAll('.project'); const oldButton = document.querySelector('.projects-more-wrap');
        if (cards.length <= 6) { container.classList.remove('monitor-projects-collapsed'); oldButton?.remove(); return; }
        if (!container.classList.contains('monitor-projects-expanded')) container.classList.add('monitor-projects-collapsed');
        let wrap = document.querySelector('.projects-more-wrap');
        if (!wrap) {
            wrap = document.createElement('div'); wrap.className = 'projects-more-wrap';
            const button = document.createElement('button'); button.type='button'; button.className='projects-more-button';
            button.addEventListener('click', () => {
                const expanded = container.classList.toggle('monitor-projects-expanded');
                container.classList.toggle('monitor-projects-collapsed', !expanded);
                button.textContent = expanded ? 'Tampilkan lebih sedikit' : 'Tampilkan project lainnya';
            });
            wrap.appendChild(button); container.insertAdjacentElement('afterend', wrap);
        }
        const button = wrap.querySelector('button');
        if (button) button.textContent = container.classList.contains('monitor-projects-expanded') ? 'Tampilkan lebih sedikit' : 'Tampilkan project lainnya';
    };
    const applyCardData = () => {
        const container = document.getElementById('projects'); if (!container) return;
        container.querySelectorAll('.project').forEach((card,index) => {
            const id = Number(card.dataset.projectId || 0);
            const project = projects().find(item => Number(item.id) === id) || projects()[index]; if (!project) return;
            const orderDate = formatDate(project.created_at);
            const targetFinish = formatDate(project.target_completion_date);
            const days = remainingDays(project.target_completion_date);
            const chip = card.querySelector('.chip');
            if (chip) {
                const next = `DAYS|${days}`;
                if ((chip.dataset.cardValue || '') !== next) {
                    chip.innerHTML = `SISA HARI<strong>${days === '-' ? '-' : Math.max(0, days) + ' hari'}</strong>`;
                    chip.dataset.cardValue = next;
                    chip.setAttribute('aria-label', `Sisa ${days} hari menuju target selesai`);
                }
            }
            const projectId = card.querySelector('.project-id');
            if (projectId) {
                const next = `TARGET|${targetFinish}`;
                if ((projectId.dataset.cardValue || '') !== next) {
                    projectId.innerHTML = `TARGET FINISH<strong>${targetFinish}</strong>`;
                    projectId.dataset.cardValue = next;
                }
            }
            const footer = card.querySelector('.project-bottom');
            if (footer) {
                const columns = footer.children;
                if (columns[0]) { const label=columns[0].querySelector('.project-bottom-label'); const value=columns[0].querySelector('.project-bottom-value'); if(label) label.textContent='PT'; if(value) value.textContent=project.client || '-'; }
                if (columns[1]) { const label=columns[1].querySelector('.project-bottom-label'); const value=columns[1].querySelector('.project-bottom-value'); if(label) label.textContent='Tanggal Order'; if(value) value.textContent=orderDate; }
            }
        });
        syncMoreButton();
    };
    const init = () => {
        applyCardData();
        const container=document.getElementById('projects');
        if(container) new MutationObserver(applyCardData).observe(container,{childList:true});
        window.addEventListener('monitor:projects-updated',applyCardData);
    };
    if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',init,{once:true}); else init();
})();
</script>
