<style>
.last-updated{margin-left:auto;display:flex;align-items:center;gap:9px;padding:9px 12px;border:1px solid rgba(148,163,184,.16);border-radius:12px;background:rgba(15,23,42,.55);color:#94a3b8;font-size:11px;line-height:1.2;white-space:nowrap;backdrop-filter:blur(8px)}
.last-updated-dot{width:7px;height:7px;border-radius:50%;background:#34d399;box-shadow:0 0 0 4px rgba(52,211,153,.10),0 0 12px rgba(52,211,153,.5)}
.last-updated strong{color:#e2e8f0;font-weight:750}
.last-updated.bump{animation:lastUpdatedBump .5s ease}
@keyframes lastUpdatedBump{0%{transform:scale(1);border-color:rgba(148,163,184,.16)}45%{transform:scale(1.035);border-color:rgba(56,189,248,.55);box-shadow:0 0 0 5px rgba(56,189,248,.07)}100%{transform:scale(1);border-color:rgba(148,163,184,.16);box-shadow:none}}
@media(max-width:700px){.last-updated{padding:7px 9px;font-size:10px}.last-updated .last-updated-time{display:none}}
</style>
<script>
(() => {
    const init=()=>{
        const header=document.querySelector('.header');
        if(!header || document.getElementById('lastUpdated'))return;
        const box=document.createElement('div');
        box.id='lastUpdated';
        box.className='last-updated';
        box.innerHTML='<span class="last-updated-dot"></span><span>Updated <strong class="last-updated-relative">just now</strong><span class="last-updated-time"></span></span>';
        header.appendChild(box);

        let updatedAt=Date.now();
        const relative=box.querySelector('.last-updated-relative');
        const clock=box.querySelector('.last-updated-time');
        const bump=()=>{box.classList.remove('bump');void box.offsetWidth;box.classList.add('bump');updatedAt=Date.now();renderTime()};
        const renderTime=()=>{
            const seconds=Math.max(0,Math.floor((Date.now()-updatedAt)/1000));
            relative.textContent=seconds<5?'just now':seconds<60?`${seconds} seconds ago`:seconds<3600?`${Math.floor(seconds/60)} minutes ago`:`${Math.floor(seconds/3600)} hours ago`;
            clock.textContent=' · '+new Intl.DateTimeFormat('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'}).format(new Date(updatedAt));
        };
        window.__markMonitorUpdated=bump;
        renderTime();
        setInterval(renderTime,1000);

        const originalFetch=window.fetch;
        window.fetch=async function(...args){
            const response=await originalFetch.apply(this,args);
            try{
                const url=String(args[0]?.url||args[0]||'');
                if(url.includes('/monitor/data') && response.ok){window.__markMonitorUpdated?.()}
            }catch(e){}
            return response;
        };
    };
    if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init,{once:true});else init();
})();
</script>
