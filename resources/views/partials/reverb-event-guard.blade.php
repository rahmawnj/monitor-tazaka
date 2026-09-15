<script>
(() => {
    if (window.__monitorRenderGuardInstalled) return;
    window.__monitorRenderGuardInstalled = true;

    let lastRenderSignature = '';
    let lastProjectUpdatedAt = 0;

    const signatureOf = projects => JSON.stringify((projects || []).map(p => ({
        id: p?.id,
        updated_at: p?.updated_at,
        progress: p?.progress,
        sort_order: p?.sort_order,
        images: p?.images
    })));

    const wrapRender = fn => {
        if (typeof fn !== 'function') return fn;
        const wrapped = function (projects, summary) {
            const signature = signatureOf(projects);
            if (signature && signature === lastRenderSignature) return;
            lastRenderSignature = signature;
            return fn.apply(this, arguments);
        };
        return wrapped;
    };

    try {
        const descriptor = Object.getOwnPropertyDescriptor(window, 'render');
        if (!descriptor || descriptor.configurable !== false) {
            let initialValue = descriptor?.value ?? null;
            Object.defineProperty(window, 'render', {
                configurable: true,
                get() { return initialValue; },
                set(fn) {
                    if (typeof fn !== 'function') {
                        initialValue = fn;
                        return;
                    }
                    const wrapped = wrapRender(fn);
                    initialValue = wrapped;
                    Object.defineProperty(window, 'render', {
                        configurable: true,
                        writable: true,
                        value: wrapped
                    });
                }
            });
        }
    } catch (e) {
        console.warn('Monitor render guard tidak terpasang:', e);
    }

    if (typeof Pusher !== 'undefined' && Pusher.Channel) {
        const prototype = Pusher.Channel.prototype;
        if (!prototype.__monitorProjectUpdatedGuard) {
            const originalBind = prototype.bind;
            prototype.__monitorProjectUpdatedGuard = true;
            prototype.bind = function (eventName, callback, context) {
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
