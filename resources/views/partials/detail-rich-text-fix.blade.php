<script>
(() => {
    const allowedTags = new Set(['P','BR','STRONG','B','EM','I','U','UL','OL','LI','BLOCKQUOTE']);

    const decode = value => {
        const textarea = document.createElement('textarea');
        textarea.innerHTML = String(value ?? '');
        return textarea.value;
    };

    const sanitizeRichHtml = value => {
        const source = decode(value);
        const template = document.createElement('template');
        template.innerHTML = source;

        const cleanNode = node => {
            if (node.nodeType === Node.TEXT_NODE) return document.createTextNode(node.nodeValue || '');
            if (node.nodeType !== Node.ELEMENT_NODE) return document.createDocumentFragment();

            if (!allowedTags.has(node.tagName)) {
                const fragment = document.createDocumentFragment();
                [...node.childNodes].forEach(child => fragment.appendChild(cleanNode(child)));
                return fragment;
            }

            const clean = document.createElement(node.tagName.toLowerCase());
            [...node.childNodes].forEach(child => clean.appendChild(cleanNode(child)));
            return clean;
        };

        const fragment = document.createDocumentFragment();
        [...template.content.childNodes].forEach(node => fragment.appendChild(cleanNode(node)));
        return fragment;
    };

    const fixField = id => {
        const el = document.getElementById(id);
        if (!el) return;

        const raw = el.textContent || '';
        if (!/<\/?[a-z][^>]*>/i.test(raw) && !/<\/?[a-z][^>]*>/i.test(el.innerHTML)) return;

        const fragment = sanitizeRichHtml(raw || el.innerHTML);
        el.replaceChildren(fragment);
    };

    const fixRichText = () => {
        fixField('detailDescription');
        fixField('detailNotes');
    };

    document.addEventListener('click', event => {
        if (event.target.closest('.project') || event.target.closest('#detailClose')) {
            requestAnimationFrame(() => requestAnimationFrame(fixRichText));
        }
    }, true);

    const modal = document.getElementById('projectDetail');
    if (modal) {
        new MutationObserver(fixRichText).observe(modal, {
            subtree: true,
            childList: true,
            characterData: true,
        });
    }

    window.addEventListener('monitor:projects-updated', () => {
        requestAnimationFrame(fixRichText);
    });

    requestAnimationFrame(fixRichText);
})();
</script>
