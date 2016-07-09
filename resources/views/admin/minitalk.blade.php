@extends('admin.index')

@section('content')
    @if($edit)
        <form role="form" action="{{url('admin/minitalks/' . $minitalk->id)}}" method="POST">
            <input type="hidden" name="_method" value="PUT"/>
    @else
        <form role="form" action="{{url('admin/minitalks')}}" method="POST">
    @endif
        {!! csrf_field() !!}

        <div class="form-group">
            <label for="title">@lang('labels.title')</label>
            @if($edit)
                <input name='title' type="text" class="form-control" id="title"
                       value="{{$minitalk->title}}"/>
            @else
                <input name='title' type="text" class="form-control" id="title"/>
            @endif
        </div>

        <div class="form-group">
            <label for="avatar">@lang('labels.avatar')</label>
            @if($edit)
                <input type="text" class="form-control" id="avatar" name="avatar"
                       value="{{$minitalk->avatar}}"/>
            @else
                <input type="text" class="form-control" id="avatar" name="avatar"/>
            @endif
        </div>

        <div class="form-group">
            <label for="audio_url">@lang('labels.audio_url')</label>
            @if($edit)
                <input type="text" class="form-control" id="audio_url" name="audio_url"
                       value="{{$minitalk->audio_url}}"/>
            @else
                <input type="text" class="form-control" id="audio_url" name="audio_url"/>
            @endif
        </div>

        <div class="form-group">
            <label for="keywords">@lang('labels.keywords')</label>
            @if($edit)
                <input type="text" class="form-control" id="keywords" name="keywords"
                       value="{{$minitalk->keywords}}"/>
            @else
                <input type="text" class="form-control" id="keywords" name="keywords"/>
            @endif
        </div>

        <div class="form-group">
            <label for="duration">@lang('labels.duration')</label>
            @if($edit)
                <input type="text" class="form-control" id="duration" name="duration"
                       value="{{$minitalk->duration}}"/>
            @else
                <input type="text" class="form-control" id="duration" name="duration"/>
            @endif
        </div>

        <div class="form-group">
            <label for="free">@lang('labels.free')</label>
            @if($edit)
                <input type="text" class="form-control" id="free" name="free" value="{{$minitalk->free}}"/>
            @else
                <input type="text" class="form-control" id="free" name="free" value="0"/>
            @endif
        </div>

        <div class="form-group">
            <label for="likes">@lang('labels.likes')</label>
            @if($edit)
                <input type="text" class="form-control" id="likes" name="likes"
                       value="{{$minitalk->likes}}"/>
            @else
                <input type="text" class="form-control" id="likes" name="likes" value="0"/>
            @endif
        </div>

        <div class="form-group">
            <label for="download_url">@lang('labels.download_url')</label>
            @if($edit)
                <input type="text" class="form-control" id="download_url" name="download_url"
                       value="{{$minitalk->download_url}}"/>
            @else
                <input type="text" class="form-control" id="download_url" name="download_url"/>
            @endif
        </div>

        <div class="form-group">
            <label for="wechat_part">@lang('labels.wechatPart')</label>
            @if($edit)
                <textarea class="name-input form-control" rows="10" id="wechat_part"
                          name="wechat_part">{{$minitalk->wechat_part}}</textarea>
            @else
                <textarea class="name-input form-control" rows="10" id="wechat_part"
                          name="wechat_part"></textarea>
            @endif
        </div>

        <div class="form-group">
            <label for="content">@lang('labels.content')</label>
            @if($edit)
                <textarea class="name-input form-control" rows="10" id="content"
                          name="content" v-model="content"></textarea>
            @else
                <textarea class="name-input form-control" rows="10" id="content"
                          name="content" v-model="content"></textarea>
            @endif
        </div>

            <div class="form-group">
                <label for="description">@lang('labels.description')</label>
                @if($edit)
                    <textarea class="name-input form-control" rows="10" id="description"
                              name="description">@{{ content.substring(0, 100) + "..." }}</textarea>
                @else
                    <textarea class="name-input form-control" rows="10" id="description"
                              name="description">@{{ content.substring(0, 100) + "..." }}</textarea>
                @endif
            </div>


        <button type="submit" class="btn btn-primary pull-right">@lang('labels.submit')</button>
    </form>
@endsection

            @section('otherjs')
                <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
                <script>
                    $vm = new Vue({
                        el : 'body',
                        data : {
                            content : '{{$edit ? $minitalk->content: ''}}'
                        }
                    });
                </script>
@endsection
