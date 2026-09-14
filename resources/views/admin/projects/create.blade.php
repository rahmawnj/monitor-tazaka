<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Project — Tazaka Elektrik Mandiri</title>
    <link rel="icon" type="image/png" href="https://tazaka.co.id/assets/logo-tazaka-PPqkEZXu.png">
    <style>*{box-sizing:border-box}body{margin:0;background:#0b1020;color:#eef2ff;font-family:Inter,ui-sans-serif,system-ui,sans-serif}.wrap{max-width:900px;margin:auto;padding:32px}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}.brand{display:flex;align-items:center;gap:14px}.brand-logo{width:50px;height:50px;object-fit:contain;border-radius:11px;background:#fff;padding:5px}.brand-name{font-size:13px;font-weight:800;color:#e2e8f0}.eyebrow{color:#7dd3fc;font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.title{font-size:30px;font-weight:800;margin:5px 0}.muted{color:#94a3b8}.card{background:#111827;border:1px solid #1f2937;border-radius:18px;padding:24px;box-shadow:0 18px 50px #0003}.btn{display:inline-block;border:0;border-radius:10px;padding:11px 16px;background:#1e293b;color:#fff;cursor:pointer;font-weight:700;text-decoration:none}.btn.primary{background:#2563eb}.actions{display:flex;gap:10px;justify-content:flex-end;margin-top:22px}.row,.coords{display:grid;grid-template-columns:1fr 1fr;gap:12px}.section{border-top:1px solid #1f2937;padding-top:17px;margin-top:17px}.section-title{font-size:12px;color:#7dd3fc;font-weight:800;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px}.helper{font-size:11px;color:#64748b;margin-top:5px}@media(max-width:600px){.wrap{padding:18px}.row,.coords{grid-template-columns:1fr}.top{align-items:flex-start;gap:12px;flex-direction:column}.brand-logo{width:44px;height:44px}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="top"><div class="brand"><img class="brand-logo" src="https://tazaka.co.id/assets/logo-tazaka-PPqkEZXu.png" alt="Logo Tazaka"><div><div class="brand-name">Tazaka Elektrik Mandiri</div><div class="eyebrow">Project Management</div><div class="title">Tambah Project</div><div class="muted">Buat project baru yang akan tampil di monitor.</div></div></div><a class="btn" href="{{ route('admin.projects.index') }}">← Kembali</a></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.projects.store') }}">@csrf
            <x-forms.input label="Nama Project" name="name" placeholder="Contoh: Office Network Upgrade" required />
            <x-forms.input label="Client" name="client" placeholder="Nama perusahaan / client" required />
            <div class="row"><x-forms.select label="Project Type" name="project_type" required><option value="">Pilih tipe project</option><option value="tazaka_order" @selected(old('project_type')==='tazaka_order')>Tazaka Order</option><option value="subcontract" @selected(old('project_type')==='subcontract')>Subcontract</option><option value="external" @selected(old('project_type')==='external')>External</option></x-forms.select><x-forms.input label="Target Completion" name="target_completion_date" type="date" /></div>
            <x-forms.input label="Project Month" name="project_month" type="month" />
            <div class="section"><div class="section-title">Project Detail</div><x-forms.textarea label="Description" name="description" placeholder="Tulis deskripsi project..." /><x-forms.textarea label="Notes" name="notes" placeholder="Tulis catatan project..." /></div>
            <div class="section"><div class="section-title">Location</div><x-forms.input label="Location" name="location" placeholder="Contoh: Bandung, West Java" /><div class="coords"><x-forms.input label="Latitude" name="lat" type="number" step="any" min="-90" max="90" placeholder="-6.9175" /><x-forms.input label="Longitude" name="lng" type="number" step="any" min="-180" max="180" placeholder="107.6191" /></div><div class="helper">Isi latitude & longitude kalau project ingin ditampilkan sebagai marker di peta.</div></div>
            <div class="actions"><a class="btn" href="{{ route('admin.projects.index') }}">Batal</a><button class="btn primary" type="submit">Simpan Project</button></div>
        </form>
    </div>
</div>
</body>
</html>
