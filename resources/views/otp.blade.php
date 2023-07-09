<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Verify</title>
</head>
<body>
    @if (session()->has('error'))
        <strong style="color: red;">{{ session('error') }}</strong>
    @endif
    <form method="POST">
        @csrf
        <lable for="token">Token OTP :</label>
        <input type="text" name="token" id="token">
        <button type="submit">Verify</button>

        <br><br><br>

        <a href="/otp/verify/{{ $nomer }}">Kirim Ulang Token</a>
        @if ($retries <= 0)
            <label>Please try again after {{ $seconds }} seconds.</label>
        @endif
    </form>
</body>
</html>