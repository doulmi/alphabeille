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
                                <div class="menu month-{{$menu->duration}} aniview" av-animation="slideInBottom">
                                    <h3 class="menu-title buy-{{$menu->duration}}-title">@lang('labels.' . $menu->name)</h3>
                                    <h3 class="menu-price menu-{{$menu->duration}}-price">
                                        <span class="symbol">￥</span><span
                                                class="price-label">@lang($menu->price)</span>
                                    </h3>
                                    <div class="buy-btn-div">
                                        <a href='{{url('subscription/' . $menu->id)}}'
                                           class="buy-btn buy-{{$menu->duration}}-btn">@lang('labels.buy')</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </div>
        </div>

        <div class="row center">
            <div class="Header"></div>
            <div class="Header"></div>
            <h4 class="">{{ trans('labels.joinus') }}</h4>
            <center>
                <img src="/img/placeholder.png" data-original="http://o9dnc9u2v.bkt.clouddn.com/AlphabeilleStudioQR.png"
                     class="Card-image" width="200px" height="200px">
            </center>
        </div>
    </div>
</div>