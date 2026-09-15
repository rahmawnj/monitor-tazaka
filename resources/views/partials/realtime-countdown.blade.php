<style>
    .project-countdown { display:block; margin-top:3px; font-size:10px; font-weight:800; color:#38bdf8; }
    .project-countdown.is-today { color:#f59e0b; }
    .project-countdown.is-late { color:#fb7185; }
    .detail-countdown { display:block; margin-top:4px; font-size:11px; font-weight:800; color:#38bdf8; }
    .detail-countdown.is-today { color:#f59e0b; }
    .detail-countdown.is-late { color:#fb7185; }
</style>
<script>
(function () {
    function sourceProjects() {
        return typeof initialProjects !== 'undefined' && Array.isArray(initialProjects) ? initialProjects : [];
    }

    function jakartaDateParts() {
        const parts = new Intl.DateTimeFormat('en-CA', {
            timeZone: 'Asia/Jakarta', year: 'numeric', month: '2-digit', day: '2-digit'
        }).formatToParts(new Date());
        const values = Object.fromEntries(parts.map(p => [p.type, p.value]));
        return { year:Number(values.year), month:Number(values.month), day:Number(values.day) };
    }

    function targetParts(value) {
        if (!value) return null;
        const match = String(value).slice(0, 10).match(/^(\d{4})-(\d{2})-(\d{2})$/);
        if (!match) return null;
        return { year:Number(match[1]), month:Number(match[2]), day:Number(match[3]) };
    }

    function dayNumber(parts) {
        return Date.UTC(parts.year, parts.month - 1, parts.day) / 86400000;
    }

    function countdownText(value) {
        const target = targetParts(value);
        if (!target) return { text:'-', className:'' };
        const diff = dayNumber(target) - dayNumber(jakartaDateParts());
        if (diff > 1) return { text:`${diff} hari lagi`, className:'' };
        if (diff === 1) return { text:'Besok', className:'' };
        if (diff === 0) return { text:'Hari ini', className:'is-today' };
        return { text:`Terlambat ${Math.abs(diff)} hari`, className:'is-late' };
    }

    function updateCountdowns() {
        const projects = sourceProjects();
        document.querySelectorAll('.project[data-project-id]').forEach(card => {
            const project = projects.find(p => String(p.id ?? '') === String(card.dataset.projectId));
            if (!project?.target_completion_date) return;
            let el = card.querySelector('.project-countdown');
            if (!el) {
                const targetValue = card.querySelector('.project-bottom > div:last-child .project-bottom-value');
                if (!targetValue) return;
                el = document.createElement('span');
                targetValue.appendChild(el);
            }
            const result = countdownText(project.target_completion_date);
            el.className = `project-countdown ${result.className}`.trim();
            el.textContent = result.text;
        });

        const target = document.getElementById('detailTarget');
        const title = document.getElementById('detailTitle')?.textContent?.trim();
        const project = projects.find(p => String(p.name ?? '').trim() === title);
        if (!target || !project?.target_completion_date) return;

        let el = target.querySelector('.detail-countdown');
        if (!el) {
            el = document.createElement('span');
            target.appendChild(el);
        }
        const result = countdownText(project.target_completion_date);
        el.className = `detail-countdown ${result.className}`.trim();
        el.textContent = result.text;
    }

    window.updateRealtimeCountdowns = updateCountdowns;
    function start() {
        updateCountdowns();
        setInterval(updateCountdowns, 30000);
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start, { once:true });
    else start();
})();
</script>
