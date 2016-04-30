<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

</head>
<body>
    <audio src="/mp3/juicy.mp3" preload="auto" />
    <script src="/js/audio.min.js"></script>
    <script>
        audiojs.events.ready(function() {
            var as = audiojs.createAll();
        });
    </script>
</body>
</html>