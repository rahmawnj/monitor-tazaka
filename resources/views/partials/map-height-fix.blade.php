<style>
    /* Batasi tinggi map di daftar/list monitor agar tidak ikut memanjang. */
    .map-card .map-wrap {
        height: 360px;
        min-height: 360px;
        max-height: 360px;
    }

    .map-card .map {
        height: 100%;
        max-height: 360px;
    }

    /* Rapikan kartu project di halaman monitor (/). */
    .project .project-label,
    .project .client,
    .project .project-bottom {
        display: none !important;
    }

    .project .chip {
        width: 92px;
        height: 42px;
        margin-top: 16px;
        padding: 7px 10px;
        border-radius: 9px;
        background: rgba(15,23,42,.78);
        border: 1px solid rgba(56,189,248,.22);
        box-shadow: none;
        color: #94a3b8;
        font-size: 8px;
        line-height: 1.25;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .project .chip::before,
    .project .chip::after {
        display: none;
    }

    .project .chip strong {
        display: block;
        margin-top: 2px;
        color: #e2e8f0;
        font-size: 10px;
        letter-spacing: .02em;
        text-transform: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .project .project-id {
        text-align: right;
    }

    .project .project-id strong {
        display: block;
        margin-top: 3px;
        color: #e2e8f0;
        font-size: 11px;
        letter-spacing: 0;
        text-transform: none;
    }
</style>

<script>
(() => {
    const formatMonth = value => {
        if (!value) return '-';
        const raw = String(value).slice(0, 7);
        const date = new Date(raw + '-01T00:00:00');
        if (Number.isNaN(date.getTime())) return String(value);
        return new Intl.DateTimeFormat('id-ID', { month: 'short', year: 'numeric' }).format(date);
    };

    const formatDate = value => {
        if (!value) return '-';
        const raw = String(value).slice(0, 10);
        const date = new Date(raw + 'T00:00:00');
        if (Number.isNaN(date.getTime())) return String(value);
        return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).format(date);
    };

    const projects = () => Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];

    const applyCardData = () => {
        const cards = document.querySelectorAll('#projects .project');
        cards.forEach((card, index) => {
            const id = Number(card.dataset.projectId || 0);
            const project = projects().find(item => Number(item.id) === id) || projects()[index];
            if (!project) return;

            const month = formatMonth(project.project_month);
            const orderDate = formatDate(project.created_at);

            const chip = card.querySelector('.chip');
            if (chip) {
                const current = chip.dataset.cardValue || '';
                const next = `MONTH|${month}`;
                if (current !== next) {
                    chip.innerHTML = `MONTH<strong>${month}</strong>`;
                    chip.dataset.cardValue = next;
                    chip.setAttribute('aria-label', `Project month ${month}`);
                }
            }

            const projectId = card.querySelector('.project-id');
            if (projectId) {
                const current = projectId.dataset.cardValue || '';
                const next = `ORDER|${orderDate}`;
                if (current !== next) {
                    projectId.innerHTML = `ORDER<strong>${orderDate}</strong>`;
                    projectId.dataset.cardValue = next;
                }
            }
        });
    };

    const init = () => {
        applyCardData();

        const container = document.getElementById('projects');
        if (container) {
            // Hanya pantau card baru dari render realtime, bukan perubahan subtree.
            new MutationObserver(applyCardData).observe(container, { childList: true });
        }

        window.addEventListener('monitor:projects-updated', applyCardData);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
</script>
