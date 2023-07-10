<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="/login" id="login">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>
