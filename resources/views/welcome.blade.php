<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>laravel wa + zoom</title>
</head>
<body>
    @if (session()->has('success'))
        <strong style="color: red;">{{ session('success') }}</strong>
    @endif
    <form action="" method="POST">
        @csrf
        <lable for="username">username :</label>
        <input type="text" name="username" id="username">

        <lable for="password">password :</label>
        <input type="password" name="password" id="password">

        <button type="submit">Login</button>

        <br><br><br>

        <a href="{{ route('register') }}">Daftar akun</a>
    </form>
</body>
</html>