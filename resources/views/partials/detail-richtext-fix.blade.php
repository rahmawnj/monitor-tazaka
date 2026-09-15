<script>
(() => {
    const allowedTags = new Set(['P','BR','STRONG','B','EM','I','U','UL','OL','LI','H1','H2','H3','H4','H5','H6','BLOCKQUOTE','A']);

    const decodeRichText = value => {
        let html = String(value ?? '');
        for (let i = 0; i < 3; i++) {
            if (/<\/?[a-z][\s\S]*>/i.test(html)) break;
            const textarea = document.createElement('textarea');
            textarea.innerHTML = html;
            const decoded = textarea.value;
            if (decoded === html) break;
            html = decoded;
        }
        return html;
    };

    const sanitizeRichText = value => {
        const source = decodeRichText(value);
        const template = document.createElement('template');
        template.innerHTML = source;

        const clean = node => {
            [...node.childNodes].forEach(child => {
                if (child.nodeType === Node.COMMENT_NODE) {
                    child.remove();
                    return;
                }
                if (child.nodeType !== Node.ELEMENT_NODE) return;

                if (!allowedTags.has(child.tagName)) {
                    const fragment = document.createDocumentFragment();
                    while (child.firstChild) fragment.appendChild(child.firstChild);
                    child.replaceWith(fragment);
                    clean(node);
                    return;
                }

                [...child.attributes].forEach(attribute => {
                    const name = attribute.name.toLowerCase();
                    const value = attribute.value.trim();
                    if (name.startsWith('on') || name === 'style' || name === 'id' || name === 'class') {
                        child.removeAttribute(attribute.name);
                    }
                    if (child.tagName === 'A' && name === 'href') {
                        if (!/^(https?:|mailto:|tel:|#)/i.test(value)) child.removeAttribute('href');
                    }
                    if (child.tagName === 'A' && name === 'target' && !['_blank','_self'].includes(value)) {
                        child.removeAttribute('target');
                    }
                });

                if (child.tagName === 'A' && child.getAttribute('target') === '_blank') {
                    child.setAttribute('rel', 'noopener noreferrer');
                }
                clean(child);
            });
        };

        clean(template.content);
        return template.innerHTML || '<p>Tidak ada informasi.</p>';
    };

    const renderRichText = (selector, value, fallback) => {
        const element = document.querySelector(selector);
        if (!element) return;
        const html = String(value ?? '').trim();
        element.innerHTML = html ? sanitizeRichText(html) : `<p>${fallback}</p>`;
    };

    const syncOpenedDetailRichText = project => {
        if (!project) return;
        const modal = document.getElementById('projectDetail');
        if (!modal?.classList.contains('open')) return;
        renderRichText('#detailDescription', project.description, 'Tidak ada deskripsi.');
        renderRichText('#detailNotes', project.notes, 'Tidak ada catatan.');
    };

    document.addEventListener('click', event => {
        const card = event.target.closest('.project[data-project-id]');
        if (!card) return;
        const projects = Array.isArray(window.__monitorProjects) ? window.__monitorProjects : [];
        const project = projects.find(item => String(item.id) === String(card.dataset.projectId));
        if (!project) return;
        requestAnimationFrame(() => syncOpenedDetailRichText(project));
    }, true);

    window.addEventListener('monitor:projects-updated', event => {
        const projects = event.detail?.projects || [];
        const modal = document.getElementById('projectDetail');
        const id = Number(modal?.dataset.projectId || 0);
        if (!id) return;
        const project = projects.find(item => Number(item.id) === id);
        if (project) syncOpenedDetailRichText(project);
    });
})();
</script>
