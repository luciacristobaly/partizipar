<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Partizipar')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- Data tables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <link href="https://unpkg.com/tailwindcss@%5E2/dist/tailwind.min.css" ref="stylesheet">

    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .ative a {
            color: white;
            text-decoration: bold;
        }

        * {
            box-sizing: border-box;
        }

        .card-deck .card {
            height:200px;
            width: 200px;
            transition: transform .1s;
            flex-basis: 100%;
            position: relative;
            max-width: 250px;
        }

        .card-deck .card:hover {
            -ms-transform: scale(1.25); /* IE 9 */
            -webkit-transform: scale(1.25); /* Safari 3-8 */
            transform: scale(1.25); 
            position:initial;
            z-index:111;
        }

        @media (min-width: 576px) {
            .card-deck .card {
                flex-basis: calc(50% - 30px);
                /* #{$grid-gutter-width} */
            }
        }

        @media (min-width: 768px) {
            .card-deck .card {
                flex-basis: calc(33.33% - 30px);
            }
        }

        @media (min-width: 992px) {
            .card-deck .card {
                flex-basis: calc(25% - 30px);
            }
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card .content {
            position: absolute;
            bottom: 0;
            background: rgb(0, 0, 0);
            background: rgba(0, 0, 0, 0.5);
            color: #f1f1f1;
            width: 100%;
            height: 30%;
            object-fit: cover;
            text-size-adjust: 10px;
            padding-left: 10px;
            padding-right: 10px;
            padding-bottom: 10px;
            margin-left: 0px;
        }

        .special-card {
            /* create a custom class so you 
            do not run into specificity issues 
            against bootstraps styles
            which tends to work better than using !important 
            (future you will thank you later)*/

            background-color: rgba(245, 245, 245, 0.4) ;
        }

        .custom-select {
            position: relative;
            font-family: Arial;
            height: 34px;
            font-size: 13px;
        }

        .custom-select select {
            display: none;
        }

        .users-icon {
            position: relative;
            z-index: 1;
        }

        .footer {
            position: fixed;
            left: 20;
            bottom: 40px;
            width: 100%;
            color:white;
            text-align: left;
        }

        .accept-list-button {
            height: 100%;
            position: relative;
        }

        .center {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .modal-body div{float:left; width: 100%}
        .modal-body div p{float:left; width: 20%; font-weight: 600;}
        .modal-body div span{float:left; width: 80%}

        .modal.right .modal-dialog {
            position: fixed;
            margin: auto;
            width: 320px;
            height: 100%;
            -webkit-transform: translate3d(0%, 0, 0);
                -ms-transform: translate3d(0%, 0, 0);
                -o-transform: translate3d(0%, 0, 0);
                    transform: translate3d(0%, 0, 0);
        }

        .modal.right .modal-content {
            height: 100%;
            overflow-y: auto;
        }
        
        .modal.right .modal-body {
            padding: 15px 15px 80px;
        }
            
        .modal.right.fade .modal-dialog {
            right: -320px;
            -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
            -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
                -o-transition: opacity 0.3s linear, right 0.3s ease-out;
                    transition: opacity 0.3s linear, right 0.3s ease-out;
        }
        
        .modal.right.fade.in .modal-dialog {
            right: 0;
        }

    </style>

</head>
<body class="container bg-dark text-white">
    
        @include('partials.nav')

        @yield('content')
        
            
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $('#datetimestartpicker').datetimepicker();
                $('#datetimeendpicker').datetimepicker();
            });
            </script> 
        <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script> <!-- email --> 


        <script>
            jQuery(document).ready(function($) {
                $('#listsTable').DataTable({
                    searching: false,
                    responsive: true,
                    "autoWidth": false,
                });
                var table = $('#listsTable').DataTable();
                $('#listsTable tbody').on('click', 'tr', function () {
                    console.log('Modal opened');
                    $(".listname span").text(table.row(this).data()[0]);
                    $(".students span").text(table.row(this).data()[1]);
                    $("#showList").modal("show");
                });
            } );
        </script>
        
        @stack('scripts')
</body>
</html>
