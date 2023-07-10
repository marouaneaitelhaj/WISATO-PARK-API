<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>
    <form method="POST" action="/profile" enctype="multipart/form-data">
        @csrf
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="{{ $user->name }}"><br><br>
        
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="{{ $user->username }}"><br><br>
        
        <label for="gender">Gender</label>
        <input type="text" id="gender" name="gender" value="{{ $user->gender }}"><br><br>
        
        <label for="city">City</label>
        <input type="text" id="city" name="city" value="{{ $user->city }}"><br><br>
        
        <label for="cin">CIN</label>
        <input type="text" id="cin" name="cin" value="{{ $user->cin }}"><br><br>
        
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="{{ $user->phone }}"><br><br>
        
        <label for="image">Image</label>
        <input type="file" id="image" name="image"><br><br>
        
        <button type="submit">Update</button>
    </form>
</body>
</html>
