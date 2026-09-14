<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management — Monitor Tazaka</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#0b1020;color:#eef2ff;font-family:Inter,ui-sans-serif,system-ui,sans-serif}button,input,select,textarea{font:inherit}a{text-decoration:none;color:inherit}.wrap{max-width:1450px;margin:auto;padding:32px}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:28px}.eyebrow{color:#7dd3fc;font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.title{font-size:30px;font-weight:800;margin:5px 0}.muted{color:#94a3b8}.actions{display:flex;gap:10px}.btn{border:0;border-radius:10px;padding:11px 16px;background:#1e293b;color:#fff;cursor:pointer;font-weight:700}.btn.primary{background:#2563eb}.btn.danger{background:#7f1d1d}.grid{display:grid;grid-template-columns:440px 1fr;gap:22px;align-items:start}.card{background:#111827;border:1px solid #1f2937;border-radius:18px;padding:22px;box-shadow:0 18px 50px #0003}.card h2{font-size:18px;margin:0 0 18px}.field{margin-bottom:14px}.field label{display:block;font-size:12px;color:#a7b3c7;margin-bottom:7px;font-weight:700}.field label span{color:#f87171}.control{width:100%;background:#0b1222;border:1px solid #263246;border-radius:10px;padding:11px 12px;color:#fff;outline:none;transition:.2s}.control:focus{border-color:#38bdf8;box-shadow:0 0 0 3px #38bdf81c}.control::placeholder{color:#526078}.row{display:grid;grid-template-columns:1fr 1fr;gap:12px}.coords{display:grid;grid-template-columns:1fr 1fr;gap:12px}.section{border-top:1px solid #1f2937;padding-top:17px;margin-top:17px}.section-title{font-size:12px;color:#7dd3fc;font-weight:800;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px}.project{border:1px solid #263246;background:#0d1424;border-radius:14px;padding:17px;margin-bottom:12px}.project-head{display:flex;justify-content:space-between;gap:12px}.project-name{font-weight:800;font-size:16px}.badge{font-size:11px;border-radius:999px;padding:5px 9px;background:#172554;color:#93c5fd;white-space:nowrap}.meta{font-size:12px;color:#94a3b8;margin-top:5px}.progress{height:8px;background:#1e293b;border-radius:99px;overflow:hidden;margin:14px 0 8px}.bar{height:100%;background:#38bdf8}.project-actions{display:flex;gap:8px;margin-top:13px}.project-actions form{margin:0}.empty{padding:40px;text-align:center;color:#64748b}.alert{padding:13px 16px;border-radius:12px;margin-bottom:18px;background:#0f1f32;border:1px solid #1e3a5f}.alert.success{color:#86efac}.alert.error{color:#fca5a5}.error{display:block;color:#fca5a5;font-size:11px;margin-top:5px}.helper{font-size:11px;color:#64748b;margin-top:5px}@media(max-width:1000px){.grid{grid-template-columns:1fr}.wrap{padding:18px}}@media(max-width:600px){.row,.coords{grid-template-columns:1fr}.top{align-items:flex-start;gap:15px;flex-direction:column}}
    </style>
    @stack('styles')
</head>
<body>
<div class="wrap">
    <div class="top">
        <div>
            <div class="eyebrow">Monitor Tazaka</div>
            <div class="title">Project Management</div>
            <div class="muted">Kelola data project yang tampil di monitor kantor.</div>
        </div>
        <div class="actions"><a class="btn" href="{{ route('monitor') }}" target="_blank">Buka Monitor ↗</a></div>
    </div>

    @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert error">Periksa kembali input yang bertanda merah.</div>@endif

    <div class="grid">
        <div class="card">
            <h2>Tambah Project</h2>
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
                    <x-forms.input label="Progress" name="progress" type="number" min="0" max="100" value="0" required />
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

                <button class="btn primary" type="submit" style="width:100%;margin-top:4px">Simpan Project</button>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Project ({{ $projects->count() }})</h2>
            @forelse($projects as $project)
                <div class="project">
                    <div class="project-head">
                        <div>
                            <div class="project-name">{{ $project->name }}</div>
                            <div class="meta">{{ $project->client }} · {{ str_replace('_', ' ', ucfirst($project->project_type)) }}</div>
                        </div>
                        <span class="badge">{{ $project->progress }}%</span>
                    </div>
                    <div class="progress"><div class="bar" style="width:{{ $project->progress }}%"></div></div>
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
@stack('scripts')
</body>
</html>
