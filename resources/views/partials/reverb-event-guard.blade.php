<script>
(() => {
    if (window.__monitorRenderGuardInstalled) return;
    window.__monitorRenderGuardInstalled = true;

    let currentRender = null;
    let lastRenderSignature = '';
    let lastProjectUpdatedAt = 0;

    const signatureOf = projects => JSON.stringify((projects || []).map(p => ({
        id: p?.id,
        updated_at: p?.updated_at,
        progress: p?.progress,
        sort_order: p?.sort_order,
        images: p?.images
    })));

    const installRender = fn => {
        if (typeof fn !== 'function' || currentRender === fn) return;
        currentRender = fn;
        window.render = function (projects, summary) {
            const signature = signatureOf(projects);
            if (signature && signature === lastRenderSignature) return;
            lastRenderSignature = signature;
            return currentRender.apply(this, arguments);
        };
    };

    try {
        const descriptor = Object.getOwnPropertyDescriptor(window, 'render');
        if (!descriptor || descriptor.configurable !== false) {
            let storedRender = descriptor?.value ?? null;
            Object.defineProperty(window, 'render', {
                configurable: true,
                get() { return storedRender; },
                set(fn) {
                    storedRender = fn;
                    installRender(fn);
                }
            });
            if (storedRender) installRender(storedRender);
        }
    } catch (e) {
        console.warn('Monitor render guard tidak terpasang:', e);
    }

    if (typeof Pusher !== 'undefined' && Pusher.Channel) {
        const originalBind = Pusher.Channel.prototype.bind;
        if (!Pusher.Channel.prototype.__monitorProjectUpdatedGuard) {
            Pusher.Channel.prototype.__monitorProjectUpdatedGuard = true;
            Pusher.Channel.prototype.bind = function (eventName, callback, context) {
                if (eventName !== 'project.updated' || typeof callback !== 'function') {
                    return originalBind.call(this, eventName, callback, context);
                }

                const guardedCallback = function (...args) {
                    const now = Date.now();
                    if (now - lastProjectUpdatedAt < 500) return;
                    lastProjectUpdatedAt = now;
                    return callback.apply(this, args);
                };

                return originalBind.call(this, eventName, guardedCallback, context);
            };
        }
    }
})();
</script>
