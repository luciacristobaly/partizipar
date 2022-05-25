<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Partizipar')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <link href="https://unpkg.com/tailwindcss@%5E2/dist/tailwind.min.css" ref="stylesheet">

    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Dselect -->
    <link rel="stylesheet" href="css/dselect.css" />
    <style>
        .ative a {
            color: white;
            text-decoration: bold;
        }
        * {
            box-sizing: border-box;
        }

        .zoom:hover {
            -ms-transform: scale(1.25); /* IE 9 */
            -webkit-transform: scale(1.25); /* Safari 3-8 */
            transform: scale(1.25); 
        }

        .special-card {
            /* create a custom class so you 
            do not run into specificity issues 
            against bootstraps styles
            which tends to work better than using !important 
            (future you will thank you later)*/

            background-color: rgba(245, 245, 245, 0.4) ;
        }
    </style>

</head>
<body class="container bg-dark text-white">
    @include('partials.nav')

    @yield('content')
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('#datetimestartpicker').datetimepicker();
        $('#datetimeendpicker').datetimepicker();
    });
</script> 
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script> <!-- email --> 
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
</html>
