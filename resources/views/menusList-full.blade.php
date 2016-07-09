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
                                <div class="menu {{$menu->name}}">
                                    <h3 class="menu-title {{$menu->name}}-title">@lang('labels.' . $menu->name)</h3>
                                    <h3 class="menu-price {{$menu->name}}-price">
                                        <span class="symbol">￥</span><span
                                                class="price-label">@lang($menu->price)</span>
                                    </h3>
                                    <div class="advantages {{$menu->name}}-advantages">
                                        <p>@lang('labels.advantages.6months')</p>
                                        <p>@lang('labels.advantages.20texts')</p>
                                        <p>@lang('labels.advantages.8talkshows')</p>
                                        @if($menu->description != '')
                                            <p>@lang('labels.advantages.' . $menu->description)</p>
                                        @endif
                                    </div>
                                    <div class="buy-btn-div">
                                        <a href='{{url('subscription/' . $menu->id)}}'
                                           class="buy-btn {{$menu->name}}-btn">@lang('labels.buy')</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </div>
        </div>

        <div class="row center">
            <div class="Header"></div>
            <div class="col-md-offset-1 col-md-10">
                <h4 class="joinus ">{{ trans('labels.joinus') }}</h4>
                <center>
                    <img src="/img/placeholder.png" data-original="http://o9dnc9u2v.bkt.clouddn.com/ASqr.png"
                         class="Card-image qrcode-small" >
                </center>
            </div>
        </div>
    </div>
</div>