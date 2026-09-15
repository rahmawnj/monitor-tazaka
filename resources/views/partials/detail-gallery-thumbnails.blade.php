<style>
    .detail-gallery-thumbs{display:flex;gap:8px;margin-top:9px;overflow-x:auto;padding:2px 1px 4px;scrollbar-width:thin}
    .detail-gallery-thumb{flex:0 0 58px;width:58px;height:44px;padding:0;border:1px solid #334155;border-radius:8px;overflow:hidden;background:#0b1220;cursor:pointer;opacity:.72;transition:opacity .2s ease,transform .2s ease,border-color .2s ease}
    .detail-gallery-thumb:hover{opacity:1;transform:translateY(-1px)}
    .detail-gallery-thumb.active{opacity:1;border-color:#60a5fa;box-shadow:0 0 0 2px rgba(96,165,250,.16)}
    .detail-gallery-thumb img{width:100%;height:100%;display:block;object-fit:cover}
    @media(max-width:700px){.detail-gallery-thumb{flex-basis:54px;width:54px;height:42px}.detail-gallery-thumbs{gap:7px}}
</style>
<script>
(() => {
    let observer = null;
    let lastSignature = '';

    const getProject = () => {
        const modal = document.getElementById('projectDetail');
        const id = Number(modal?.dataset.projectId || 0);
        return (window.__monitorProjects || []).find(p => Number(p.id) === id) || null;
    };

    const imageUrl = image => {
        if (!image) return '';
        if (image.url) return String(image.url);
        if (!image.path) return '';
        return '/storage/' + String(image.path).replace(/^\/+/, '').split('/').map(encodeURIComponent).join('/');
    };

    const renderThumbs = () => {
        const gallery = document.getElementById('detailGallery');
        if (!gallery) return;
        const project = getProject();
        const images = Array.isArray(project?.images)
            ? project.images.slice().sort((a,b) => Number(a.sort_order || 0) - Number(b.sort_order || 0))
            : [];
        const signature = `${project?.id || 0}:${images.map(i => i.id || i.path || i.url).join('|')}`;
        if (signature === lastSignature && gallery.nextElementSibling?.classList.contains('detail-gallery-thumbs')) return;
        lastSignature = signature;

        gallery.nextElementSibling?.classList.contains('detail-gallery-thumbs') && gallery.nextElementSibling.remove();
        if (!images.length) return;

        const thumbs = document.createElement('div');
        thumbs.className = 'detail-gallery-thumbs';
        thumbs.setAttribute('aria-label', 'Preview project images');
        thumbs.innerHTML = images.map((image, index) => `<button type="button" class="detail-gallery-thumb${index === 0 ? ' active' : ''}" data-thumb-index="${index}" aria-label="Preview image ${index + 1}"><img src="${imageUrl(image)}" alt="Preview ${index + 1}" loading="lazy"></button>`).join('');
        gallery.insertAdjacentElement('afterend', thumbs);

        thumbs.querySelectorAll('.detail-gallery-thumb').forEach(button => {
            button.addEventListener('click', () => {
                const index = Number(button.dataset.thumbIndex || 0);
                const image = images[index];
                const main = gallery.querySelector('img');
                if (main) main.src = imageUrl(image);
                const count = gallery.querySelector('.detail-gallery-count');
                if (count) count.textContent = `${index + 1} / ${images.length}`;
                thumbs.querySelectorAll('.detail-gallery-thumb').forEach(t => t.classList.remove('active'));
                button.classList.add('active');
            });
        });
    };

    const watch = () => {
        const gallery = document.getElementById('detailGallery');
        if (!gallery) return false;
        if (observer) observer.disconnect();
        observer = new MutationObserver(() => requestAnimationFrame(renderThumbs));
        observer.observe(gallery, { childList: true, subtree: true });
        renderThumbs();
        return true;
    };

    const boot = () => {
        if (watch()) return;
        setTimeout(boot, 150);
    };

    new MutationObserver(() => setTimeout(boot, 20)).observe(document.body, { childList: true, subtree: true });
    document.addEventListener('DOMContentLoaded', boot);
    setTimeout(boot, 100);
})();
</script>
