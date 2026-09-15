<script>
(() => {
    if (window.__monitorReverbEventGuardInstalled) return;
    window.__monitorReverbEventGuardInstalled = true;

    if (typeof Pusher === 'undefined' || !Pusher.Channel) return;

    const originalBind = Pusher.Channel.prototype.bind;
    let lastProjectUpdatedAt = 0;

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
})();
</script>
