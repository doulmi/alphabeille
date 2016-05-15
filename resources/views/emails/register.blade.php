<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Langlango</title>
</head>
<body>
    <a href=" {{ url('register/confirmation/'. $confirmation_code) }}">@lang('labels.confirmEmail')</a>
</body>
</html>