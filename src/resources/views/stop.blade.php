@if(Auth::user()->isImpersonating())
	<div class="text-center stop-impersonating col-xs-4 col-xs-offset-4">
	    <a href="/core/impersonate/stop"
	        class="btn btn-xs bg-orange">{{ __('Stop Impersonating') }}
	    </a>
	</div>
@endif