<section class="no-print">
    <nav class="navbar navbar-default bg-white m-4">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">التبديل والتنقل</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{action('\Modules\CcTools\Http\Controllers\CcToolsController@index')}}"><i class="fa fas fa-shield-alt"></i> {{__('cctools::lang.app_name')}}</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                
                @if (auth()->user()->can('cctools.modifiers_view'))
                    <ul class="nav navbar-nav">
                            <li @if(request()->segment(1) == 'cctools' && request()->segment(2) == 'index-modifier') class="active" @endif><a href="{{action('\Modules\CcTools\Http\Controllers\ModifiersController@index_modifiers')}}">{{__('cctools::lang.modifiers')}}</a></li>
                    </ul>
                @endif
				
                @if (auth()->user()->can('superadmin'))
					<ul class="nav navbar-nav">                   
                        <li @if(request()->segment(1) == 'cctools' && request()->segment(2) == 'index-currencys') class="active" @endif><a href="{{action('\Modules\CcTools\Http\Controllers\CurrencysController@index_currencys')}}">{{__('cctools::lang.currencys')}}</a></li>
					</ul>
				@endif
				
				@if (auth()->user()->can('cctools.rated_view'))
					<ul class="nav navbar-nav">
                        <li @if(request()->segment(1) == 'cctools' && request()->segment(2) == 'tazas') class="active" @endif><a href="{{action('\Modules\CcTools\Http\Controllers\CurrencysController@tazas')}}">{{__('cctools::lang.tazas')}}</a></li>
					</ul>
				@endif

            </div>
			<!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</section>