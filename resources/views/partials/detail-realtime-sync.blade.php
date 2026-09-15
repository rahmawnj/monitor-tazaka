<script>
(() => {
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
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            }).format(date);
        };

        const formatMonth = (value) => {
            if (!value) return '-';
            const raw = String(value).slice(0, 7);
            const date = new Date(raw + '-01T00:00:00');
            if (Number.isNaN(date.getTime())) return String(value);
            return new Intl.DateTimeFormat('id-ID', {
                month: 'long',
                year: 'numeric',
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

        const progress = Math.max(0, Math.min(100, Number(project.progress) || 0));
        const progressText = document.getElementById('detailProgressText');
        const progressBar = document.getElementById('detailProgressBar');

        if (progressText) {
            progressText.textContent = progress + '%';
            progressText.dataset.progressValue = String(progress);
        }
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
    };

    const handleProjects = (projects) => {
        const normalized = Array.isArray(projects) ? projects : [];

        // Update immediately when the Reverb refresh replaces the shared project list.
        syncDetailFields(normalized);
    };

    // Keep the existing shared-state mechanism, but make the modal sync independent
    // from the order in which the other realtime scripts are initialized.
    try {
        const current = Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];
        let projects = current;

        Object.defineProperty(window, '__monitorProjects', {
            configurable: true,
            get: () => projects,
            set: (value) => {
                projects = Array.isArray(value) ? value : [];
                window.dispatchEvent(new CustomEvent('monitor:projects-updated', {
                    detail: projects,
                }));
            },
        });
    } catch (e) {
        console.warn('Realtime detail state hook gagal:', e);
    }

    window.addEventListener('monitor:projects-updated', event => {
        handleProjects(event.detail);
    });

    // The Reverb script refreshes /monitor/data when project.updated arrives.
    // Hook the existing fetch instead of starting another polling loop/request.
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

    // Sync once immediately in case the modal is already open when this partial loads.
    handleProjects(window.__monitorProjects || []);
})();
</script>
