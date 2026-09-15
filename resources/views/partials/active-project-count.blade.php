<style>
    .active-project-count{float:right;display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:24px;padding:0 8px;border:1px solid rgba(56,189,248,.28);border-radius:999px;background:rgba(56,189,248,.08);color:#7dd3fc;font-size:11px;font-weight:800;line-height:1}
</style>
<script>
(() => {
    const syncActiveProjectCount = () => {
        const projects = document.getElementById('projects');
        if (!projects) return;
        const heading = [...document.querySelectorAll('.project-card h2')].find(el => el.textContent.trim().startsWith('Active Projects'));
        if (!heading) return;
        let count = heading.querySelector('.active-project-count');
        if (!count) {
            count = document.createElement('span');
            count.className = 'active-project-count';
            heading.appendChild(count);
        }
        count.textContent = projects.querySelectorAll('.project').length;
    };

    const init = () => {
        syncActiveProjectCount();
        const projects = document.getElementById('projects');
        if (projects && !projects.__activeProjectCountObserver) {
            projects.__activeProjectCountObserver = new MutationObserver(syncActiveProjectCount);
            projects.__activeProjectCountObserver.observe(projects, {childList:true});
        }
    };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init, {once:true});
    else init();
})();
</script>
