<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
	<head><base href="../../../">
		<meta charset="utf-8" />
		<title>{{ $pageTitle ?? '' }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="description" content="Metronic admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
		<meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular 11, VueJs, React, Laravel, admin themes, web design, figma, web development, ree admin themes, bootstrap admin, bootstrap dashboard" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="{{ asset('/themes/') }}/admin/media/logos/favicon.ico" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
        <link href="{{ asset('/themes/') }}/admin/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
		<link href="{{ asset('/themes/') }}/admin/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/themes/') }}/admin/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/themes/') }}/admin/css/custom.css" rel="stylesheet" type="text/css" />

		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
        @if(Auth::guard('admins')->check())

            <body id="kt_body" 
            class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed" 
            style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    
        @else

            <body id="kt_body" class="bg-white">

        @endif


        @if(Auth::guard('admins')->check())
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
                @include('admin.includes.sidebar')

                <!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                    <!--begin::Header-->
                    @include('admin.includes.header')
                    <!--end::Header-->

                    <!--begin::Content-->
                    @yield('content')
                    <!--end::Content-->

                    <!--begin::Footer-->
                    @include('admin.includes.footer')
                    <!--end::Footer-->
                   
                 <!--end::Wrapper-->
            </div>
        </div>
  
        @else
            @yield('content_login')
        @endif

		<!--begin::Page Custom Javascript(used by this page)-->
        <script src="{{ asset('/themes/') }}/admin/js/custom.js"></script>
        <!--end::Page Custom Javascript-->
        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script src="{{ asset('/themes/') }}/admin/plugins/global/plugins.bundle.js"></script>
        <script src="{{ asset('/themes/') }}/admin/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->

        <!--begin::Javascript-->
        @if(Auth::guard('admins')->check())
            <!--begin::Page Custom Javascript(used by this page)-->
            <script src="{{ asset('/themes/') }}/admin//js/custom/widgets.js"></script>
            <script src="{{ asset('/themes/') }}/admin//js/custom/apps/chat/chat.js"></script>
            <script src="{{ asset('/themes/') }}/admin//js/custom/modals/create-app.js"></script>
            <script src="{{ asset('/themes/') }}/admin//js/custom/modals/upgrade-plan.js"></script>
            <!--end::Page Custom Javascript-->
        @else   
            <!--begin::Page Authentication Javascript(used by this page)-->
            <script src="{{ asset('/themes/') }}/admin/js/custom/authentication/sign-in/general.js"></script>
            <!--end::Page Authentication Javascript-->
        @endif
		<!--end::Javascript-->
    </body>
	<!--end::Body-->
</html>