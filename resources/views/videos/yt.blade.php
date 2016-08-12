{{--<iframe width="620" height="390" src="https://www.youtube.com/embed/OpMVGgwfMcA" frameborder="0"--}}
{{--allowfullscreen></iframe>--}}

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>--}}
{{--<button onclick="avancer()">Avancer</button>--}}
{{--<button onclick="reculer()">Reculer</button>--}}
{{--<script>--}}
{{--var video = $('video');--}}
{{--console.log(video);--}}
{{--function avancer() {--}}
{{--console.log(video.currentTime);--}}
{{--//        video.currentTime = 3;--}}
{{--}--}}

{{--function reculer() {--}}

{{--}--}}
{{--</script>--}}

<div id="video-placeholder"></div>
<button onclick="avancer()">Avancer</button>
<button onclick="reculer()">Reculer</button>

<script src="https://www.youtube.com/iframe_api"></script>
<script>
    var player;

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('video-placeholder', {
            width: 600,
            height: 400,
            videoId: 'Xa0Q0J5tOP0',
            playerVars: {
                color: 'white',
                playlist: 'taJ60kskkns,FG0fTKAqZ5g'
            },
            events: {
                onReady: initialize
            }
        });
    }

    function initialize() {

        // Update the controls on load
        updateTimerDisplay();
        updateProgressBar();

        // Clear any old interval.
        clearInterval(time_update_interval);

        // Start interval to update elapsed time display and
        // the elapsed part of the progress bar every second.
        time_update_interval = setInterval(function () {
            updateTimerDisplay();
            updateProgressBar();
        }, 1000)

    }

    function avancer() {
        player.seekTo(player.getCurrentTime() + 5);
    }

    function reculer() {
    }
</script>