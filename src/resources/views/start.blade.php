@can('impersonate', $user)
	<a class="btn btn-xs bg-orange"
		href="/core/impersonate/{{ $user->id }}">
		{{ __("Impersonate") }}
	</a>
@endcan