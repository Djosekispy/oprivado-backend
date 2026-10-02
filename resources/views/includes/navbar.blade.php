<header>
	<nav class="navbar navbar-expand-lg navbar-inverse fixed-top p-nav @if(auth()->guest() && request()->path() == '/' && $settings->home_style == 0) scroll @else p-3 @if (request()->is('live/*')) d-none @endif  @if (request()->is('messages/*')) d-none d-lg-block shadow-sm @elseif(request()->is('messages')) shadow-sm @else @endif {{ auth()->check() && auth()->user()->dark_mode == 'on' ? 'bg-white' : 'navbar_background_color' }} link-scroll @endif">
		
		<div class="container-fluid d-flex position-relative">

			@auth
			<div class="buttons-mobile-nav d-lg-none">
				<a class="btn-mobile-nav none navbar-toggler-mobile" href="#"  data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" role="button">
					<i class="feather icon-menu icon-navbar"></i>
				</a>
				</div>
			
			<div class="buttons-mobile-nav d-lg-none">
			<a class="btn-mobile" href="{{url('messages')}}" title="Mensagens">
				<span style="top: -4px !important;right: -3px !important;" class="noti_msg notify @if (auth()->user()->messagesInbox() != 0) d-block @endif">
						{{ auth()->user()->messagesInbox() }}
						</span>
						<i class="feather icon-message-circle icon-navbar"></i>
			</a></div> @endauth

			<a class="navbar-brand" href="{{url('/')}}">
				@if (auth()->check() && auth()->user()->dark_mode == 'on' )
					<img src="{{url('public/img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-bottom max-w-100" />
				@else
				<img src="/public/img/logotipo.svg" width="130px" alt="{{$settings->title}}" class="logo align-bottom" />
			@endif
			</a>

			@guest
				<button class="navbar-toggler @if(auth()->guest() && request()->path() == '/' && $settings->home_style == 0) text-white @endif" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<i class="feather icon-menu icon-navbar"></i>
				</button>
			@endguest

			<div class="collapse navbar-collapse navbar-mobile" id="navbarCollapse">

			@if (auth()->guest() && $settings->who_can_see_content == 'all' || auth()->check())
				<ul class="navbar-nav mr-auto">

					@if (!$settings->disable_creators_section)
						@if (!$settings->disable_search_creators)
						<form class="form-inline my-lg-0 position-relative" method="get" action="{{url('creators')}}">
							<input  style="border: 1px solid #ced4da !important;border-radius: 25px!important;background: #fff!important;" id="searchCreatorNavbar"
								class="form-control search-bar @if(auth()->guest() && request()->path() == '/') border-0 @endif" type="text"
								required name="q" autocomplete="off" minlength="3" placeholder="{{ __('general.find_user') }}"
								aria-label="Search">
							<button class="btn btn-outline-success my-sm-0 button-search e-none" type="submit">
								<img class=" lazyloaded" alt="Pesquisar" src="/public/img/pesquisa.svg" width="16px" style="position: absolute;top: 10px;left: 15px;"></button>
						
							<div class="dropdown-menu dd-menu-user position-absolute" style="width: 95%; top: 48px;" id="dropdownCreators">
						
								<button type="button" class="d-none" id="triggerBtn" data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"></button>
						
								<div class="w-100 text-center display-none py-2" id="spinnerSearch">
									<span class="spinner-border spinner-border-sm align-middle text-primary"></span>
								</div>
						
								<div id="containerCreators"></div>
						
								<div id="viewAll" class="display-none mt-2">
									<a style="font-size:12px" class="dropdown-item py-2 border text-center" href="#">{{ __('general.view_all') }}</a>
								</div>
							</div><!-- dropdown-menu -->
						</form>
						@endif
					@endif

					@guest
					 @if (!$settings->disable_creators_section)
						<li class="nav-item manutencao">
							<a style="font-size:14px;" class="nav-link" href="{{url('creators')}}">{{__('general.explore')}}</a>
						</li>
						@endif

						@if ($settings->shop)
						<li class="nav-item">
							<a class="nav-link" href="{{url('shop')}}">{{__('general.shop')}}</a>
						</li>
					@endif
					@endguest

				</ul>
			@endif

				<ul class="navbar-nav ml-auto">
					@guest
					<li class="nav-item mr-1">
						<a @if (Helper::showLoginFormModal()) data-toggle="modal" data-target="#loginFormModal" @endif class="nav-link none login-btn @if ($settings->registration_active == '0')  btn btn-main btn-primary pr-3 pl-3 @endif" href="{{ in_array(config('settings.home_style'), [0, 2]) ? url('login') : url('/')}}">
							{{__('auth.login')}}
						</a>
					</li>

					@if ($settings->registration_active == '1')
					<li class="nav-item">
						<a @if (Helper::showLoginFormModal()) data-toggle="modal" data-target="#loginFormModal" @endif class="toggleRegister nav-link btn btn-main @if (request()->path() == '/' && $settings->home_style == 0) btn-light @else btn-primary @endif btn-register-menu pr-3 pl-3 btn-arrow btn-arrow-sm" href="{{ in_array(config('settings.home_style'), [0, 2]) ? url('signup') : url('/') }}">
							{{__('general.getting_started')}}
						</a>
					</li>
				@endif

			@else

				<!-- ============ Menu Mobile ============-->
					
						<div class="mt-3"></div>

				<li class="nav-item dropdown d-lg-none @if (auth()->user()->role != 'admin') mt-2 @endif">
					<a href="{{url(auth()->user()->username)}}" class="nav-link px-2 link-menu-mobile py-1 url-user">
						<div>
							<img style=" margin-bottom: 3px; " src="/public/img/user_1.svg" alt="User" class="mr-1" width="20" height="20">
							<span class="d-lg-none">{{ auth()->user()->verified_id == 'yes' ? __('general.my_page') : __('users.my_profile') }}</span>
						</div>
					</a>
				</li>
					
					@if (auth()->user()->verified_id == 'yes')
				<li class="nav-item dropdown d-lg-none">
					<a href="/dashboard" class="nav-link px-2 link-menu-mobile py-1 url-user">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/usd-circle.svg">
							<span class="d-lg-none">{{ __('general.balance') }}: {{Helper::amountFormatDecimal(auth()->user()->balance)}}</span>
						</div>
					</a>
				</li> @else
					
					<li class="nav-item dropdown d-lg-none">
					<a href="/my/referrals" class="nav-link px-2 link-menu-mobile py-1 url-user">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/usd-circle.svg">
							<span class="d-lg-none">{{ __('general.balance') }}: {{Helper::amountFormatDecimal(auth()->user()->balance)}}</span>
						</div>
					</a>
				</li>
					@endif
					
					@if ($settings->disable_wallet == 'on' && auth()->user()->wallet != 0.00 || $settings->disable_wallet == 'off')
					<li class="nav-item dropdown d-lg-none">
						<a @if ($settings->disable_wallet == 'off') href="{{url('my/wallet')}}" @endif class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/atm-card.svg">
							{{ __('general.wallet') }}: <span class="balanceWallet">{{Helper::userWallet()}}</span>
						</div>
						</a>
					</li>
				@endif
					
					<li class="nav-item dropdown d-lg-none">
					<a href="/my/referrals" class="nav-link px-2 link-menu-mobile py-1 url-user">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/afiliado4.svg">
							<span class="d-lg-none">Afiliados</span>
						</div>
					</a>
				</li>

					
				@if (auth()->user()->verified_id == 'yes')
				<li class="nav-item dropdown d-lg-none">
					<a href="{{url('dashboard')}}" class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/states.svg">
							<span class="d-lg-none">{{ __('admin.dashboard') }}</span>
						</div>
						</a>
				</li>

				<li class="nav-item dropdown d-lg-none">
					<a href="{{url('my/posts')}}" class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/post2.svg">
							<span class="d-lg-none">{{ __('general.my_posts') }}</span>
						</div>
						</a>
				</li>
	
				<li class="nav-item dropdown d-lg-none">
					<a href="{{url('my/stories')}}" class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/stories2.svg">
							<span class="d-lg-none">Minhas histórias</span>
						</div>
					</a>
				</li>
				
			@endif
					
					@if (auth()->user()->verified_id == 'no' && auth()->user()->verified_id != 'reject')
				<li class="nav-item dropdown border-top border-bottom d-lg-none">
					<a href="{{url('settings/verify/account')}}" class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:0px!important;" src="/public/img/usd-circle.svg">
							<span class="d-lg-none">{{ __('general.become_creator') }}</span><br>
							<span style=" margin-left: 30px; font-size: 12px; " class="d-lg-none">Ganhar dinheiro</span>
						</div>
					</a>
				</li>
			@endif
						
				<li class="nav-item dropdown d-lg-none">
					<a href="{{url('settings/withdrawals')}}" class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/saques.svg">
							<span class="d-lg-none">Meus levantamentos</span>
						</div>
					</a>
				</li>

				@if (auth()->user()->verified_id == 'yes')
				<li class="nav-item dropdown d-lg-none">
					<a href="{{url('my/subscribers')}}" class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/love.svg">
							<span class="d-lg-none">{{ __('users.my_subscribers') }}</span>
						</div>
					</a>
				</li>
				@endif

				<li class="nav-item dropdown d-lg-none">
					<a href="{{url('my/subscriptions')}}" class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/follow.svg">
							<span class="d-lg-none">{{ __('users.my_subscriptions') }}</span>
						</div>
					</a>
				</li>
					
					<li class="nav-item dropdown d-lg-none">
				<a href="{{url('my/bookmarks')}}" class="nav-link px-2 link-menu-mobile py-1">
					<div>
						<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/salve.svg">
						<span class="d-lg-none">{{ __('general.bookmarks') }}</span>
					</div>
				</a>
			</li>

			<li class="nav-item dropdown d-lg-none">
				<a href="{{url('my/likes')}}" class="nav-link px-2 link-menu-mobile py-1">
					<div>
						<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/star.svg">
						<span class="d-lg-none">{{ __('general.likes') }}</span>
					</div>
				</a>
			</li>

					<li class="nav-item dropdown d-lg-none">
						<a href="{{url('my/purchases')}}" class="nav-link px-2 link-menu-mobile py-1">
							<div>
								<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/compra.svg">
								<span class="d-lg-none">{{ __('general.purchased') }}</span>
							</div>
						</a>
					</li>

				<li class="nav-item dropdown d-lg-none mb-2">
					<a href="{{ url('logout') }}" class="nav-link px-2 link-menu-mobile py-1">
						<div>
							<img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/exit2.svg">
							<span class="d-lg-none">{{ __('auth.logout') }}</span>
						</div>
					</a>
				</li>
				<!-- =========== End Menu Mobile ============-->

					<li class="nav-item dropdown d-lg-block d-none">
						<a class="nav-link px-2" href="{{url('/')}}" title="{{__('admin.home')}}">
							<i class="feather icon-home icon-navbar"></i>
							<span class="d-lg-none align-middle ml-1">{{__('admin.home')}}</span>
						</a>
					</li>

					@if (!$settings->disable_creators_section)
					<li class="nav-item manutencao dropdown d-lg-block d-none">
						<a class="nav-link px-2" href="{{url('explore')}}" title="{{__('general.explore')}}">
							<i class="feather icon-compass icon-navbar"></i>
							<span class="d-lg-none align-middle ml-1">{{__('general.explore')}}</span>
						</a>
					</li>
					@endif
					
					 @if ($settings->live_streaming_status == 'on') <li class="nav-item manutencao dropdown d-lg-block d-none">
						<a class="nav-link px-2" href="{{ url('explore/creators/live') }}" title="Em directo">

							<i class="feather icon-radio icon-navbar"></i>

							@php
								$liveService = app(\App\Services\LiveService::class);
								$activeLives = $liveService->countActiveLives() ?? 0;
							@endphp

							@if($activeLives > 0)
								<span class="live-dot"></span>
							@endif
							<span class="d-lg-none align-middle ml-1">Em directo</span>
						</a>
					</li>@endif
					
					<li class="nav-item manutencao dropdown d-lg-block d-none">
						<a class="nav-link px-2" href="{{url('creators/more-active')}}" title="{{__('general.explore_creators')}}">
							<i class="feather icon-star icon-navbar"></i>
							<span class="d-lg-none align-middle ml-1">{{__('general.creators')}}</span>
						</a>
					</li>

					@if ($settings->shop)
					<li class="nav-item dropdown d-lg-block d-none">
						<a class="nav-link px-2" href="{{url('shop')}}" title="{{__('general.shop')}}">
							<i class="feather icon-shopping-bag icon-navbar"></i>
							<span class="d-lg-none align-middle ml-1">{{__('general.shop')}}</span>
						</a>
					</li>
				@endif

				<li class="nav-item dropdown d-lg-block d-none">
					<a href="{{url('messages')}}" class="nav-link px-2" title="{{ __('general.messages') }}">

						<span class="noti_msg notify @if (auth()->user()->messagesInbox() != 0) d-block @endif">
							{{ auth()->user()->messagesInbox() }}
							</span>

						<i class="feather icon-message-circle icon-navbar"></i>
						<span class="d-lg-none align-middle ml-1">{{ __('general.messages') }}</span>
					</a>
				</li>

				<li class="nav-item dropdown d-lg-block d-none">
					<a href="{{url('notifications')}}" class="nav-link px-2" title="{{ __('general.notifications') }}">

						<span class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @endif">
							{{ auth()->user()->unseenNotifications() }}
							</span>

						<i class="feather icon-bell icon-navbar"></i>
						<span class="d-lg-none align-middle ml-1">{{ __('general.notifications') }}</span>
					</a>
				</li>

				<li class="nav-item dropdown d-lg-block d-none">
					<a class="nav-link" href="#" id="nav-inner-success_dropdown_1" role="button" data-toggle="dropdown">
						<img src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}" alt="User" class="rounded-circle avatarUser mr-1" width="25" height="25">
						<span class="d-lg-none">{{auth()->user()->first_name}}</span>
						<i class="feather icon-chevron-down m-0 align-middle"></i>
					</a>
					<div class="dropdown-menu mb-1 dropdown-menu-right dd-menu-user" aria-labelledby="nav-inner-success_dropdown_1">
						<a class="dropdown-item dropdown-navbar url-user" href="{{url(auth()->User()->username)}}"><img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/user_1.svg"> {{ auth()->user()->verified_id == 'yes' ? __('general.my_page') : __('users.my_profile') }}</a>
						
						@if (auth()->user()->verified_id == 'yes') <a class="dropdown-item dropdown-navbar url-user" href="/dashboard"><img class="mr-2" width="20" heigth="20" style="margin-bottom:2px!important;" src="/public/img/usd-circle.svg">{{__('general.balance')}}: {{Helper::amountFormatDecimal(auth()->user()->balance)}}</a>@else <a class="dropdown-item dropdown-navbar url-user" href="/my/referrals"><img class="mr-2" width="20" heigth="20" style="margin-bottom:2px!important;" src="/public/img/usd-circle.svg">{{__('general.balance')}}: {{Helper::amountFormatDecimal(auth()->user()->balance)}} @endif
						
						@if ($settings->disable_wallet == 'on' && auth()->user()->wallet != 0.00 || $settings->disable_wallet == 'off')
						@if ($settings->disable_wallet == 'off')
							<a class="dropdown-item dropdown-navbar" href="{{url('my/wallet')}}">
								<img class="mr-1" width="22" heigth="22" style="margin-bottom:5px!important;" src="/public/img/atm-card.svg"> {{__('general.wallet')}}:
								<span class="balanceWallet">{{Helper::userWallet()}}</span>
							</a>
						@else
							<span class="dropdown-item dropdown-navbar balance">
								<img class="mr-1" width="22" heigth="22" style="margin-bottom:3px!important;" src="/public/img/atm-card.svg"> {{__('general.wallet')}}:
								<span class="balanceWallet">{{Helper::userWallet()}}</span>
							</span>
						@endif
						
						@if (auth()->user()->verified_id == 'yes')
						<a class="dropdown-item dropdown-navbar" href="{{url('dashboard')}}"><img class="mr-2" width="20" heigth="20" style="margin-bottom:5px!important;" src="/public/img/states.svg">{{__('admin.dashboard')}}</a> 	@endif
						
						<a class="dropdown-item dropdown-navbar url-user" href="/my/referrals"><img class="mr-2" width="20" heigth="20" style="margin-bottom:2px!important;" src="/public/img/afiliado4.svg">Afiliados</a>

					@endif
						@if (auth()->user()->verified_id == 'yes')
						<a class="dropdown-item dropdown-navbar" href="{{url('my/posts')}}"><img class="mr-1" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/post2.svg"> {{__('general.my_posts')}}</a>
						<a class="dropdown-item dropdown-navbar" href="{{url('my/stories')}}"><img class="mr-1" width="19" heigth="18" style="margin-bottom:3px!important;" src="/public/img/stories2.svg"> Minhas histórias</a>
					@endif
						<a class="dropdown-item dropdown-navbar" href="{{url('settings/withdrawals')}}"><img class="mr-2" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/saques.svg">Meus levantamentos</a>

						@if (auth()->user()->verified_id == 'yes')
						<a class="dropdown-item dropdown-navbar" href="{{url('my/subscribers')}}"><img class="mr-2" width="20" heigth="20" style="margin-bottom:3px!important;" src="/public/img/love.svg">{{__('users.my_subscribers')}}</a>
					@endif
						<a class="dropdown-item dropdown-navbar" href="{{url('my/subscriptions')}}"><img class="mr-1" width="20" heigth="20" style="margin-bottom:5px!important;" src="/public/img/follow.svg"> {{__('users.my_subscriptions')}}</a>
						
						@if (auth()->user()->verified_id == 'no'
									&& auth()->user()->verified_id != 'reject'
									&& $settings->requests_verify_account == 'on'
									)
							<div class="dropdown-divider"></div>
							<a class="dropdown-item dropdown-navbar" href="{{url('settings/verify/account')}}"><img class="mr-1" width="20" heigth="20" style="margin-bottom:0px!important;" src="/public/img/usd-circle.svg"> {{__('general.become_creator')}} <br><span style=" margin-left: 26px; font-size: 12px; ">Ganhar dinheiro</span></a>
						<div class="dropdown-divider dropdown-navbar"></div>
						@endif

						<a class="dropdown-item dropdown-navbar" href="{{url('settings/page')}}"><img class="mr-1" width="20" heigth="20" style="margin-bottom:5px!important;" src="/public/img/cog-11.svg"> Denifições</a>
						
						<a class="dropdown-item dropdown-navbar" href="{{url('logout')}}"><img class="mr-1" width="22" heigth="22" style="margin-bottom:5px!important;" src="/public/img/exit2.svg">  {{__('auth.logout')}}</a>
					</div>
				</li>

					@endguest

				</ul>
			</div>
		</div>
	</nav>
</header>


