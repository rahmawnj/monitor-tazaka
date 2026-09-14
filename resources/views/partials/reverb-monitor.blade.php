<style>
    .header { position: sticky; top: 0; z-index: 900; padding: 14px 0; margin-bottom: 24px; background: rgba(7, 11, 20, .88); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); }
    .project.water-update { animation: waterCardFloat .75s cubic-bezier(.22, .8, .25, 1); }
    .project.water-update::before { animation: waterDropRipple 1.05s cubic-bezier(.16, .8, .3, 1); }
    .project.water-update .bar { box-shadow: 0 0 16px rgba(255,255,255,.45); }
    .detail-bar span.realtime-update { box-shadow: 0 0 18px rgba(255,255,255,.7); }
    @keyframes waterCardFloat { 0% { transform: translateY(0) scale(1); } 18% { transform: translateY(-3px) scale(1.008); } 55% { transform: translateY(1px) scale(.998); } 100% { transform: translateY(0) scale(1); } }
    @keyframes waterDropRipple { 0% { transform: scale(.72); opacity: .05; } 20% { opacity: .34; } 100% { transform: scale(1.65); opacity: 0; } }
    @media (prefers-reduced-motion: reduce) { .project.water-update, .project.water-update::before { animation: none !important; } }
</style>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
<script>
(() => {
    const previousProgress = new Map();

    const progressGradient = value => {
        const p = Math.max(0, Math.min(100, Number(value) || 0));
        if (p >= 100) return 'linear-gradient(90deg,#10b981,#34d399)';
        if (p >= 80) return 'linear-gradient(90deg,#22c55e,#84cc16)';
        if (p >= 60) return 'linear-gradient(90deg,#eab308,#facc15)';
        if (p >= 30) return 'linear-gradient(90deg,#f97316,#fbbf24)';
        return 'linear-gradient(90deg,#ef4444,#fb7185)';
    };

    const applyProgressColors = projects => {
        projects.forEach(project => {
            const card = document.querySelector(`.project[data-project-id="${Number(project.id)}"]`);
            const bar = card?.querySelector('.bar');
            if (bar) bar.style.background = progressGradient(project.progress);
        });
    };

    document.querySelectorAll('.project[data-project-id]').forEach(card => {
        const id = Number(card.dataset.projectId);
        const text = card.querySelector('.progress-label strong')?.textContent || '';
        const value = Number.parseInt(text.replace(/[^0-9-]/g, ''), 10);
        if (Number.isFinite(id) && Number.isFinite(value)) previousProgress.set(id, value);
    });

    const updateDetailModal = (project, oldProgress, newProgress) => {
        const backdrop = document.getElementById('projectDetail');
        if (!backdrop?.classList.contains('open')) return;
        if (Number(backdrop.dataset.projectId) !== Number(project.id)) return;

        const text = document.getElementById('detailProgressText');
        const bar = document.getElementById('detailProgressBar');
        const from = Math.max(0, Math.min(100, Number(oldProgress) || 0));
        const to = Math.max(0, Math.min(100, Number(newProgress) || 0));
        if (!bar || !text) return;

        bar.classList.add('realtime-update');
        bar.style.transition = 'none';
        bar.style.width = `${from}%`;
        bar.style.background = progressGradient(to);
        text.textContent = `${from}%`;
        void bar.offsetWidth;

        requestAnimationFrame(() => {
            bar.style.transition = 'width 1.5s cubic-bezier(.16,1,.3,1), box-shadow .8s ease';
            bar.style.width = `${to}%`;
            const start = performance.now();
            const duration = 1500;
            const tick = now => {
                const progress = Math.min(1, (now - start) / duration);
                const eased = 1 - Math.pow(1 - progress, 3);
                text.textContent = `${Math.round(from + (to - from) * eased)}%`;
                if (progress < 1) requestAnimationFrame(tick);
                else text.textContent = `${to}%`;
            };
            requestAnimationFrame(tick);
        });

        window.setTimeout(() => {
            bar.classList.remove('realtime-update');
            bar.style.transition = 'width .4s ease';
            bar.style.background = progressGradient(to);
        }, 1700);
    };

    const animateProgressBar = (projectId, oldProgress, newProgress) => {
        const card = document.querySelector(`.project[data-project-id="${Number(projectId)}"]`);
        if (!card || oldProgress === undefined || oldProgress === newProgress) return;
        const bar = card.querySelector('.bar');
        const label = card.querySelector('.progress-label strong');
        if (!bar) return;

        const from = Math.max(0, Math.min(100, Number(oldProgress) || 0));
        const to = Math.max(0, Math.min(100, Number(newProgress) || 0));
        bar.style.transition = 'none';
        bar.style.width = `${from}%`;
        bar.style.background = progressGradient(to);
        if (label) label.textContent = `${from}%`;
        void bar.offsetWidth;

        requestAnimationFrame(() => {
            bar.style.transition = 'width 1.5s cubic-bezier(.16,1,.3,1), box-shadow .8s ease';
            bar.style.boxShadow = '0 0 16px rgba(255,255,255,.65)';
            bar.style.width = `${to}%`;
            if (label) {
                const start = performance.now();
                const duration = 1500;
                const tick = now => {
                    const progress = Math.min(1, (now - start) / duration);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    label.textContent = `${Math.round(from + (to - from) * eased)}%`;
                    if (progress < 1) requestAnimationFrame(tick);
                    else label.textContent = `${to}%`;
                };
                requestAnimationFrame(tick);
            }
        });

        window.setTimeout(() => {
            bar.style.boxShadow = '';
            bar.style.transition = 'width .4s ease';
            bar.style.background = progressGradient(to);
        }, 1700);
    };

    const triggerWaterDrop = projectId => {
        const card = document.querySelector(`.project[data-project-id="${Number(projectId)}"]`);
        if (!card) return;
        card.classList.remove('water-update');
        void card.offsetWidth;
        card.classList.add('water-update');
        window.setTimeout(() => card.classList.remove('water-update'), 1200);
    };

    const refreshMonitor = async (event = {}) => {
        try {
            const response = await fetch(@json(route('monitor.data')), { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
            if (!response.ok) return;
            const data = await response.json();
            if (!Array.isArray(data.projects)) return;

            const changedId = Number(event.project_id || 0);
            const changedProject = data.projects.find(p => Number(p.id) === changedId);
            const oldProgress = previousProgress.get(changedId);
            const newProgress = changedProject ? Number(changedProject.progress || 0) : undefined;
            const progressChanged = changedProject && oldProgress !== undefined && oldProgress !== newProgress;

            render(data.projects, data.summary);
            renderMap(data.projects);
            applyProgressColors(data.projects);
            data.projects.forEach(p => previousProgress.set(Number(p.id), Number(p.progress || 0)));

            if (changedProject && progressChanged) {
                updateDetailModal(changedProject, oldProgress, newProgress);
                requestAnimationFrame(() => {
                    animateProgressBar(changedId, oldProgress, newProgress);
                    triggerWaterDrop(changedId);
                });
            }
        } catch (error) {
            console.error('Gagal memperbarui monitor realtime:', error);
        }
    };

    applyProgressColors(@json($projects));

    const pusher = new Pusher(@json(env('REVERB_APP_KEY')), {
        cluster: 'mt1',
        wsHost: @json(env('REVERB_HOST', '127.0.0.1')),
        wsPort: Number(@json(env('REVERB_PORT', 8085))),
        wssPort: Number(@json(env('REVERB_PORT', 8085))),
        forceTLS: @json(env('REVERB_SCHEME', 'http')) === 'https',
        enabledTransports: ['ws', 'wss'],
        disableStats: true
    });

    const channel = pusher.subscribe('monitor');
    channel.bind('project.updated', refreshMonitor);
    pusher.connection.bind('connected', () => console.info('Monitor realtime terhubung ke Reverb.'));
    pusher.connection.bind('error', error => console.error('Koneksi Reverb gagal:', error));
})();
</script>
