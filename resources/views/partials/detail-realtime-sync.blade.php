<script>
(() => {
    let progressAnimationFrame = null;

    const animateDetailProgress = target => {
        const progressText = document.getElementById('detailProgressText');
        const progressBar = document.getElementById('detailProgressBar');
        if (!progressText && !progressBar) return;

        const end = Math.max(0, Math.min(100, Number(target) || 0));
        const start = Math.max(0, Math.min(100, Number(
            progressText?.dataset.progressValue ??
            String(progressText?.textContent || '').replace(/[^0-9.-]/g, '')
        ) || 0));

        if (progressAnimationFrame) cancelAnimationFrame(progressAnimationFrame);

        // If there is no actual change, just keep the current state.
        if (start === end) {
            if (progressText) {
                progressText.textContent = end + '%';
                progressText.dataset.progressValue = String(end);
            }
            if (progressBar) progressBar.style.width = end + '%';
            return;
        }

        const duration = 850;
        const started = performance.now();
        const ease = t => 1 - Math.pow(1 - t, 3);

        // Start the bar from the currently displayed percentage, then smoothly
        // move it to the new percentage instead of jumping directly.
        if (progressBar) {
            progressBar.style.transition = 'none';
            progressBar.style.width = start + '%';
            void progressBar.offsetWidth;
            progressBar.style.transition = `width ${duration}ms cubic-bezier(.22,1,.36,1)`;
            progressBar.style.width = end + '%';
        }

        const step = now => {
            const t = Math.min(1, (now - started) / duration);
            const value = Math.round(start + (end - start) * ease(t));

            if (progressText) {
                progressText.textContent = value + '%';
                progressText.dataset.progressValue = String(value);
            }

            if (t < 1) {
                progressAnimationFrame = requestAnimationFrame(step);
            } else {
                progressAnimationFrame = null;
                if (progressText) {
                    progressText.textContent = end + '%';
                    progressText.dataset.progressValue = String(end);
                }
            }
        };

        progressAnimationFrame = requestAnimationFrame(step);
    };

    const syncDetailFields = (projects) => {
        const modal = document.getElementById('projectDetail');
        if (!modal?.classList.contains('open')) return;

        const id = Number(modal.dataset.projectId || 0);
        if (!id) return;

        const project = (projects || []).find(p => Number(p.id) === id);
        if (!project) return;

        const setText = (selector, value) => {
            const el = document.querySelector(selector);
            if (el) el.textContent = value ?? '-';
        };

        const setRich = (selector, value) => {
            const el = document.querySelector(selector);
            if (el) el.innerHTML = value || '-';
        };

        const typeLabels = {
            tazaka_order: 'Tazaka Order',
            subcontract: 'Subcontract',
            external: 'External',
        };

        const formatDate = (value) => {
            if (!value) return '-';
            const raw = String(value).slice(0, 10);
            const date = new Date(raw + 'T00:00:00');
            if (Number.isNaN(date.getTime())) return String(value);
            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric'
            }).format(date);
        };

        const formatMonth = (value) => {
            if (!value) return '-';
            const raw = String(value).slice(0, 7);
            const date = new Date(raw + '-01T00:00:00');
            if (Number.isNaN(date.getTime())) return String(value);
            return new Intl.DateTimeFormat('id-ID', {
                month: 'long', year: 'numeric'
            }).format(date);
        };

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

    const handleProjects = projects => syncDetailFields(Array.isArray(projects) ? projects : []);

    try {
        const current = Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];
        let projects = current;

        Object.defineProperty(window, '__monitorProjects', {
            configurable: true,
            get: () => projects,
            set: value => {
                projects = Array.isArray(value) ? value : [];
                window.dispatchEvent(new CustomEvent('monitor:projects-updated', {
                    detail: projects
                }));
            }
        });
    } catch (e) {
        console.warn('Realtime detail state hook gagal:', e);
    }

    window.addEventListener('monitor:projects-updated', event => {
        handleProjects(event.detail);
    });

    const originalFetch = window.fetch.bind(window);
    window.fetch = async (...args) => {
        const response = await originalFetch(...args);

        try {
            const requestUrl = typeof args[0] === 'string'
                ? args[0]
                : (args[0]?.url || '');

            if (requestUrl.includes('/monitor/data')) {
                response.clone().json()
                    .then(data => handleProjects(data?.projects || []))
                    .catch(() => {});
            }
        } catch (e) {
            console.warn('Realtime detail fetch sync gagal:', e);
        }

        return response;
    };

    handleProjects(window.__monitorProjects || []);
})();
</script>
