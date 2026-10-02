
<ul class="list-unstyled d-lg-block d-none menu-left-home sticky-top">
	<li>
		<a href="{{url('/')}}" @if (request()->is('/')) class="active disabled" @endif>
			<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/inicial.svg">
			<span class="ml-2">{{ trans('admin.home') }}</span>
		</a>
	</li>

	@auth
	<li>
		<a href="{{ url(auth()->user()->username) }}">
			<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/user_1.svg">
			<span class="ml-2">{{ auth()->user()->verified_id == 'yes' ? trans('general.my_page') : trans('users.my_profile') }}</span>
		</a>
	</li>
	@if (auth()->user()->verified_id == 'yes')
	<li>
		<a href="{{ url('dashboard') }}">
			<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/states.svg">
			<span class="ml-2">{{ trans('admin.dashboard') }}</span>
		</a>
	</li>
	@endif
		<li>
			<a href="{{ url('my/purchases') }}" @if (request()->is('my/purchases')) class="active disabled" @endif>
				<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/star.svg">
				<span class="ml-2">{{ trans('general.purchased') }}</span>
			</a>
		</li>
	<li>
		<a href="{{ url('messages') }}">
			<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/Comentar.svg">
			<span class="ml-2">{{ trans('general.messages') }}</span>
		</a>
	</li>
	@if (!$settings->disable_explore_section)
	<li>
		<a href="{{ url('explore') }}" @if (request()->is('explore')) class="active disabled" @endif>
			<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/Explorar.svg">
			<span class="ml-2">{{ trans('general.explore') }}</span>
		</a>
	</li>
	@endif
	<li>
		<a href="{{ url('my/bookmarks') }}" @if (request()->is('my/bookmarks')) class="active disabled" @endif>
			<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/salve.svg">
			<span class="ml-2">{{ trans('general.bookmarks') }}</span>
		</a>
	</li>

	@else
	<li>
		<a href="{{ url('creators') }}">
			<i class="bi-compass"></i>
			<span class="ml-2">{{ trans('general.explore') }}</span>
		</a>
	</li>

		@if ($settings->shop)
		<li>
			<a href="{{ url('shop') }}">
				<i class="feather icon-shopping-bag"></i>
				<span class="ml-2">{{ trans('general.shop') }}</span>
			</a>
		</li>
		@endif

	@endauth
</ul>