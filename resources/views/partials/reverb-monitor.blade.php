<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
<script>
(() => {
    const refreshMonitor = async () => {
        try {
            const response = await fetch(@json(route('monitor.data')), {
                headers: { 'Accept': 'application/json' },
                cache: 'no-store',
            });

            if (!response.ok) return;

            const data = await response.json();
            if (!Array.isArray(data.projects)) return;

            render(data.projects, data.summary);
            renderMap(data.projects);
        } catch (error) {
            console.error('Gagal memperbarui monitor realtime:', error);
        }
    };

    const pusher = new Pusher(@json(env('REVERB_APP_KEY')), {
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
})();
</script>
