<style>
    .idle-project-fab{
        position:fixed;
        right:24px;
        bottom:24px;
        z-index:1100;
        display:inline-flex;
        align-items:center;
        gap:9px;
        padding:12px 16px;
        border:1px solid rgba(125,211,252,.3);
        border-radius:999px;
        background:rgba(7,11,20,.82);
        color:#e2e8f0;
        box-shadow:0 14px 35px rgba(0,0,0,.35);
        backdrop-filter:blur(14px);
        -webkit-backdrop-filter:blur(14px);
        font:700 11px/1 Inter,ui-sans-serif,system-ui,sans-serif;
        letter-spacing:.04em;
        cursor:pointer;
        text-decoration:none;
        transition:transform .2s ease,background .2s ease,border-color .2s ease,box-shadow .2s ease;
    }
    .idle-project-fab:hover{
        transform:translateY(-2px);
        background:rgba(15,23,42,.96);
        border-color:rgba(125,211,252,.58);
        box-shadow:0 18px 42px rgba(0,0,0,.45);
        color:#fff;
    }
    .idle-project-fab-icon{
        width:28px;
        height:28px;
        display:grid;
        place-items:center;
        border-radius:50%;
        background:rgba(56,189,248,.13);
        color:#7dd3fc;
        font-size:14px;
    }
    @media(max-width:600px){
        .idle-project-fab{right:16px;bottom:16px;padding:10px 13px}
        .idle-project-fab-icon{width:25px;height:25px}
    }
</style>

<a class="idle-project-fab" href="{{ route('monitor.idle') }}" aria-label="Buka Project Showcase">
    <span class="idle-project-fab-icon">▶</span>
    <span>Project Showcase</span>
</a>
