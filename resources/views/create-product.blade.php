<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>product create</title>
</head>
<body>
    <form action={{ route('products.store') }} method="POST">
        @csrf
        <input type="text" name="name" placeholder="enter product here">
        <input type="text" name="price" placeholder="enter amount here">
        <input type="submit" value="submit">
    </form>
</body>
</html>
