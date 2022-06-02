<!DOCTYPE html>
@langrtl
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', app_name())">
    <meta name="author" content="@yield('meta_author', 'WDP Technologies Pvt. Ltd.')">
    @yield('meta')

    @stack('before-styles')

    <!-- Check if the language is set to RTL, so apply the RTL layouts -->
    <!-- Otherwise apply the normal LTR layouts -->


    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css" rel="stylesheet">


    {{ style(mix('css/backend.css')) }}
    {{ style('css/bootstrap.min.css') }}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" type="text/css">
    @stack('after-styles')

    {{ script('js/jquery.min.js') }}
    {{ script('js/popper.min.js') }}
    {{-- script('js/bootstrap.min.js') --}}
    {{ style('css/bootstrap-datetimepicker.min.css') }}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuL7xhKMuwdiSATLR02YbUWv-8o0b5_H8&libraries=places,drawing,geometry&language=en&callback=drawPolygon"></script>
 
    <!-- 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- recaptcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="app header-fixed sidebar-fixed aside-menu-off-canvas sidebar-lg-show">
    @include('backend.includes.header')

    <div class="app-body">
        @include('backend.includes.sidebar')

        <main class="main">
            @include('includes.partials.read-only')
            @include('includes.partials.logged-in-as')
            {{-- Breadcrumbs::render() --}}
            
            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="content-header">
                        @include('includes.partials.messages')
                        @yield('page-header')

                    </div><!--content-header-->

                   
                    @yield('content')
                </div><!--animated-->
            </div><!--container-fluid-->
        </main><!--main-->

        @include('backend.includes.aside')
    </div><!--app-body-->

    @include('backend.includes.footer')

    <!-- Scripts -->
    @stack('before-scripts')
    {!! script(mix('js/manifest.js')) !!}
    {!! script(mix('js/vendor.js')) !!}
    {!! script(mix('js/backend.js')) !!}
    {!! script('js/bootstrap-datetimepicker.js') !!}
    @stack('after-scripts')



    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>

       <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>

<!--     <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
 -->

    <script type="text/javascript">
        $('.form_date').datetimepicker({
            //language:  'fr',
            format: 'dd-mm-yyyy',
            weekStart: 1,
            todayBtn:  0,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
    </script>


<script type="text/javascript">
    $(document).ready(function() {
        //alert();
        $('#multi-select-proviences').multiselect();
    });
</script>


<!-- <script type="text/javascript">
   $(document).ready(function() {
    $('#example12').DataTable();
} );
</script> -->

<!-- <script type="text/javascript">

   $(document).ready(function() {
    $('#example12').DataTable( {
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columnDefs: [ {
            className: 'control',
            orderable: false,
            targets:   -1
        } ]
    } );
} );
</script> -->

<script type="text/javascript">
    var table = $('#example12').DataTable({
    responsive: true,
    pageLength: 25
});
</script>

<style type="text/css">
.question-table.dataTable.dtr-column > tbody > tr > td.control::before, .question-table.dataTable.dtr-column > tbody > tr > th.control::before {
    right: 3% !important; left: auto !important;
}
</style>
</body>
</html>
