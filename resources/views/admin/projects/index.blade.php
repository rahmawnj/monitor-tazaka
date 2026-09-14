<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management — Monitor Tazaka</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#0b1020;color:#eef2ff;font-family:Inter,ui-sans-serif,system-ui,sans-serif}button,input,select,textarea{font:inherit}a{text-decoration:none;color:inherit}.wrap{max-width:1450px;margin:auto;padding:32px}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:28px}.eyebrow{color:#7dd3fc;font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.title{font-size:30px;font-weight:800;margin:5px 0}.muted{color:#94a3b8}.actions{display:flex;gap:10px}.btn{border:0;border-radius:10px;padding:11px 16px;background:#1e293b;color:#fff;cursor:pointer;font-weight:700}.btn.primary{background:#2563eb}.btn.danger{background:#7f1d1d}.grid{display:block}.card{background:#111827;border:1px solid #1f2937;border-radius:18px;padding:22px;box-shadow:0 18px 50px #0003}.card h2{font-size:18px;margin:0 0 18px}.control{width:100%;background:#0b1222;border:1px solid #263246;border-radius:10px;padding:11px 12px;color:#fff;outline:none;transition:.2s}.control:focus{border-color:#38bdf8;box-shadow:0 0 0 3px #38bdf81c}.control::placeholder{color:#526078}.row{display:grid;grid-template-columns:1fr 1fr;gap:12px}.coords{display:grid;grid-template-columns:1fr 1fr;gap:12px}.section{border-top:1px solid #1f2937;padding-top:17px;margin-top:17px}.section-title{font-size:12px;color:#7dd3fc;font-weight:800;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px}.project{border:1px solid #263246;background:#0d1424;border-radius:14px;padding:17px;margin-bottom:12px;cursor:grab;transition:.18s}.project:active{cursor:grabbing}.project.dragging{opacity:.45;transform:scale(.99)}.project.drag-over{border-color:#38bdf8;box-shadow:0 0 0 2px #38bdf822}.project-head{display:flex;justify-content:space-between;gap:12px}.project-name{font-weight:800;font-size:16px}.badge{font-size:11px;border-radius:999px;padding:5px 9px;background:#172554;color:#93c5fd;white-space:nowrap}.meta{font-size:12px;color:#94a3b8;margin-top:5px}.progress-control{margin-top:14px}.progress-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}.progress-label{font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;font-weight:800}.progress-value{font-size:16px;font-weight:850;color:#7dd3fc;min-width:48px;text-align:right}.progress-slider{width:100%;height:7px;appearance:none;-webkit-appearance:none;border-radius:99px;background:linear-gradient(to right,#38bdf8 0%,#38bdf8 var(--progress),#1e293b var(--progress),#1e293b 100%);outline:none;cursor:pointer}.progress-slider::-webkit-slider-thumb{appearance:none;-webkit-appearance:none;width:20px;height:20px;border-radius:50%;background:#fff;border:3px solid #38bdf8;box-shadow:0 0 0 3px #38bdf822;cursor:grab}.progress-slider::-webkit-slider-thumb:active{cursor:grabbing}.progress-slider::-moz-range-thumb{width:14px;height:14px;border-radius:50%;background:#fff;border:3px solid #38bdf8;box-shadow:0 0 0 3px #38bdf822;cursor:grab}.progress-saving{font-size:10px;color:#64748b;margin-top:6px;min-height:14px}.project-actions{display:flex;gap:8px;margin-top:13px}.project-actions form{margin:0}.drag-handle{float:right;color:#64748b;font-size:18px;line-height:1;margin-left:8px;user-select:none}.empty{padding:40px;text-align:center;color:#64748b}.alert{padding:13px 16px;border-radius:12px;margin-bottom:18px;background:#0f1f32;border:1px solid #1e3a5f}.alert.success{color:#86efac}.alert.error{color:#fca5a5}.error{display:block;color:#fca5a5;font-size:11px;margin-top:5px}.helper{font-size:11px;color:#64748b;margin-top:5px}.order-status{font-size:11px;color:#64748b;margin:-10px 0 14px}.modal{position:fixed;inset:0;z-index:1000;display:none;align-items:center;justify-content:center;padding:24px;background:#020617b8;backdrop-filter:blur(7px)}.modal.open{display:flex}.modal-box{width:min(720px,100%);max-height:90vh;overflow:auto;background:#111827;border:1px solid #263246;border-radius:20px;padding:24px;box-shadow:0 30px 90px #0008}.modal-head{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:20px}.modal-title{font-size:21px;font-weight:800}.modal-subtitle{font-size:12px;color:#64748b;margin-top:4px}.close-btn{width:38px;height:38px;border:0;border-radius:10px;background:#1e293b;color:#cbd5e1;font-size:22px;cursor:pointer;line-height:1}.modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}.flash-wrap{margin-bottom:18px}@media(max-width:600px){.row,.coords{grid-template-columns:1fr}.top{align-items:flex-start;gap:15px;flex-direction:column}.wrap{padding:18px}.modal{padding:12px}.modal-box{padding:18px;max-height:94vh}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <div>
            <div class="eyebrow">Monitor Tazaka</div>
            <div class="title">Project Management</div>
            <div class="muted">Kelola data project yang tampil di monitor kantor.</div>
        </div>
        <div class="actions">
            <button class="btn primary" type="button" onclick="openProjectModal()">+ Tambah Project</button>
            <a class="btn" href="{{ route('monitor') }}" target="_blank">Buka Monitor ↗</a>
        </div>
    </div>

    @if(session('success') || $errors->any())
        <div class="flash-wrap">
            @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
            @if($errors->any())
                <div class="alert error">Periksa kembali input yang bertanda merah.</div>
            @endif
        </div>
    @endif

    <div class="card">
        <h2>Daftar Project ({{ $projects->count() }})</h2>
        <div class="order-status" id="orderStatus">↕ Drag project untuk mengatur urutan tampil di monitor.</div>
        <div id="projectList">
            @forelse($projects as $project)
                <div class="project" draggable="true" data-id="{{ $project->id }}">
                    <span class="drag-handle" title="Drag untuk memindahkan">⋮⋮</span>
                    <div class="project-head">
                        <div>
                            <div class="project-name">{{ $project->name }}</div>
                            <div class="meta">{{ $project->client }} · {{ str_replace('_', ' ', ucfirst($project->project_type)) }}</div>
                        </div>
                        <span class="badge">{{ $project->progress }}%</span>
                    </div>
                    <div class="progress-control">
                        <div class="progress-head">
                            <span class="progress-label">Progress</span>
                            <span class="progress-value" data-progress-value>{{ $project->progress }}%</span>
                        </div>
                        <input class="progress-slider" type="range" min="0" max="100" step="1" value="{{ $project->progress }}" data-progress-slider data-id="{{ $project->id }}" style="--progress:{{ $project->progress }}%" aria-label="Progress {{ $project->name }}">
                        <div class="progress-saving" data-progress-status></div>
                    </div>
                    <div class="meta">Target: {{ $project->target_completion_date?->format('d M Y') ?? '-' }} · Month: {{ $project->project_month?->format('M Y') ?? '-' }}</div>
                    @if($project->location)<div class="meta">📍 {{ $project->location }}</div>@endif
                    <div class="project-actions">
                        <button class="btn" type="button" onclick="alert('Untuk edit, sementara data project dapat diperbarui lewat form berikutnya.')">Edit</button>
                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Hapus project ini?')">
                            @csrf @method('DELETE')
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty">Belum ada project.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="modal {{ $errors->any() ? 'open' : '' }}" id="projectModal" aria-hidden="{{ $errors->any() ? 'false' : 'true' }}">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="projectModalTitle">
        <div class="modal-head">
            <div>
                <div class="modal-title" id="projectModalTitle">Tambah Project</div>
                <div class="modal-subtitle">Isi detail project yang akan tampil di monitor.</div>
            </div>
            <button class="close-btn" type="button" onclick="closeProjectModal()" aria-label="Tutup">×</button>
        </div>

        <form method="POST" action="{{ route('admin.projects.store') }}">
            @csrf
            <x-forms.input label="Nama Project" name="name" placeholder="Contoh: Office Network Upgrade" required />
            <x-forms.input label="Client" name="client" placeholder="Nama perusahaan / client" required />
            <div class="row">
                <x-forms.select label="Project Type" name="project_type" required>
                    <option value="">Pilih tipe project</option>
                    <option value="tazaka_order" @selected(old('project_type') === 'tazaka_order')>Tazaka Order</option>
                    <option value="subcontract" @selected(old('project_type') === 'subcontract')>Subcontract</option>
                    <option value="external" @selected(old('project_type') === 'external')>External</option>
                </x-forms.select>
            </div>
            <div class="row">
                <x-forms.input label="Target Completion" name="target_completion_date" type="date" />
                <x-forms.input label="Project Month" name="project_month" type="month" />
            </div>
            <div class="section">
                <div class="section-title">Project Detail</div>
                <x-forms.textarea label="Description" name="description" placeholder="Tulis deskripsi project..." />
                <x-forms.textarea label="Notes" name="notes" placeholder="Tulis catatan project..." />
            </div>
            <div class="section">
                <div class="section-title">Location</div>
                <x-forms.input label="Location" name="location" placeholder="Contoh: Bandung, West Java" />
                <div class="coords">
                    <x-forms.input label="Latitude" name="lat" type="number" step="any" min="-90" max="90" placeholder="-6.9175" />
                    <x-forms.input label="Longitude" name="lng" type="number" step="any" min="-180" max="180" placeholder="107.6191" />
                </div>
                <div class="helper">Isi latitude & longitude kalau project ingin ditampilkan sebagai marker di peta.</div>
            </div>
            <div class="modal-actions">
                <button class="btn" type="button" onclick="closeProjectModal()">Batal</button>
                <button class="btn primary" type="submit">Simpan Project</button>
            </div>
        </form>
    </div>
</div>

<script>
const list=document.getElementById('projectList');
const statusEl=document.getElementById('orderStatus');
const projectModal=document.getElementById('projectModal');
let dragged=null;

function openProjectModal(){
    projectModal.classList.add('open');
    projectModal.setAttribute('aria-hidden','false');
    document.body.style.overflow='hidden';
}
function closeProjectModal(){
    projectModal.classList.remove('open');
    projectModal.setAttribute('aria-hidden','true');
    document.body.style.overflow='';
}
projectModal?.addEventListener('click',e=>{if(e.target===projectModal)closeProjectModal();});
document.addEventListener('keydown',e=>{if(e.key==='Escape' && projectModal?.classList.contains('open'))closeProjectModal();});

list?.querySelectorAll('.project[draggable="true"]').forEach(card=>{
    card.addEventListener('dragstart',()=>{dragged=card;card.classList.add('dragging');});
    card.addEventListener('dragend',async()=>{card.classList.remove('dragging');list.querySelectorAll('.project').forEach(x=>x.classList.remove('drag-over'));dragged=null;await saveOrder();});
    card.addEventListener('dragover',e=>{
        e.preventDefault();
        if(!dragged||dragged===card)return;
        const rect=card.getBoundingClientRect();
        const after=e.clientY>rect.top+rect.height/2;
        card.classList.add('drag-over');
        if(after) card.after(dragged); else card.before(dragged);
    });
    card.addEventListener('dragleave',()=>card.classList.remove('drag-over'));
    card.addEventListener('drop',e=>{e.preventDefault();card.classList.remove('drag-over');});
});

list?.querySelectorAll('[data-progress-slider]').forEach(slider=>{
    const card=slider.closest('.project');
    const valueEl=card.querySelector('[data-progress-value]');
    const status=card.querySelector('[data-progress-status]');
    const updateVisual=()=>{
        const value=Number(slider.value);
        slider.style.setProperty('--progress',value+'%');
        valueEl.textContent=value+'%';
    };
    slider.addEventListener('input',updateVisual);
    slider.addEventListener('change',async()=>{
        const value=Number(slider.value);
        status.textContent='Menyimpan...';
        try{
            const response=await fetch('{{ url('/admin/projects') }}/'+slider.dataset.id+'/progress',{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({progress:value})});
            if(!response.ok)throw new Error('Gagal menyimpan progress');
            card.querySelector('.badge').textContent=value+'%';
            status.textContent='✓ Tersimpan';
            setTimeout(()=>status.textContent='',1400);
        }catch(error){
            status.textContent='⚠ Gagal menyimpan';
            console.error(error);
        }
    });
});

async function saveOrder(){
    const ids=[...list.querySelectorAll('.project[data-id]')].map(x=>Number(x.dataset.id));
    if(!ids.length)return;
    statusEl.textContent='Menyimpan urutan...';
    try{
        const response=await fetch('{{ route('admin.projects.reorder') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({ids})});
        if(!response.ok)throw new Error('Gagal menyimpan');
        statusEl.textContent='✓ Urutan tersimpan';
        setTimeout(()=>statusEl.textContent='↕ Drag project untuk mengatur urutan tampil di monitor.',1800);
    }catch(error){
        statusEl.textContent='⚠ Gagal menyimpan urutan. Coba lagi.';
        console.error(error);
    }
}

@if($errors->any())
openProjectModal();
@endif
</script>
</body>
</html>
