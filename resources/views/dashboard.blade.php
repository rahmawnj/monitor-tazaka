<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Monitor Tazaka</title>
    <style>
        body { margin:0; min-height:100vh; font-family:Inter,ui-sans-serif,system-ui,sans-serif; background:#07111f; color:#f8fafc; }
        .wrap { max-width:1200px; margin:auto; padding:40px 24px; }
        .top { display:flex; align-items:center; justify-content:space-between; gap:20px; }
        .eyebrow { color:#60a5fa; font-size:12px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
        h1 { margin:6px 0; font-size:34px; }
        .muted { color:#94a3b8; }
        .panel { margin-top:28px; padding:24px; border:1px solid #1e293b; border-radius:20px; background:#0f172a; }
        .logout { padding:10px 15px; border:1px solid #334155; border-radius:10px; background:transparent; color:#cbd5e1; cursor:pointer; }
        .logout:hover { background:#172033; }
        code { color:#93c5fd; }
    </style>
</head>
<body>
    <main class="wrap">
        <div class="top">
            <div>
                <div class="eyebrow">Monitor Tazaka</div>
                <h1>Executive Dashboard</h1>
                <div class="muted">Selamat datang, {{ session('user')->username }} ({{ session('user')->email }})</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit">Logout</button>
            </form>
        </div>

        <section class="panel">
            <h2>Login sudah aktif.</h2>
            <p class="muted">Dashboard project, chart, map Indonesia, dan realtime Reverb akan kita bangun di area ini.</p>
            <p class="muted">Identitas login tersedia sebagai <code>session('user')->username</code> dan <code>session('user')->email</code>.</p>
        </section>
    </main>
</body>
</html>
