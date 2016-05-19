<div class="back-to-learn">
    <h3>@lang('labels.backToLearn')</h3>
    <div class="ads-body">@lang('labels.adText')</div>
    <a class="btn btn-ads">@lang('labels.adBtn') </a><br/>
    @if(!Auth::check())
    <a class="btn-ads-login">@lang('labels.loginAccount')</a>
    @endif
</div>