<div class="container ">
    <div class="Header"></div>

    <div class="col-md-6">
        <div class="subscribe-pros">
            <h1 class="subscribe-pros__title">借助Alphabeille法语，您可以更快掌握一口流利的法语</h1>

            <div class="item">
                <span class="i icon">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_enjoy_grammar"></use>
                    </svg>
                </span>
                <h2 class="title">享受法语</h2>
                <p class="texts">
                    短视频，日记形式的小文章，脱口秀，Minitalk！<br>
                    让你在快乐中学习到纯真又地道的法语
                </p>
            </div>

            <div class="item">
                <span class="i icon">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_unlimited_access"></use>
                    </svg>
                </span>
                <h2 class="title">永远不会忘记你学过的知识</h2>
                <p class="texts">
                    学习历史和数据分析<br>
                    词汇保存和 PDF 下载
                </p>

            </div>

            <div class="item">
                <span class="i icon">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_never_forget"></use>
                    </svg>
                </span>
                <h2 class="title">随时随地享受无限访问权</h2>
                <p class="texts">
                    移动应用推出离线模式<br>
                    可访问网站所有内容及字幕
                </p>
            </div>

            <div class="item">
                <span class="i icon">
                    <svg>
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_instant_feedback"></use>
                    </svg>
                </span>
                <h2 class="title">外教一对一，让你学以致用</h2>
                <p class="texts">
                    平台数百位法语外教<br>
                    更加及时地让你体会到你的进步
                </p>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="subscribe-plans">
            <h2 class="subscribe-plans_title center">
                选择您的计划
            </h2>
            @foreach($menus as $i => $menu)
                @if($i == 1)
                    <a href="{{url('subscription/' . $menu->id)}}" class="subscribe-plan subscribe-plan--special">
                    <span class="subscribe-plan__special subscribe-plan__special--gold">
                        <span class="text">最热门</span>
                    </span>
                @else
                    <a href="{{url('subscription/' . $menu->id)}}" class="subscribe-plan">
                @endif
                <button class="plan-btn" href="">现在购买</button>
                <p class="plan-length">{{$menu->duration}} 个月</p>
                <p class="plan-price">
                    <span class="plan-price-value">{{$menu->price / $menu->duration}} 元</span>
                    <span class="plan-price-month">/月</span>
                </p>

                <p class="plan-price-legend">
                    总共 {{$menu->price}}元
                </p>
            </a>
            @endforeach
        </div>
    </div>
</div>