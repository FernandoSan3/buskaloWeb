<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', app_name())</title>
	<meta name="description" content="@yield('meta_description', app_name())">
	<meta name="author" content="@yield('meta_author', 'WDP Technologies Pvt. Ltd.')">
	@yield('meta')
    <link rel="icon" href="{{ url('favicon.ico') }}">
    <!-- Bootstrap core CSS -->
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet"> 
    

<!--   {{ style('css/owl.carousel.min.css') }}

	{{ style('css/owl.theme.css') }} -->
	{{ style('css/font-awesome.css') }}
	{{ style('css/bootstrap.min.css') }}
	<!-- {{ style('css/owl.carousel.css') }} -->
	<!-- {{ style('css/owl.theme.css') }} -->
	{{ style('css/owl.carousel.min.css') }}
	{{ style('css/style.css') }}
	{{ style('css/responsive.css') }}
	{{ style('css/jquery.dataTables.min.css') }}
	{{ style('css/rowReorder.dataTables.min.css') }}
	{{ style('css/responsive.dataTables.min.css') }}	
	{{ style('select2/dist/css/select2.min.css') }}
	{{ style('css/bootstrap-datetimepicker.min.css') }}

	{{ style('css/chat.css') }}
     {{ script('js/jquery.min.js') }}

	<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
	<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>

		
	<style type="text/css">
		.inactiveLink {
	   pointer-events: none;
	   cursor: default;
	}
	</style>

{{-- 	<style type="text/css">
		
		.cropper-crop-box, .cropper-view-box {
    border-radius: 50%;
}

.cropper-view-box {
    box-shadow: 0 0 0 1px #39f;
    outline: 0;
}

.cropper-face {
  background-color:inherit !important;
}

.cropper-dashed, .cropper-point.point-se, .cropper-point.point-sw, .cropper-point.point-nw,   .cropper-point.point-ne, .cropper-line {
  display:none !important;
}

.cropper-view-box {
  outline:inherit !important;
}
	</style> --}}

</head>

    

    
