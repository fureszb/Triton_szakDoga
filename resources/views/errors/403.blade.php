<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Hozzáférés megtagadva</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css'>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div>
            <i class="fas fa-shield-alt" style="font-size:3rem;color:#ed1b24;margin-bottom:16px;display:block;text-align:center;"></i>
        </div>
        <p class="error-code">403</p>
        <p class="error-title">Hozzáférés megtagadva</p>
        <p class="error-desc">Nincs jogosultságod ennek az oldalnak az eléréséhez.</p>
        <a href="{{ url('/') }}" class="btn-save" style="text-decoration:none;">
            <i class="fas fa-home"></i> Vissza a kezdőlapra
        </a>
    </div>
</body>
</html>
