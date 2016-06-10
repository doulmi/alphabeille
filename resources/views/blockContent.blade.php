<div class="back-to-learn">
    <h3>@lang('labels.backToLearn')</h3>
    <div class="ads-body">@lang('labels.adText')</div>
    <button class="btn btn-ads">@lang('labels.adBtn') </button><br/>
    @if(!Auth::check())
    <a href="{{url('login')}}" class="btn-ads-login">@lang('labels.loginAccount')</a>
    @endif
</div>