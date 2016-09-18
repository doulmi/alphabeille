$('body').click(function (event) {
var target = $(event.target);       // 判断自己当前点击的内容
if (!target.hasClass('popover')
&& !target.hasClass('pop')
&& !target.hasClass('popover-content')
&& !target.hasClass('audio-play-btn')
&& !target.hasClass('audio-icon')
&& !target.hasClass('popover-title')
&& !target.hasClass('glyphicon')
&& !target.hasClass('arrow')) {
$('.popover').popover('hide');      // 当点击body的非弹出框相关的内容的时候，关闭所有popover
$('.popover-content').html("@lang('labels.loading')");
}
});

var closeBtn = '<button type="button" id="close" class="close" onclick="$(\'.popver\').popover(\'hide\');">&times;</button>';

var activePopover = function () {
var word = $(this).html().trim().toLowerCase();
$('.popover').popover('hide');          // 当点击一个按钮的时候把其他的所有内容先关闭。
$('.popover-content').html("@lang('labels.loading')");

var result = localStorage.getItem('dict:fr:' + word);
if (result && result != '') {
    var audioBtn = '<button type="button" class="audio-play-btn" id="audioBtn"><img class="audio-icon" src="data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjMzIyRTMzIiBoZWlnaHQ9IjE4IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHdpZHR oPSIxOCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxwYXRoIGQ9Ik 0zIDl2Nmg0bDUgNVY0TDcgOUgzem0xMy41IDNjMC0xLjc3LTEuMDItMy4yOS0yLjUtNC4wM3Y4L jA1YzEuNDgtLjczIDIuNS0yLjI1IDIuNS00LjAyek0xNCAzLjIzdjIuMDZjMi44OS44NiA1IDMu NTQgNSA2Ljcxcy0yLjExIDUuODUtNSA2LjcxdjIuMDZjNC4wMS0uOTEgNy00LjQ5IDctOC43N3M tMi45OS03Ljg2LTctOC43N3oiLz4KICAgIDxwYXRoIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KPC9zdmc+"/></button>';

    //有缓存的情况下
    $(this).popover({
        placement: 'bottom', trigger: 'focus', delay: {show: 10, hide: 100}, html: true,
        title: function () {
        return $(this).html() + audioBtn + closeBtn;
        },
        content : function() {
        return result;
        }
    });
    $(this).popover('toggle');

    var src = localStorage.getItem('dict:fr:audio:' + word);

    var audio = $('<audio/>', {
        controls : 'controls'
    });

    $('<source/>').attr('src', src).appendTo(audio);

    //添加查询次数
    $.get("{{url('api/words')}}" + "/" + word + '/{{$readable->id . '/' . $type}}', function (response) {
        $('#audioBtn').click(function() {
        audio.appendTo('body');
        audio.trigger('play');
        });

        //add word to words list
        console.log(response);
        var words = vm.words;
        if(words.indexOf(word) < 0) {
        var newWord = {
            id: response['id'],
            word : {
                word : word,
                explication : response['msg']
            }
        };
        vm.words.push(newWord);
        }
    });

} else {
    //无缓存则从服务器获取信息
    var audioBtn = '<button type="button" class="audio-play-btn" id="audioBtn"><i class="glyphicon glyphicon-volume-up"></i></button>';
    $(this).popover({
    placement: 'bottom', trigger: 'focus', delay: {show: 10, hide: 100}, html: true,
    title: function () {
    return $(this).html() + audioBtn + closeBtn;
    },
    content : function() {
    $.get("{{url('api/words')}}" + "/" + word + '/{{$readable->id . '/' . $type}}', function (response) {
        $(".popover-content").html(response['msg']);
        //                                audioBtn = '<button type="button" class="audio-play-btn" onclick="playWordAudio(\'' + audio + '\')"><i class="glyphicon glyphicon-volume-up"></i></button>';
        console.log(response);

        if (response['status'] == 200) {
            localStorage.setItem('dict:fr:' + word, response['msg']);
            localStorage.setItem('dict:fr:audio:' + word, response['audio']);
            var src = response['audio'];

        var words = vm.words;
        if(words.indexOf(word) < 0) {
            var newWord = {
                id: response['id'],
                word : {
                    word : word,
                    explication : response['msg']
                }
            };
            vm.words.push(newWord);
        }
            var audio = $('<audio/>', {
            controls : 'controls'
            });

            $('<source/>').attr('src', src).appendTo(audio);
            $('#audioBtn').click(function() {
            audio.appendTo('body');
            audio.trigger('play');
        });
    }
    return response['msg'];
});
}
});

$(this).popover('toggle');          // 然后只把自己打开。
}
};

