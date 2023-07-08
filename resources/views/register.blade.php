<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Akun</title>
</head>
<body>
    <form method="POST">
        @csrf
        <lable for="username">username :</label>
        <input type="text" name="username" id="username">

        <lable for="email">email :</label>
        <input type="text" name="email" id="email">

        <lable for="nomer hp :">nomer hp :</label>
        <input type="number" name="nomer" id="nomer hp :">

        <lable for="password">password :</label>
        <input type="password" name="password" id="password">

        <button type="submit">Daftar Akun</button>

        <br><br><br>

        <a href="{{ route('login') }}">Login</a>
    </form>
</body>
</html>