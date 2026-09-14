<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Project Monitor</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#070b14;color:#f8fafc;font-family:Inter,ui-sans-serif,system-ui,sans-serif}.wrap{max-width:1600px;margin:auto;padding:30px}.header{margin-bottom:24px}.eyebrow{font-size:11px;letter-spacing:.2em;color:#38bdf8;font-weight:800;text-transform:uppercase}.title{font-size:34px;font-weight:850;margin:5px 0}.muted{color:#94a3b8}.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px}.kpi,.card{background:#0d1320;border:1px solid #1e293b;border-radius:18px}.kpi{padding:20px}.kpi-label{font-size:12px;color:#94a3b8}.kpi-value{font-size:32px;font-weight:850;margin-top:8px}.main{display:grid;grid-template-columns:1fr 1fr;gap:18px}.card{padding:20px}.card h2{font-size:16px;margin:0 0 18px}.chart-wrap{height:280px}.map-card{margin-top:18px}.map-wrap{height:430px;border-radius:14px;overflow:hidden;border:1px solid #1e293b}.map{width:100%;height:100%;background:#0a101b}.leaflet-container{font-family:Inter,ui-sans-serif,system-ui,sans-serif;background:#0b1220}.leaflet-popup-content-wrapper,.leaflet-popup-tip{background:#0d1320;color:#f8fafc}.leaflet-popup-content{font-size:12px;line-height:1.5}.leaflet-control-zoom a{background:#0d1320;color:#f8fafc;border-color:#334155}.projects{display:grid;grid-template-columns:1fr 1fr;gap:12px}.project{padding:15px;border:1px solid #1e293b;border-radius:14px;background:#0a101b}.phead{display:flex;justify-content:space-between;gap:10px}.pname{font-weight:800}.client{font-size:12px;color:#94a3b8;margin-top:3px}.badge{font-size:10px;padding:4px 8px;border-radius:99px;background:#172554;color:#93c5fd;height:max-content}.progress{height:7px;background:#1e293b;border-radius:99px;margin-top:13px;overflow:hidden}.bar{height:100%;background:#38bdf8;border-radius:99px;transition:width .4s}.foot{display:flex;justify-content:space-between;font-size:11px;color:#64748b;margin-top:7px}@media(max-width:1000px){.kpis{grid-template-columns:1fr 1fr}.main{grid-template-columns:1fr}.projects{grid-template-columns:1fr}}@media(max-width:600px){.wrap{padding:16px}.title{font-size:25px}.map-wrap{height:340px}}
    </style>
</head>
<body>
<div class="wrap">
    <header class="header"><div><div class="eyebrow">Tazaka Management</div><div class="title">Executive Project Monitor</div></div></header>
    <section class="kpis"><div class="kpi"><div class="kpi-label">TOTAL PROJECT</div><div class="kpi-value" id="total">0</div></div><div class="kpi"><div class="kpi-label">RUNNING</div><div class="kpi-value" id="running">0</div></div><div class="kpi"><div class="kpi-label">COMPLETED</div><div class="kpi-value" id="completed">0</div></div><div class="kpi"><div class="kpi-label">AVG PROGRESS</div><div class="kpi-value"><span id="average">0</span>%</div></div></section>
    <section class="main"><div class="card"><h2>Project Type</h2><div class="chart-wrap"><canvas id="projectTypeChart"></canvas></div></div><div class="card"><h2>Overall Progress</h2><div class="chart-wrap"><canvas id="progressChart"></canvas></div></div></section>
    <section class="card map-card"><h2>Project Locations · Indonesia</h2><div class="map-wrap"><div id="projectMap" class="map"></div></div></section>
    <section class="card" style="margin-top:18px"><h2>Active Projects</h2><div id="projects" class="projects"></div></section>
</div>
<script>
const initialProjects=@json($projects);let projectTypeChart,progressChart,projectMap,projectMarkers;
const chartColors=['#38bdf8','#a78bfa','#f59e0b','#34d399','#fb7185'];
const chartTextColor='#e2e8f0';
function typeLabel(t){return{tazaka_order:'Tazaka Order',subcontract:'Subcontract',external:'External'}[t]??t}
function escapeHtml(v){return String(v??'').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]))}
function render(projects,summary=null){
    const s=summary??{total:projects.length,running:projects.filter(p=>Number(p.progress||0)<100).length,completed:projects.filter(p=>Number(p.progress||0)>=100).length,average_progress:projects.length?Math.round(projects.reduce((a,p)=>a+Number(p.progress||0),0)/projects.length):0};
    document.getElementById('total').textContent=s.total;document.getElementById('running').textContent=s.running;document.getElementById('completed').textContent=s.completed;document.getElementById('average').textContent=s.average_progress;
    if(typeof Chart==='undefined'){console.error('Chart.js gagal dimuat');return;}
    const types=['tazaka_order','subcontract','external'];const typeValues=types.map(t=>projects.filter(p=>p.project_type===t).length);
    if(projectTypeChart)projectTypeChart.destroy();
    projectTypeChart=new Chart(document.getElementById('projectTypeChart'),{type:'pie',data:{labels:types.map(typeLabel),datasets:[{data:typeValues,backgroundColor:chartColors,borderWidth:2,borderColor:'#0d1320'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{color:chartTextColor,padding:16,font:{size:12,weight:'600'}}}}}});
    const completed=projects.filter(p=>Number(p.progress||0)>=100).length,running=projects.length-completed;
    if(progressChart)progressChart.destroy();
    progressChart=new Chart(document.getElementById('progressChart'),{type:'doughnut',data:{labels:['Completed','In Progress'],datasets:[{data:[completed,running],backgroundColor:['#34d399','#38bdf8'],borderWidth:2,borderColor:'#0d1320'}]},options:{responsive:true,maintainAspectRatio:false,cutout:'72%',plugins:{legend:{position:'bottom',labels:{color:chartTextColor,padding:16,font:{size:12,weight:'600'}}}}}});
    const sorted=[...projects].sort((a,b)=>Number(b.progress)-Number(a.progress));document.getElementById('projects').innerHTML=sorted.length?sorted.map(p=>`<article class="project"><div class="phead"><div><div class="pname">${escapeHtml(p.name)}</div><div class="client">${escapeHtml(p.client??'')}</div></div><span class="badge">${typeLabel(p.project_type)}</span></div><div class="progress"><div class="bar" style="width:${Number(p.progress)}%"></div></div><div class="foot"><span>${Number(p.progress)}% progress</span><span>Target: ${p.target_completion_date??'-'}</span></div></article>`).join(''):'<div class="muted">Belum ada project.</div>';
}
function initMap(){
    if(typeof L==='undefined'){console.error('Leaflet gagal dimuat');return;}
    projectMap=L.map('projectMap',{zoomControl:true,worldCopyJump:false}).setView([-2.5,118],5);L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap contributors'}).addTo(projectMap);projectMarkers=L.layerGroup().addTo(projectMap);renderMap(initialProjects);
}
function renderMap(projects){if(!projectMap)return;projectMarkers.clearLayers();projects.forEach(p=>{const c=p.latlong,lat=Number(c?.lat),lng=Number(c?.lng);if(!Number.isFinite(lat)||!Number.isFinite(lng))return;const marker=L.marker([lat,lng]).addTo(projectMarkers);marker.bindPopup(`<strong>${escapeHtml(p.name)}</strong><br>${escapeHtml(p.client??'')}<br>Progress: ${Number(p.progress||0)}%<br>${escapeHtml(p.location??'')}`)});projectMap.setView([-2.5,118],5)}
render(initialProjects);initMap();setTimeout(()=>projectMap?.invalidateSize(),200);
</script>
</body></html>
