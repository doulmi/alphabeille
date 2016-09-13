@if($readables->currentPage() > 1)
    {{--<div >--}}
    <div class="prePage" ><a href="{{$readables->previousPageUrl()}}"><span class="glyphicon glyphicon-chevron-left pre-page-icon"></span></a></div>
@endif
{{--</div>--}}

@if($readables->currentPage() < $readables->lastPage())
    <div class="nextPage">
        <a href="{{$readables->nextPageUrl()}}"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
@endif

<div class="center">
    {!! $readables->links() !!}
</div>

<div class="Header"></div>