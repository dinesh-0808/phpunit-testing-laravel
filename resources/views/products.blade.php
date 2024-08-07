

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if(count($products)>0)
    <table>

        <thead>
            <td>name</td>
            <td>price</td>
        </thead>
        @foreach ($products as $product)
            <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            </tr>
        @endforeach
    </table>
    @else
    {{ __('product not found...') }}
    @endif
</body>
</html>
