<div class="container">
    <div class="margin-top"></div>
    <div class="Header"></div>
    <ul class="product-select row">
        <?php $imgs = ['mill', 'windmill', 'plant']; ?>
        @foreach($menus as $i =>$menu)
        <li>
            <h1>@lang('labels.' . $menu->name)</h1>
            <img src="http://o9dnc9u2v.bkt.clouddn.com/images/{{$imgs[$i]}}.png">
            <span class="price">@lang($menu->price)</span>
            @if($i == 1)
            <span class="featured">@lang('labels.featured')</span>
            @endif
            <p>@lang('labels.advantages.6months')</p>
            <div class="hidden-xs">
                <p>@lang('labels.advantages.20texts')</p>
                <p>@lang('labels.advantages.8talkshows')</p>
            </div>
            @if($menu->description != '')
                <p>@lang('labels.advantages.' . $menu->description)</p>
            @endif
            <a href='{{url('subscription/' . $menu->id)}}'>@lang('labels.buy')</a>
        </li>
        @endforeach
    </ul>
</div>