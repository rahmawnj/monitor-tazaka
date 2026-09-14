<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit {{ $project->name }} — Tazaka Elektrik Teknologi</title>
<link rel="icon" type="image/png" href="https://tazaka.co.id/assets/logo-tazaka-PPqkEZXu.png">
<style>
*{box-sizing:border-box}body{margin:0;background:radial-gradient(circle at 80% 0%,#172554 0,#0b1020 35%,#080c18 100%);color:#eef2ff;font-family:Inter,ui-sans-serif,system-ui,sans-serif;min-height:100vh}.wrap{max-width:1050px;margin:auto;padding:36px 28px 60px}.top{display:flex;justify-content:space-between;align-items:center;gap:20px;margin-bottom:26px}.brand{display:flex;align-items:center;gap:14px}.brand-logo{width:54px;height:54px;object-fit:contain;border-radius:14px;background:#fff;padding:5px;box-shadow:0 8px 30px #0004}.brand-name{font-size:12px;font-weight:850;color:#93c5fd;letter-spacing:.1em;text-transform:uppercase}.eyebrow{color:#64748b;font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;margin-top:3px}.title{font-size:30px;font-weight:850;line-height:1.15;margin:5px 0}.muted{color:#94a3b8;font-size:13px}.card{background:linear-gradient(145deg,#121a2b,#0d1423);border:1px solid #263246;border-radius:22px;padding:30px;box-shadow:0 25px 70px #0005}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}.full{grid-column:1/-1}.section{margin-top:28px;padding-top:25px;border-top:1px solid #202c40}.section-title{display:flex;align-items:center;gap:9px;font-size:12px;color:#7dd3fc;font-weight:850;text-transform:uppercase;letter-spacing:.1em;margin-bottom:18px}.section-title:before{content:'';width:7px;height:7px;border-radius:50%;background:#38bdf8;box-shadow:0 0 12px #38bdf8}.field{margin:0 0 18px}.field label{display:block;margin:0 0 8px;font-size:12px;font-weight:800;color:#cbd5e1}.field label span{color:#38bdf8}.control{display:block;width:100%;height:48px;border:1px solid #2b3a52;border-radius:12px;background:#0a1120;color:#f8fafc;padding:0 14px;font:inherit;font-size:13px;outline:0;transition:.18s;box-shadow:inset 0 1px 0 #ffffff05}.control::placeholder{color:#526078}.control:hover{border-color:#3b4c67}.control:focus{border-color:#38bdf8;box-shadow:0 0 0 4px #38bdf815}.field select.control{appearance:none;background-image:linear-gradient(45deg,transparent 50%,#64748b 50%),linear-gradient(135deg,#64748b 50%,transparent 50%);background-position:calc(100% - 18px) 21px,calc(100% - 13px) 21px;background-size:5px 5px,5px 5px;background-repeat:no-repeat}.error{display:block;color:#fb7185;font-size:11px;margin-top:6px}.helper{font-size:11px;color:#64748b;margin-top:-8px;margin-bottom:16px}.wysiwyg{border:1px solid #2b3a52;border-radius:13px;overflow:hidden;background:#0a1120;transition:.18s}.wysiwyg:focus-within{border-color:#38bdf8;box-shadow:0 0 0 4px #38bdf815}.wysiwyg-toolbar{display:flex;gap:4px;padding:8px;border-bottom:1px solid #202c40;background:#111a2b}.wysiwyg-toolbar button{border:0;border-radius:7px;background:transparent;color:#94a3b8;padding:7px 10px;cursor:pointer;font-size:12px}.wysiwyg-toolbar button:hover{background:#1e293b;color:#fff}.wysiwyg-editor{min-height:155px;padding:14px;color:#f8fafc;outline:0;line-height:1.65;font-size:13px}.wysiwyg-editor:empty:before{content:attr(data-placeholder);color:#526078}.coords{display:grid;grid-template-columns:1fr 1fr;gap:16px}.actions{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:30px;padding-top:22px;border-top:1px solid #202c40}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:44px;padding:0 17px;border:1px solid #2b3a52;border-radius:11px;background:#172033;color:#e2e8f0;cursor:pointer;font-weight:800;text-decoration:none;font-size:13px}.btn:hover{background:#1e293b}.btn.primary{border-color:#2563eb;background:#2563eb;color:#fff;box-shadow:0 8px 24px #2563eb33}.btn.primary:hover{background:#1d4ed8}.btn.danger{border-color:#7f1d1d;background:#3f1118;color:#fecaca}.required-note{font-size:11px;color:#64748b}.required-note b{color:#38bdf8}.image-drop{border:1.5px dashed #38506f;border-radius:16px;background:#0a1120;padding:28px;text-align:center;cursor:pointer;transition:.18s}.image-drop:hover,.image-drop.dragging{border-color:#38bdf8;background:#0d1729;box-shadow:0 0 0 4px #38bdf810}.image-drop strong{display:block;font-size:14px;color:#e2e8f0}.image-drop span{display:block;margin-top:6px;font-size:11px;color:#64748b}.image-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:18px}.image-item{position:relative;border:1px solid #293852;border-radius:14px;background:#0a1120;overflow:hidden;cursor:grab}.image-item:active{cursor:grabbing}.image-thumb{display:block;width:100%;height:150px;object-fit:cover;background:#111827}.image-meta{padding:10px 11px;display:flex;align-items:center;gap:8px}.image-order{width:26px;height:26px;border-radius:8px;background:#172554;color:#93c5fd;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;flex:0 0 auto}.image-name{font-size:11px;color:#cbd5e1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-width:0}.image-remove{position:absolute;right:9px;top:9px;width:30px;height:30px;border:1px solid #7f1d1d;border-radius:9px;background:#3f1118;color:#fecaca;display:flex;align-items:center;justify-content:center;cursor:pointer;font-weight:900}.image-remove:hover{background:#5b151f}.empty-images{border:1px dashed #293852;border-radius:14px;padding:24px;text-align:center;color:#64748b;font-size:12px}.image-section-head{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:14px}.image-section-head .helper{margin:0}.hidden-input{display:none}@media(max-width:700px){.wrap{padding:22px 16px}.top{align-items:flex-start;flex-direction:column}.form-grid,.coords{grid-template-columns:1fr}.full{grid-column:auto}.card{padding:20px}.actions{flex-direction:column-reverse;align-items:stretch}.actions>div{display:flex!important}.btn{width:100%}.image-grid{grid-template-columns:1fr 1fr}.image-thumb{height:125px}}@media(max-width:450px){.image-grid{grid-template-columns:1fr}}
</style>
@stack('styles')
</head>
<body><div class="wrap">
<div class="top"><div class="brand"><img class="brand-logo" src="https://tazaka.co.id/assets/logo-tazaka-PPqkEZXu.png" alt="Logo Tazaka"><div><div class="brand-name">Tazaka Elektrik Teknologi</div><div class="eyebrow">Project Management</div><div class="title">Edit Project</div><div class="muted">{{ $project->name }} · {{ $project->client }}</div></div></div><a class="btn" href="{{ route('admin.projects.show',$project) }}">← Detail</a></div>

<div class="card"><form method="POST" action="{{ route('admin.projects.update',$project) }}">@csrf @method('PUT')
<div class="form-grid"><div><x-forms.input label="Nama Project" name="name" value="{{ old('name',$project->name) }}" required /></div><div><x-forms.input label="Client" name="client" value="{{ old('client',$project->client) }}" required /></div><div><x-forms.select label="Project Type" name="project_type" required><option value="tazaka_order" @selected(old('project_type',$project->project_type)==='tazaka_order')>Tazaka Order</option><option value="subcontract" @selected(old('project_type',$project->project_type)==='subcontract')>Subcontract</option><option value="external" @selected(old('project_type',$project->project_type)==='external')>External</option></x-forms.select></div><div><x-forms.input label="Target Completion" name="target_completion_date" type="date" value="{{ old('target_completion_date',$project->target_completion_date?->format('Y-m-d')) }}" /></div><div><x-forms.input label="Project Month" name="project_month" type="month" value="{{ old('project_month',$project->project_month?->format('Y-m')) }}" /></div></div>
<div class="section"><div class="section-title">Project Detail</div><div class="form-grid"><div class="full"><x-forms.textarea label="Description" name="description" placeholder="Jelaskan pekerjaan, scope, atau detail utama project...">{{ old('description',$project->description) }}</x-forms.textarea></div><div class="full"><x-forms.textarea label="Notes" name="notes" placeholder="Tambahkan catatan, kendala, atau informasi penting...">{{ old('notes',$project->notes) }}</x-forms.textarea></div></div></div>
<div class="section"><div class="section-title">Location & Map</div><div class="form-grid"><div class="full"><x-forms.input label="Location" name="location" value="{{ old('location',$project->location) }}" placeholder="Contoh: Bandung, West Java" /></div></div><div class="coords"><x-forms.input label="Latitude" name="lat" type="number" step="any" min="-90" max="90" value="{{ old('lat',data_get($project->latlong,'lat')) }}" placeholder="-6.9175" /><x-forms.input label="Longitude" name="lng" type="number" step="any" min="-180" max="180" value="{{ old('lng',data_get($project->latlong,'lng')) }}" placeholder="107.6191" /></div></div>
<div class="actions"><span class="required-note"><b>*</b> Wajib diisi</span><div style="display:flex;gap:10px"><a class="btn" href="{{ route('admin.projects.show',$project) }}">Batal</a><button class="btn primary" type="submit">Simpan Perubahan&nbsp; →</button></div></div></form>

<div class="section"><div class="image-section-head"><div><div class="section-title" style="margin-bottom:6px">Project Images</div><div class="helper">Upload beberapa image sekaligus. Drag & drop thumbnail untuk mengatur urutan.</div></div><span class="muted">{{ $project->images->count() }} image</span></div>
<form id="imageUploadForm" method="POST" action="{{ route('admin.projects.images.store',$project) }}" enctype="multipart/form-data">@csrf
<div class="image-drop" id="imageDrop"><strong>Drop image di sini atau klik untuk memilih</strong><span>JPG, JPEG, PNG, WEBP · maksimal 5 MB per image · bisa multiple</span><input class="hidden-input" id="imageInput" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple></div>
<div class="image-grid" id="newImageGrid"></div>
<div class="actions" style="margin-top:18px"><span class="required-note" id="selectedCount">Belum ada image baru.</span><button class="btn primary" id="uploadImagesBtn" type="submit" disabled>Upload Image →</button></div>
</form>

<div class="section" style="margin-top:22px"><div class="image-section-head"><div><div class="section-title" style="margin-bottom:6px">Urutan Image Tersimpan</div><div class="helper">Urutan ini yang dipakai untuk image project.</div></div></div>
<div class="image-grid" id="existingImageGrid">
@forelse($project->images as $image)
<div class="image-item" draggable="true" data-image-id="{{ $image->id }}"><img class="image-thumb" src="{{ Storage::url($image->path) }}" alt="{{ $image->original_name ?: 'Project image' }}"><div class="image-meta"><span class="image-order"></span><span class="image-name" title="{{ $image->original_name }}">{{ $image->original_name ?: 'Image' }}</span></div><form method="POST" action="{{ route('admin.project-images.destroy',$image) }}" class="delete-image-form" onsubmit="return confirm('Hapus image ini?')">@csrf @method('DELETE')<button class="image-remove" type="submit" title="Hapus">×</button></form></div>
@empty
<div class="empty-images" style="grid-column:1/-1">Belum ada image untuk project ini.</div>
@endforelse
</div></div>
</div></div></div>

@stack('scripts')
<script>
(() => {
    const drop = document.getElementById('imageDrop');
    const input = document.getElementById('imageInput');
    const grid = document.getElementById('newImageGrid');
    const count = document.getElementById('selectedCount');
    const uploadBtn = document.getElementById('uploadImagesBtn');
    const existing = document.getElementById('existingImageGrid');
    let selectedFiles = [];
    let dragNew = null;
    let dragExisting = null;

    function renderNewFiles() {
        grid.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'image-item';
            item.draggable = true;
            item.dataset.index = index;
            const img = document.createElement('img');
            img.className = 'image-thumb';
            img.alt = file.name;
            img.src = URL.createObjectURL(file);
            const meta = document.createElement('div');
            meta.className = 'image-meta';
            meta.innerHTML = `<span class="image-order">${index + 1}</span><span class="image-name" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</span>`;
            const remove = document.createElement('button');
            remove.type = 'button'; remove.className = 'image-remove'; remove.textContent = '×'; remove.title = 'Hapus dari pilihan';
            remove.addEventListener('click', () => { selectedFiles.splice(index, 1); syncInput(); renderNewFiles(); });
            item.append(img, meta, remove);
            item.addEventListener('dragstart', () => dragNew = index);
            item.addEventListener('dragover', e => e.preventDefault());
            item.addEventListener('drop', e => { e.preventDefault(); if (dragNew === null || dragNew === index) return; const moved = selectedFiles.splice(dragNew, 1)[0]; selectedFiles.splice(index, 0, moved); dragNew = null; syncInput(); renderNewFiles(); });
            grid.appendChild(item);
        });
        count.textContent = selectedFiles.length ? `${selectedFiles.length} image siap di-upload. Drag thumbnail untuk mengatur urutan.` : 'Belum ada image baru.';
        uploadBtn.disabled = !selectedFiles.length;
    }

    function syncInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        input.files = dt.files;
    }

    function addFiles(files) {
        selectedFiles = [...selectedFiles, ...Array.from(files)];
        syncInput(); renderNewFiles();
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>\"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;',"'":'&#039;'}[char]));
    }

    drop.addEventListener('click', () => input.click());
    input.addEventListener('change', () => { addFiles(input.files); });
    ['dragenter','dragover'].forEach(type => drop.addEventListener(type, e => { e.preventDefault(); drop.classList.add('dragging'); }));
    ['dragleave','drop'].forEach(type => drop.addEventListener(type, e => { e.preventDefault(); drop.classList.remove('dragging'); }));
    drop.addEventListener('drop', e => addFiles(e.dataTransfer.files));

    function refreshExistingNumbers() {
        existing.querySelectorAll('.image-item[data-image-id]').forEach((item, index) => {
            item.querySelector('.image-order').textContent = index + 1;
        });
    }

    async function saveExistingOrder() {
        const ids = [...existing.querySelectorAll('.image-item[data-image-id]')].map(item => Number(item.dataset.imageId));
        if (!ids.length) return;
        const response = await fetch(@json(route('admin.projects.images.reorder', $project)), {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':@json(csrf_token()),'Accept':'application/json'},
            body: JSON.stringify({ids})
        });
        if (!response.ok) alert('Urutan image gagal disimpan.');
    }

    existing.querySelectorAll('.image-item[data-image-id]').forEach(item => {
        item.addEventListener('dragstart', () => { dragExisting = item; item.style.opacity = '.45'; });
        item.addEventListener('dragend', async () => { item.style.opacity = ''; dragExisting = null; refreshExistingNumbers(); await saveExistingOrder(); });
        item.addEventListener('dragover', e => {
            e.preventDefault();
            if (!dragExisting || dragExisting === item) return;
            const rect = item.getBoundingClientRect();
            const before = e.clientY < rect.top + rect.height / 2;
            if (before) existing.insertBefore(dragExisting, item); else existing.insertBefore(dragExisting, item.nextSibling);
            refreshExistingNumbers();
        });
    });
    refreshExistingNumbers();
})();
</script>
</body></html>
