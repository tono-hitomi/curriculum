<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>利用停止のお知らせ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .message-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; }
    </style>
</head>
<body>

<div class="message-box">
    <p class="fs-5 mb-4">
        このアカウントは利用停止されている為、<br>
        利用することができません。
    </p>
    <a href="{{ route('login') }}" class="btn btn-outline-primary">ログイン画面へ</a>
</div>

</body>
</html>