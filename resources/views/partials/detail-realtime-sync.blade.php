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
            return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }).format(date);
        };

        const formatMonth = (value) => {
            if (!value) return '-';
            const raw = String(value).slice(0, 7);
            const date = new Date(raw + '-01T00:00:00');
            if (Number.isNaN(date.getTime())) return String(value);
            return new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' }).format(date);
        };

        setText('#detailTitle', project.name || '-');
        setText('#detailClient', project.client || '-');
        setText('#detailType', typeLabels[project.project_type] || project.project_type || '-');
        setText('#detailMonth', formatMonth(project.project_month));
        setText('#detailTarget', formatDate(project.target_completion_date));
        setText('#detailLocation', project.location || '-');
        setText('#detailDescription', project.description || '-');
        setText('#detailNotes', project.notes || '-');
    };

    const install = () => {
        const current = window.__monitorProjects || [];
        let projects = current;

        try {
            Object.defineProperty(window, '__monitorProjects', {
                configurable: true,
                get: () => projects,
                set: (value) => {
                    projects = Array.isArray(value) ? value : [];
                    window.dispatchEvent(new CustomEvent('monitor:projects-updated', { detail: projects }));
                },
            });
        } catch (e) {
            console.warn('Realtime detail sync tidak dapat dipasang:', e);
            return;
        }

        window.addEventListener('monitor:projects-updated', event => {
            syncDetailFields(event.detail);
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', install, { once: true });
    } else {
        install();
    }
})();
</script>
