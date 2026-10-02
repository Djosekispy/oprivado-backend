<div class="justify-content-center">
<div style="box-shadow: 0px 0rem 10px 2px rgb(0 0 0 / 11%) !important;margin-left: 20px;margin-right: 20px;margin-bottom: 10px;border-radius: 34px;right: 0;" class="menuMobile bg-white p-3 border-top">
	<ul class="list-inline d-flex bd-highlight m-0 text-center">

				<li class="flex-fill bd-highlight">
					<a class="p-3 btn-mobile {{ request()->is('/') ? 'active' : '' }}" href="{{url('/')}}" title="{{trans('admin.home')}}">
						<i class="feather icon-home icon-navbar"></i>
					</a>
				</li>

				@if (!$settings->disable_creators_section)
				<li class="flex-fill bd-highlight">
					<a class="p-3 btn-mobile {{ request()->is('explore') ? 'active' : '' }}" href="/explore" title="{{trans('general.explore')}}">
						<i class="feather icon-compass icon-navbar"></i>
					</a>
				</li>
				@endif
		
		 @if ($settings->live_streaming_status == 'on') <li class="flex-fill bd-highlight">
				<a href="/explore/creators/live" class="p-3 btn-mobile position-relative {{ request()->is('explore/creators/live') ? 'active' : '' }}" title="Live">
					<i class="feather icon-radio icon-navbar"></i>
							@php
								$liveService = app(\App\Services\LiveService::class);
								$activeLives = $liveService->countActiveLives() ?? 0;
							@endphp
							@if($activeLives > 0)
								<span class="live-dot" style="right: 11px!important;bottom: 32px!important;top:8px !important;"></span>
							@endif
				</a>
			</li>@endif

			<li class="flex-fill bd-highlight">
				<a href="/creators/more-active" class="p-3 btn-mobile position-relative {{ request()->is('creators/more-active') ? 'active' : '' }}" title="Criadores">
					<i class="feather icon-star icon-navbar"></i>
				</a>
			</li>

			<li class="flex-fill bd-highlight">
				<a href="{{url('notifications')}}" class="p-3 btn-mobile position-relative {{ request()->is('notifications') ? 'active' : '' }}" title="{{ trans('general.notifications') }}">
					<span class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @endif">
						{{ auth()->user()->unseenNotifications() }}
						</span>
					<i class="feather icon-bell icon-navbar"></i>
				</a>
			</li>
		
		@auth
		<li class="flex-fill bd-highlight">
				<a class="btn-mobile p-3 navbar-toggler-mobile" href="#"  data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" role="button">
					<img src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}" alt="User" class="rounded-circle icon-navbar avatarUser" width="21" height="21">
				</a>
				</li> @endauth
			</ul>
</div>
</div>