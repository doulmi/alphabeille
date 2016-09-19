{{--<div class="back-to-learn">--}}
<div class="blockcontent">
    {{--<h3>@lang('labels.investYourself')</h3>--}}
    {{--<div class="ads-body">@lang('labels.adText')</div>--}}
    @if(Auth::check())
        {{--<img src="http://o9dnc9u2v.bkt.clouddn.com/images/subtitle.png" alt="">--}}
    @else
        <img src="http://o9dnc9u2v.bkt.clouddn.com/images/subtitle.png" alt="">
    @endif
    {{--<a href="{{url('menus')}}" class="btn btn-ads hidden-xs">@lang('labels.adBtn')</a><br/>--}}
    {{--    <a href="{{url('menus')}}" class="btn btn-ads visible-xs">@lang('labels.adBtnShort')</a><br/>--}}
    @if(Auth::check())
        <div class="ads-btns-login">
            <div class="">@lang('labels.freeTimeUsedOut')</div>
            <a href="{{url('menus')}}" class="btn btn-ads">@lang('labels.adBtnShort')</a><br/>
        </div>
    @else
        <div class="ads-btns">
            <a href="{{url('login')}}" class="btn btn-ads">@lang('labels.loginRightNow')</a><br/>
        </div>
    @endif
</div>