<script>
(() => {
    const COMPANY_NAME = 'Tazaka Elektrik Teknologi';
    const WRONG_NAMES = new Set(['Tazaka Elektrik Mandiri']);

    const replaceCompanyName = root => {
        const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
        const nodes = [];
        let node;

        while ((node = walker.nextNode())) {
            if (WRONG_NAMES.has(node.nodeValue.trim())) nodes.push(node);
        }

        nodes.forEach(textNode => {
            textNode.nodeValue = textNode.nodeValue.replace(/Tazaka Elektrik Mandiri/g, COMPANY_NAME);
        });
    };

    const init = () => {
        replaceCompanyName(document.body);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
</script>
