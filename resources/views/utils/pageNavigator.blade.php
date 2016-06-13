@if($pages->currentPage() > 1)
    {{--<div >--}}
    <div class="prePage" ><a href="{{$pages->previousPageUrl()}}" class="glyphicon glyphicon-chevron-left pre-page-icon"></a></div>
@endif
{{--</div>--}}

@if($pages->currentPage() < $pages->lastPage())
    <div class="nextPage">
        <a href="{{$pages->nextPageUrl()}}"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
@endif

<div class="center">
    {!! $pages->links() !!}
</div>