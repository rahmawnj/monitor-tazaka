<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Monitor Tazaka</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #07111f;
            color: #f8fafc;
        }
        .glow { position: fixed; width: 420px; height: 420px; border-radius: 50%; filter: blur(100px); background: rgba(59,130,246,.18); top: -160px; right: -100px; }
        .card {
            position: relative;
            width: min(420px, 100%);
            padding: 36px;
            border: 1px solid rgba(148,163,184,.18);
            border-radius: 24px;
            background: rgba(15,23,42,.88);
            box-shadow: 0 30px 80px rgba(0,0,0,.45);
            backdrop-filter: blur(18px);
        }
        .brand { margin-bottom: 30px; }
        .brand small { color: #60a5fa; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        h1 { margin: 8px 0 8px; font-size: 30px; }
        .subtitle { margin: 0; color: #94a3b8; }
        label { display: block; margin: 20px 0 8px; color: #cbd5e1; font-size: 14px; font-weight: 600; }
        input { width: 100%; padding: 13px 14px; border: 1px solid #334155; border-radius: 12px; outline: none; background: #0b1729; color: white; }
        input:focus { border-color: #60a5fa; box-shadow: 0 0 0 3px rgba(96,165,250,.12); }
        button { width: 100%; margin-top: 24px; padding: 13px 16px; border: 0; border-radius: 12px; background: #2563eb; color: white; font-weight: 700; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { margin-top: 16px; padding: 12px; border-radius: 10px; background: rgba(239,68,68,.1); color: #fca5a5; font-size: 14px; }
    </style>
</head>
<body>
    <div class="glow"></div>
    <main class="card">
        <div class="brand">
            <small>Monitor Tazaka</small>
            <h1>Executive Login</h1>
            <p class="subtitle">Masuk untuk mengelola project monitoring.</p>
        </div>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="login">Username / Email</label>
            <input id="login" type="text" name="login" value="{{ old('login') }}" autocomplete="username" required autofocus>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required>

            <button type="submit">Masuk ke Dashboard</button>
        </form>
    </main>
</body>
</html>
