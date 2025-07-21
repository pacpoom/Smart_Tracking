@foreach($menu as $item)
    @include('layouts.partials._menu_item', ['item' => $item, 'level' => 0])
@endforeach
