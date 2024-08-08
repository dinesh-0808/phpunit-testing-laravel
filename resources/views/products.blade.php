

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if(auth()->user()->is_admin)
        <a class="btn btn-secondary" href={{ route('products.create') }}>create product</a>
    @endif
    @if(count($products)>0)
    <table>

        <thead>
            <td>name</td>
            <td>price</td>
            <td>edit</td>
            <td>delete</td>
        </thead>
        @foreach ($products as $product)
            <tr>

            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td><a href="{{ route('products.edit',$product->id) }}">edit</a></td>
            <td>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="delete">
                </form>
            </td>
            </tr>
        @endforeach

    </table>
    {{ $products->links() }}
    @else
    {{ __('product not found...') }}
    @endif
</body>
</html>
