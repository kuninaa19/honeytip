<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<p>
    {{ $greeting }} {{$name}}. Welcome Back~
    {{ $greeting }}

<ul>
    @foreach($items as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>

@if($itemCount = count($items))
    <p>There are {{ $itemCount }} items !</p>
@else
    <p>There is no item !</p>
    @endif
</p>
    <?php $items = []; ?>
    @forelse($items as $item)
        <p>The item is {{ $item }}</p>
    @empty
        <p>There is no item !</p>
    @endforelse
</body>
</html>

