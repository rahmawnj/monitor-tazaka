<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management — Tazaka Elektrik Mandiri</title>
    <link rel="icon" type="image/png" href="https://tazaka.co.id/assets/logo-tazaka-PPqkEZXu.png">
    <style>
        *{box-sizing:border-box}body{margin:0;background:#0b1020;color:#eef2ff;font-family:Inter,ui-sans-serif,system-ui,sans-serif}.wrap{max-width:1450px;margin:auto;padding:32px}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:28px}.brand{display:flex;align-items:center;gap:14px}.brand-logo{width:54px;height:54px;object-fit:contain;border-radius:12px;background:#fff;padding:5px}.brand-name{font-size:13px;font-weight:800;color:#e2e8f0}.title{font-size:30px;font-weight:800;margin:4px 0}.actions{display:flex;gap:10px}.btn{display:inline-block;border:0;border-radius:10px;padding:11px 16px;background:#1e293b;color:#fff;cursor:pointer;font-weight:700;text-decoration:none}.btn.primary{background:#2563eb}.btn.danger{background:#7f1d1d}.card{background:#111827;border:1px solid #1f2937;border-radius:18px;padding:22px;box-shadow:0 18px 50px #0003}.card h2{font-size:18px;margin:0 0 18px}.project{position:relative;border:1px solid #263246;background:#0d1424;border-radius:14px;padding:17px 17px 17px 58px;margin-bottom:12px;cursor:grab;transition:.18s}.project:active{cursor:grabbing}.project.dragging{opacity:.45;transform:scale(.99)}.project.drag-over{border-color:#38bdf8;box-shadow:0 0 0 2px #38bdf822}.project-number{position:absolute;left:16px;top:17px;width:30px;height:30px;border-radius:9px;background:#172554;color:#93c5fd;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:900}.project-head{display:flex;justify-content:space-between;gap:12px}.project-name{font-weight:800;font-size:16px}.badge{font-size:11px;border-radius:999px;padding:5px 9px;background:#172554;color:#93c5fd;white-space:nowrap}.meta{font-size:12px;color:#94a3b8;margin-top:5px}.progress-summary{margin-top:14px}.progress-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}.progress-label{font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;font-weight:800}.progress-value{font-size:16px;font-weight:850;color:#7dd3fc}.progress-bar{height:7px;border-radius:99px;background:#1e293b;overflow:hidden}.progress-fill{height:100%;border-radius:99px;background:#38bdf8}.project-actions{display:flex;gap:8px;margin-top:13px}.project-actions form{margin:0}.drag-handle{float:right;color:#64748b;font-size:18px;line-height:1;margin-left:8px;user-select:none}.empty{padding:40px;text-align:center;color:#64748b}.order-status{font-size:11px;color:#64748b;margin:-10px 0 14px}@media(max-width:600px){.top{align-items:flex-start;gap:15px;flex-direction:column}.wrap{padding:18px}.project-actions{flex-wrap:wrap}.brand-logo{width:46px;height:46px}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="top"><div class="brand"><img class="brand-logo" src="https://tazaka.co.id/assets/logo-tazaka-PPqkEZXu.png" alt="Logo Tazaka"><div><div class="brand-name">Tazaka Elektrik Mandiri</div><div class="title">Project Management</div></div></div><div class="actions"><a class="btn primary" href="{{ route('admin.projects.create') }}">+ Tambah Project</a><a class="btn" href="{{ route('monitor') }}" target="_blank">Buka Monitor ↗</a></div></div>
    <div class="card">
        <h2>Daftar Project ({{ $projects->count() }})</h2>
        <div class="order-status" id="orderStatus">↕ Drag project untuk mengatur urutan tampil di monitor.</div>
        <div id="projectList">
            @forelse($projects as $project)
                <div class="project" draggable="true" data-id="{{ $project->id }}">
                    <span class="project-number">{{ $loop->iteration }}</span>
                    <span class="drag-handle">⋮⋮</span>
                    <div class="project-head"><div><div class="project-name">{{ $project->name }}</div><div class="meta">{{ $project->client }} · {{ str_replace('_',' ',ucfirst($project->project_type)) }}</div></div><span class="badge">{{ $project->progress }}%</span></div>
                    <div class="progress-summary"><div class="progress-head"><span class="progress-label">Progress</span><span class="progress-value">{{ $project->progress }}%</span></div><div class="progress-bar"><div class="progress-fill" style="width:{{ $project->progress }}%"></div></div></div>
                    <div class="meta">Target: {{ $project->target_completion_date?->format('d M Y') ?? '-' }} · Month: {{ $project->project_month?->format('M Y') ?? '-' }}</div>
                    @if($project->location)<div class="meta">📍 {{ $project->location }}</div>@endif
                    <div class="project-actions"><a class="btn" href="{{ route('admin.projects.show',$project) }}">Detail</a><a class="btn primary" href="{{ route('admin.projects.edit',$project) }}">Edit</a><form method="POST" action="{{ route('admin.projects.destroy',$project) }}" class="delete-form">@csrf @method('DELETE')<button class="btn danger" type="submit">Hapus</button></form></div>
                </div>
            @empty
                <div class="empty">Belum ada project.</div>
            @endforelse
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-form').forEach(form=>form.addEventListener('submit',function(e){e.preventDefault();Swal.fire({title:'Hapus project?',text:'Project ini akan dihapus permanen.',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus',cancelButtonText:'Batal',confirmButtonColor:'#dc2626'}).then(r=>{if(r.isConfirmed)this.submit()})}));
@if(session('success'))Swal.fire({toast:true,position:'top-end',icon:'success',title:@json(session('success')),showConfirmButton:false,timer:2200,timerProgressBar:true});@endif
const list=document.getElementById('projectList');let dragged=null;list?.querySelectorAll('.project').forEach(card=>{card.addEventListener('dragstart',()=>{dragged=card;card.classList.add('dragging')});card.addEventListener('dragend',()=>{card.classList.remove('dragging');updateNumbers();saveOrder()});card.addEventListener('dragover',e=>{e.preventDefault();if(card!==dragged){const rect=card.getBoundingClientRect();const after=e.clientY>rect.top+rect.height/2;if(after)card.after(dragged);else card.before(dragged)}})});function updateNumbers(){list?.querySelectorAll('.project-number').forEach((number,index)=>number.textContent=index+1)}function saveOrder(){const ids=[...list.querySelectorAll('.project')].map(x=>x.dataset.id);fetch('{{ route('admin.projects.reorder') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({ids})}).then(r=>r.json()).then(d=>{if(d.success)Swal.fire({toast:true,position:'top-end',icon:'success',title:'Urutan tersimpan',showConfirmButton:false,timer:1200})})}
</script>
</body>
</html>
