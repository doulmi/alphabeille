<div class="beach">
    <ul>
        <li class="layer umbra"><img src="/img/umbra.png"></li>
    </ul>

    <div class="Card-Collection Menus">
        <div class="row">
        @foreach($menus as $i =>$menu)
            @if($i == 0)
            <div class="col-md-3 col-md-offset-3 col-sm-4 col-sm-offset-0 col-xs-6">
            @else
            <div class="col-md-3 col-sm-4 col-xs-6">
            @endif
                <div class="menu month-{{$menu->duration}} aniview">
                    <h3 class="menu-title buy-{{$menu->duration}}-title">@lang('labels.' . $menu->name)</h3>

                    <h3 class="menu-price menu-{{$menu->duration}}-price">
                        <span class="symbol">￥</span><span class="price-label">@lang($menu->price)</span>
                    </h3>
                    <ul class="menu-advantages advantages-{{$menu->duration}}">
                        @foreach($menu->advantages as $advantage)
                            <li>@lang('labels.' . $advantage)</li>
                        @endforeach
                    </ul>

                    <div class="buy-btn-div">
                        <a href='{{url('subscript/' . $menu->id)}}' class="buy-btn buy-{{$menu->duration}}-btn">@lang('labels.buy')</a>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        </div>

        <div class="row center">
            <h2 class="">{{ trans('labels.joinus') }}</h2>
            <ul class="advantages">
                <li>
                    优势1
                </li>
                <li>
                    优势2
                </li>
                <li>
                    优势3
                </li>
            </ul>
        </div>
    </div>
</div>