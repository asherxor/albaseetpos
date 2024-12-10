<!-- Left side column. contains the logo and sidebar -->
<aside class="side-bar tw-relative tw-hidden tw-h-full tw-bg-white tw-w-64 xl:tw-w-64 lg:tw-flex lg:tw-flex-col tw-shrink-0">

    <!-- sidebar: style can be found in sidebar.less -->

    {{-- <a href="{{route('home')}}" class="logo">
		<span class="logo-lg">{{ Session::get('business.name') }}</span>
	</a> --}}

    <a href="{{route('home')}}"
        class="tw-flex tw-items-center tw-justify-center tw-w-full tw-border-r tw-h-15 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'primary'}}@endif-800 tw-shrink-0 tw-border-primary-500/30">
        <p class="tw-text-lg tw-font-medium tw-text-white side-bar-heading tw-text-center">
           	<!-- Logo -->
			@if(!empty(Session::get('business.logo')))
			<img style="max-height: 100px; width: 100px;" src="{{ asset( 'uploads/business_logos/' . Session::get('business.logo') ) }}" class="img">
		@endif
        </p>
    </a>

    <!-- Sidebar Menu -->
    {!! Menu::render('admin-sidebar-menu', 'adminltecustom') !!}

    <!-- /.sidebar-menu -->
    <!-- /.sidebar -->
</aside>
