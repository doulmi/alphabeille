<div class="back-to-learn">
    <h3>@lang('labels.investYourself')</h3>
    <div class="ads-body">@lang('labels.adText')</div>
    <a href="{{url('menus')}}" class="btn btn-ads">@lang('labels.adBtn')</a><br/>
    @if(!Auth::check())
        <a href="{{url('login')}}" class="btn-ads-login">@lang('labels.loginAccount')</a>
    @endif
</div>