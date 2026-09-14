<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management — Monitor Tazaka</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#0b1020;color:#eef2ff;font-family:Inter,ui-sans-serif,system-ui,sans-serif}a{text-decoration:none;color:inherit}.wrap{max-width:1400px;margin:auto;padding:32px}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:28px}.eyebrow{color:#7dd3fc;font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.title{font-size:30px;font-weight:800;margin:5px 0}.muted{color:#94a3b8}.actions{display:flex;gap:10px}.btn{border:0;border-radius:10px;padding:11px 16px;background:#1e293b;color:#fff;cursor:pointer;font-weight:700}.btn.primary{background:#2563eb}.btn.danger{background:#7f1d1d}.grid{display:grid;grid-template-columns:380px 1fr;gap:22px}.card{background:#111827;border:1px solid #1f2937;border-radius:18px;padding:22px;box-shadow:0 18px 50px #0003}.card h2{font-size:18px;margin:0 0 18px}.field{margin-bottom:13px}.field label{display:block;font-size:12px;color:#94a3b8;margin-bottom:6px}.field input,.field select,.field textarea{width:100%;background:#0b1222;border:1px solid #263246;border-radius:9px;padding:10px;color:#fff;outline:none}.field textarea{min-height:80px;resize:vertical}.row{display:grid;grid-template-columns:1fr 1fr;gap:10px}.project{border:1px solid #263246;background:#0d1424;border-radius:14px;padding:16px;margin-bottom:12px}.project-head{display:flex;justify-content:space-between;gap:12px}.project-name{font-weight:800}.badge{font-size:11px;border-radius:999px;padding:5px 9px;background:#172554;color:#93c5fd}.badge.completed{background:#052e16;color:#86efac}.badge.on_hold{background:#422006;color:#fdba74}.meta{font-size:12px;color:#94a3b8;margin-top:5px}.progress{height:7px;background:#1e293b;border-radius:99px;overflow:hidden;margin:14px 0 8px}.bar{height:100%;background:#38bdf8}.project-actions{display:flex;gap:8px;margin-top:12px}.empty{padding:40px;text-align:center;color:#64748b}
        @media(max-width:900px){.grid{grid-template-columns:1fr}.wrap{padding:18px}.top{align-items:flex-start;gap:15px;flex-direction:column}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <div><div class="eyebrow">Monitor Tazaka</div><div class="title">Project Management</div><div class="muted">Kelola data yang tampil realtime di monitor kantor.</div></div>
        <div class="actions"><a class="btn" href="{{ route('monitor') }}" target="_blank">Buka Monitor</a></div>
    </div>

    @if(session('success'))<div class="card" style="margin-bottom:18px;color:#86efac">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="card" style="margin-bottom:18px;color:#fca5a5">{{ $errors->first() }}</div>@endif

    <div class="grid">
        <div class="card">
            <h2>Tambah Project</h2>
            <form method="POST" action="{{ route('admin.projects.store') }}">
                @csrf
                <div class="field"><label>Nama Project</label><input name="name" required></div>
                <div class="field"><label>Client</label><input name="client_name" required></div>
                <div class="row"><div class="field"><label>Mulai</label><input type="date" name="started_at"></div><div class="field"><label>Target</label><input type="date" name="target_date"></div></div>
                <div class="row"><div class="field"><label>Progress (%)</label><input type="number" name="progress" min="0" max="100" value="0" required></div><div class="field"><label>Status</label><select name="status"><option value="running">Running</option><option value="completed">Completed</option><option value="on_hold">On Hold</option></select></div></div>
                <div class="field"><label>Deskripsi</label><textarea name="description"></textarea></div>
                <div class="field"><label>Catatan</label><textarea name="notes"></textarea></div>
                <button class="btn primary" type="submit">Simpan Project</button>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Project ({{ $projects->count() }})</h2>
            @forelse($projects as $project)
                <div class="project">
                    <div class="project-head"><div><div class="project-name">{{ $project->name }}</div><div class="meta">{{ $project->client_name }}</div></div><span class="badge {{ $project->status }}">{{ $project->status_label }}</span></div>
                    <div class="progress"><div class="bar" style="width:{{ $project->progress }}%"></div></div>
                    <div class="meta">Progress {{ $project->progress }}% · Target {{ $project->target_date?->format('d M Y') ?? '-' }}</div>
                    <div class="project-actions">
                        <form method="POST" action="{{ route('admin.projects.update', $project) }}" style="display:flex;gap:8px;flex:1">@csrf @method('PUT')<input type="hidden" name="name" value="{{ $project->name }}"><input type="hidden" name="client_name" value="{{ $project->client_name }}"><input type="hidden" name="started_at" value="{{ $project->started_at?->format('Y-m-d') }}"><input type="hidden" name="target_date" value="{{ $project->target_date?->format('Y-m-d') }}"><input type="hidden" name="progress" value="{{ $project->progress }}"><input type="hidden" name="status" value="{{ $project->status }}"><input type="hidden" name="description" value="{{ $project->description }}"><input type="hidden" name="notes" value="{{ $project->notes }}"><button class="btn" type="submit">Simpan ulang</button></form>
                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Hapus project ini?')">@csrf @method('DELETE')<button class="btn danger" type="submit">Hapus</button></form>
                    </div>
                </div>
            @empty
                <div class="empty">Belum ada project.</div>
            @endforelse
        </div>
    </div>
</div>
</body>
</html>
