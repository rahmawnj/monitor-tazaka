<style>
    .header {
        position: sticky;
        top: 0;
        z-index: 900;
        padding: 14px 0;
        margin-bottom: 24px;
        background: rgba(7, 11, 20, .88);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .project.water-update {
        animation: waterCardFloat .75s cubic-bezier(.22, .8, .25, 1);
    }

    .project.water-update::before {
        animation: waterDropRipple 1.05s cubic-bezier(.16, .8, .3, 1);
    }

    .project.water-update .bar {
        animation: waterProgressShimmer .9s ease-out;
    }

    @keyframes waterCardFloat {
        0% { transform: translateY(0) scale(1); }
        18% { transform: translateY(-3px) scale(1.008); }
        55% { transform: translateY(1px) scale(.998); }
        100% { transform: translateY(0) scale(1); }
    }

    @keyframes waterDropRipple {
        0% { transform: scale(.72); opacity: .05; }
        20% { opacity: .34; }
        100% { transform: scale(1.65); opacity: 0; }
    }

    @keyframes waterProgressShimmer {
        0% { filter: brightness(1); }
        35% { filter: brightness(1.8); }
        100% { filter: brightness(1); }
    }

    @media (prefers-reduced-motion: reduce) {
        .project.water-update,
        .project.water-update::before,
        .project.water-update .bar { animation: none !important; }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
<script>
(() => {
    let previousProgress = new Map();

    const triggerWaterDrop = (projectId) => {
        if (!projectId) return;
        const card = document.querySelector(`.project[data-project-id="${Number(projectId)}"]`);
        if (!card) return;

        card.classList.remove('water-update');
        void card.offsetWidth;
        card.classList.add('water-update');
        window.setTimeout(() => card.classList.remove('water-update'), 1200);
    };

    const refreshMonitor = async (event = {}, establishBaseline = false) => {
        try {
            const response = await fetch(@json(route('monitor.data')), {
                headers: { 'Accept': 'application/json' },
                cache: 'no-store',
            });

            if (!response.ok) return;

            const data = await response.json();
            if (!Array.isArray(data.projects)) return;

            const changedId = Number(event.project_id || 0);
            const changedProject = data.projects.find(p => Number(p.id) === changedId);
            const oldProgress = changedId ? previousProgress.get(changedId) : undefined;
            const newProgress = changedProject ? Number(changedProject.progress || 0) : undefined;
            const progressChanged = !establishBaseline && changedProject && oldProgress !== undefined && oldProgress !== newProgress;

            render(data.projects, data.summary);
            renderMap(data.projects);

            previousProgress = new Map(
                data.projects.map(p => [Number(p.id), Number(p.progress || 0)])
            );

            if (progressChanged) {
                requestAnimationFrame(() => triggerWaterDrop(changedId));
            }
        } catch (error) {
            console.error('Gagal memperbarui monitor realtime:', error);
        }
    };

    const pusher = new Pusher(@json(env('REVERB_APP_KEY')), {
        cluster: 'mt1',
        wsHost: @json(env('REVERB_HOST', '127.0.0.1')),
        wsPort: Number(@json(env('REVERB_PORT', 8085))),
        wssPort: Number(@json(env('REVERB_PORT', 8085))),
        forceTLS: @json(env('REVERB_SCHEME', 'http')) === 'https',
        enabledTransports: ['ws', 'wss'],
        disableStats: true,
    });

    const channel = pusher.subscribe('monitor');
    channel.bind('project.updated', refreshMonitor);

    pusher.connection.bind('connected', () => {
        console.info('Monitor realtime terhubung ke Reverb.');
    });

    pusher.connection.bind('error', (error) => {
        console.error('Koneksi Reverb gagal:', error);
    });

    // Ambil baseline progress dari API tanpa memicu animasi.
    refreshMonitor({}, true);
})();
</script>
