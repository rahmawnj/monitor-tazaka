<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan PIN — Monitor Tazaka</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f4f6f8;
            color: #17202a;
        }
        .card {
            width: min(100%, 390px);
            padding: 34px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            box-shadow: 0 18px 50px rgba(0,0,0,.08);
            text-align: center;
        }
        h1 { margin: 0 0 8px; font-size: 25px; }
        p { margin: 0 0 26px; color: #6b7280; }
        input {
            width: 100%;
            padding: 15px 16px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            font-size: 24px;
            letter-spacing: 8px;
            text-align: center;
            outline: none;
        }
        input:focus { border-color: #111827; }
        button {
            width: 100%;
            margin-top: 14px;
            padding: 14px;
            border: 0;
            border-radius: 12px;
            background: #111827;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }
        .error { margin-top: 12px; color: #dc2626; font-size: 14px; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Monitor Tazaka</h1>
        <p>Masukkan PIN untuk membuka dashboard.</p>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <input
                type="password"
                name="pin"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="20"
                autocomplete="current-password"
                placeholder="••••••"
                autofocus
                required
            >
            <button type="submit">Masuk</button>
        </form>

        @error('pin')
            <div class="error">{{ $message }}</div>
        @enderror
    </main>
</body>
</html>
