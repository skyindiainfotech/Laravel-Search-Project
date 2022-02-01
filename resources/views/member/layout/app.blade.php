<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - #1 Selling Bootstrap 5 HTML Multi-demo Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head><base href="../../../">
		<meta charset="utf-8" />
		<title>Search Demo </title>
		<meta name="description" content="Metronic admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
		<meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular 11, VueJs, React, Laravel, admin themes, web design, figma, web development, ree admin themes, bootstrap admin, bootstrap dashboard" />
		<link rel="canonical" href="Https://preview.keenthemes.com/metronic8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="{{ asset('/themes/') }}/admin/media/logos/favicon.ico" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{ asset('/themes/') }}/admin/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/themes/') }}/admin/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
        @if(Auth::guard('members')->check())

            <body id="kt_body" 
            class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed" 
            style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    
        @else

            <body id="kt_body" class="bg-white">

        @endif


        @if(Auth::guard('members')->check())
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
            @yield('auth_content')
        @endif

        <!--begin::Javascript-->
		<!--begin::Page Custom Javascript(used by this page)-->
        <script src="{{ asset('/themes/') }}/admin/js/custom.js"></script>
        <!--end::Page Custom Javascript-->

        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script src="{{ asset('/themes/') }}/admin//plugins/global/plugins.bundle.js"></script>
        <script src="{{ asset('/themes/') }}/admin//js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->

        @if(Auth::guard('members')->check())
        <!--begin::Page Custom Javascript(used by this page)-->
        <script src="{{ asset('/themes/') }}/admin//js/custom/widgets.js"></script>
        <script src="{{ asset('/themes/') }}/admin//js/custom/apps/chat/chat.js"></script>
        <script src="{{ asset('/themes/') }}/admin//js/custom/modals/create-app.js"></script>
        <script src="{{ asset('/themes/') }}/admin//js/custom/modals/upgrade-plan.js"></script>
        <!--end::Page Custom Javascript-->
        @endif
		<!--end::Javascript-->
    </body>
	<!--end::Body-->
</html>