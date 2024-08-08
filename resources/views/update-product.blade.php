<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>product update</title>
</head>
<body>
    <form action={{ route('products.update',$product->id) }} method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $product->id }}">
        <input type="text" name="name" value="{{ $product->name }}" placeholder="enter product here">
        <input type="text" name="price" value="{{ $product->price }}" placeholder="enter amount here">
        <input type="submit" value="submit">
    </form>
</body>
</html>
