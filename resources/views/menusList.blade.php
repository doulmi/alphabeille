<div class="container ">
    <div class="Header"></div>

    <div class="col-md-6">
        <div class="subscribe-pros">
            <h1 class="subscribe-pros__title">Alphabeille VIP特权</h1>

            <div class="item">
                <span class="i icon">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_enjoy_grammar"></use>
                    </svg>
                </span>
                <h2 class="title">看视频学法语</h2>
                <p class="texts">
                    短视频以生动活泼的方式学习地道法语表达<br/>
                    内容涉及动画，电影预告和短篇喜剧，新闻报道等生活的方方面面
                </p>
            </div>

            <div class="item">
                <span class="i icon">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_unlimited_access"></use>
                    </svg>
                </span>
                <h2 class="title">脱口秀</h2>
                <p class="texts">
                    真实法语对话，时尚的主题<br>
                    提供音频下载，帮助你利用碎片化时间离线学习
                </p>

            </div>

            <div class="item">
                <span class="i icon">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_never_forget"></use>
                    </svg>
                </span>
                <h2 class="title">单词查询功能</h2>
                <p class="texts">
                    可点击网站上任意生词获得字典解释和发音<br>
                    提供可下载的单词查询记录，帮你找出你的"难点单词"
                </p>
            </div>

            <div class="item">
                <span class="i icon">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_enjoy_grammar"></use>
                    </svg>
                </span>
                <h2 class="title">永不断更</h2>
                <p class="texts">
                    内容每天不断更新中<br/>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="subscribe-plans">
            <h2 class="subscribe-plans_title center">
                选择你的订阅
            </h2>
            @foreach($menus as $i => $menu)
                @if($i == 2)
                    <a href="{{url('alipay/pay/' . $menu->id)}}" class="subscribe-plan subscribe-plan--special">
                    <span class="subscribe-plan__special subscribe-plan__special--gold">
                        <span class="text">最热门</span>
                    </span>
                @else
                    <a href="{{url('alipay/pay/' . $menu->id)}}" class="subscribe-plan">
                @endif
                <button class="plan-btn" href="">现在购买</button>
                <p class="plan-length">@lang('labels.menu' . $menu->duration)</p>
                <p class="plan-price">
                    <span class="plan-price-value">{{$menu->price}}元</span>
                </p>
            </a>
            @endforeach
        </div>
    </div>
</div>