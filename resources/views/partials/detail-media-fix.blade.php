<script>
(() => {
    const syncProjectId = (projectId) => {
        const modal = document.getElementById('projectDetail');
        if (!modal || !projectId) return;
        modal.dataset.projectId = String(projectId);
    };

    document.addEventListener('click', event => {
        const card = event.target.closest('.project[data-project-id]');
        if (!card) return;
        syncProjectId(card.dataset.projectId);
    });

    document.addEventListener('keydown', event => {
        if (event.key !== 'Enter' && event.key !== ' ') return;
        const card = document.activeElement?.closest?.('.project[data-project-id]');
        if (!card) return;
        syncProjectId(card.dataset.projectId);
    });
})();
</script>
