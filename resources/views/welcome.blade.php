<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Happy Kids</title>

    <link rel="stylesheet" href="{{ url('/src/bootstrap/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/css/global.css') }}">
    <link rel="stylesheet" href="{{ url('/src/plugins/src/sweetalerts2/sweetalerts2.css?'.date('Ymdhis')) }}">

    @if ($display_type == 'portal')
    <link rel="stylesheet" href="{{ url('/css/main.css') }}">
    <link rel="stylesheet" href="{{ url('/css/app.css?'.date('Ymdhis')) }}">
    <link rel="stylesheet" href="{{ url('/src/assets/css/light/main.css?'.date('Ymdhis')) }}">
    <link rel="stylesheet" href="{{ url('/src/assets/css/light/scrollspyNav.css?'.date('Ymdhis')) }}">
    <link rel="stylesheet" href="{{ url('/src/plugins/src/table/datatable/datatables.css?'.date('Ymdhis')) }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ url('/src/assets/css/light/authentication/auth-cover.css?'.date('Ymdhis')) }}">
    <link rel="stylesheet" href="{{ url('/src/template_layout/plugins.css?'.date('Ymdhis')) }}">
    @else
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.0/css/all.min.css" integrity="sha512-3PN6gfRNZEX4YFyz+sIyTF6pGlQiryJu9NlGhu9LrLMQ7eDjNgudQoFDK3WSNAayeIKc6B8WXXpo4a7HqxjKwg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/landing.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/odometer.min.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/azino-icons.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ url('/front/css/main.css') }}">
    @endif
</head>
<body>
    <div id="app"></div>
    <script async defer src="{{ url('/js/app.js?'.date('Ymdhis')) }}"></script>
    <script async defer src="{{ url('/src/plugins/src/sweetalerts2/sweetalerts2.min.js') }}"></script>
    @if ($display_type == "portal")

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ url('/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ url('/src/plugins/src/sweetalerts2/sweetalerts2.min.js') }}"></script> --}}
    {{-- datatable --}}
    <script src="{{ url('/src/plugins/src/table/datatable/datatables.js') }}"></script>
    {{-- SUMMMMMERNOTE --}}
    <link href="{{ url('/src/plugins/src/summernote/summernote.min.css') }}" rel="stylesheet" />
    <link href="{{ url('/src/plugins/src/summernote/summernote-lite.min.css') }}" rel="stylesheet" />
    <script src="{{ url('/src/plugins/src/summernote/summernote-lite.min.js') }}"></script>
    {{-- SUMMMMMERNOTE --}}
    {{-- tagify --}}
    <script src="{{ url('/src/plugins/src/tagify/tagify.min.js') }}"></script>

    @else

    {{-- front Landing--}}
    <script src="{{ url('/front/js/jquery-3.5.1.min.js') }}"></script>
    <script async defer src="{{ url('/front/js/bootstrap.bundle.min.js') }}"></script>
    <script async defer src="{{ url('/front/js/swiper.min.js') }}"></script>
    <script async defer src="{{ url('/front/js/jquery.ajaxchimp.min.js') }}"></script>
    <script async defer src="{{ url('/front/js/bootstrap-select.min.js') }}"></script>
    <script async defer src="{{ url('/front/js/jquery.magnific-popup.min.js') }}"></script>
    <script async defer src="{{ url('/front/js/jquery.validate.min.js') }}"></script>
    <script async defer src="{{ url('/front/js/wow.js') }}"></script>
    <script async defer src="{{ url('/front/js/jquery.appear.min.js') }}"></script>

    <!-- template js -->
    <script async defer src="{{ url('/front/js/theme.js') }}"></script>

    @endif

</body>
</html>
