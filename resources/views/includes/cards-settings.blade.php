<div class="col-md-6 col-lg-3 mt-3">

<button type="button" class="btn-menu-expand btn btn-primary btn-block mb-2 d-lg-none" type="button" data-toggle="collapse" data-target="#navbarUserHome" aria-controls="navbarCollapse" aria-expanded="false">
		<i class="fa fa-bars mr-2"></i> {{__('general.menu')}}
	</button>

	<div class="navbar-collapse collapse d-lg-block" id="navbarUserHome">

		<!-- Start Account -->
		<div class="card shadow-sm card-settings mb-3">
				<div class="list-group list-group-sm list-group-flush">

    <small class="text-muted px-4 pt-3  mb-1 font-weight-bold">{{ __('general.account') }}</small>

					@if (auth()->user()->verified_id == 'yes')
					<a href="{{url('dashboard')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('dashboard')) active @endif">
							<div>
									<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/states.svg">
									<span>{{__('admin.dashboard')}}</span>
							</div>
							<div>
									<i class="feather icon-chevron-right"></i>
							</div>
					</a>
				@endif

					<a href="{{url('settings/page')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/page')) active @endif">
							<div>
									<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/user_1.svg">
									<span>{{ auth()->user()->verified_id == 'yes' ? __('general.edit_my_page') : __('users.edit_profile')}}</span>
							</div>
							<div>
									<i class="feather icon-chevron-right"></i>
							</div>
					</a>

				@if (auth()->user()->verified_id == 'yes')
				  <a class="none" href="{{url('settings/conversations')}}" @class([ 'list-group-item list-group-item-action d-flex justify-content-between', 'active' => request()->is('settings/conversations')])>
					<div>
						<i class="feather icon-send mr-2"></i>
						<span>{{__('general.conversations')}}</span>
					</div>
					<div>
						<i class="feather icon-chevron-right"></i>
					</div>
				</a>
			  @endif

					@if ($settings->disable_wallet == 'off')
						<a href="{{url('my/wallet')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/wallet')) active @endif">
								<div>
										<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/atm-card.svg">
										<span>{{__('general.wallet')}}</span>
								</div>
								<div>
										<i class="feather icon-chevron-right"></i>
								</div>
						</a>
					@endif

          
  					<a href="{{url('my/referrals')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/referrals')) active @endif">
  							<div>
  									<img class="mr-2" width="21" heigth="21" style="margin-bottom:5px!important;" src="/public/img/afiliado4.svg">
  									<span>Afiliados</span>
  							</div>
  							<div>
  									<i class="feather icon-chevron-right"></i>
  							</div>
  					</a>
					
						@if (auth()->user()->verified_id == 'yes') <a href="{{url('my/posts')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/posts')) active @endif">
						  <div>
								 <img class="mr-2" width="22" heigth="22" style="margin-bottom:5px!important;" src="/public/img/post2.svg">
								  <span>Meus posts</span>
						  </div>
						  <div>
								  <i class="feather icon-chevron-right"></i>
						  </div>
				  </a> @endif

						@if (auth()->user()->verified_id == 'yes') <a href="{{url('my/promotions')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/promotions')) active @endif">
						  <div>
								 <img class="mr-2" width="22" heigth="22" style="margin-bottom:5px!important;" src="/public/img/ads.svg">
								  <span>Promoções</span>
						  </div>
						  <div>
								  <i class="feather icon-chevron-right"></i>
						  </div>
					  </a> @endif

					<a href="{{url('settings/verify/account')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/verify/account')) active @endif">
							<div>
									<img class="mr-2" width="23" heigth="23" style="margin-bottom:0px!important;" src="/public/img/star.svg">
									<span>{{ auth()->user()->verified_id == 'yes' ? __('general.verified_account') : __('general.become_creator')}}</span><br><span style=" margin-left: 34px; font-size: 12px; ">{{ auth()->user()->verified_id == 'yes' ? __('') : __('general.dinheiro')}}</span>
							</div>
							<div>
									<i class="feather icon-chevron-right"></i>
							</div>
					</a>
					
				</div>
			</div><!-- End Account -->

			@if ($settings->live_streaming_private == '2')
			<!-- Start Live Streaming private -->
			<div class="card shadow-sm card-settings mb-3">
				<div class="list-group list-group-sm list-group-flush">

				<small class="text-muted px-4 pt-3 mb-1 font-weight-bold">{{ __('general.live_streaming_private') }}</small>

				@if (auth()->user()->verified_id == 'yes')
				  <a href="{{url('my/live/private/settings')}}"
					class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/live/private/settings')) active @endif">
					<div>
						<i class="bi-gear mr-2"></i>
						<span>{{__('general.settings')}}</span>
					</div>
					<div>
						<i class="feather icon-chevron-right"></i>
					</div>
				</a>

				<a href="{{url('my/live/private/requests')}}"
					class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/live/private/requests')) active @endif">
					<div>
						<i class="bi-box-arrow-in-down mr-2"></i>
						<span>{{__('general.requests_received')}}</span>

							<span class="badge badge-warning">{{ auth()->user()->liveStreamingPrivateRequestPending() ?: null }}</span>
					</div>
					<div>
						<i class="feather icon-chevron-right"></i>
					</div>
				</a>
			  @endif

			<a href="{{url('my/live/private/requests/sended')}}"
					class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/live/private/requests/sended')) active @endif">
					<div>
						<i class="bi-box-arrow-in-up mr-2"></i>
						<span>{{__('general.requests_sent')}}</span>
					</div>
					<div>
						<i class="feather icon-chevron-right"></i>
					</div>
				</a>

				</div>
			</div><!-- End Live Streaming private -->
			@endif

			<!-- Start Subscription -->
			<div class="card shadow-sm card-settings mb-3">
					<div class="list-group list-group-sm list-group-flush">

			<small class="text-muted px-4 pt-3  mb-1 font-weight-bold">{{ __('general.subscription') }}</small>

			@if (auth()->user()->verified_id == 'yes')
			<a href="{{url('settings/subscription')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/subscription')) active @endif">
					<div>
							<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/subscript.svg">
							<span>{{__('general.subscription_price')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>
		@endif

			@if (auth()->user()->verified_id == 'yes')
			<a href="{{url('my/subscribers')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/subscribers')) active @endif">
					<div>
							<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/love.svg">
							<span>{{__('users.my_subscribers')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>
		@endif

			<a href="{{url('my/subscriptions')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/subscriptions')) active @endif">
					<div>
							<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/follow.svg">
							<span>{{__('users.my_subscriptions')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>

		</div>
	</div><!-- End Subscription -->
		
		<!-- Start Payments -->
			<div class="card shadow-sm card-settings mb-3">
					<div class="list-group list-group-sm list-group-flush">

	    <small class="text-muted px-4 pt-3 mb-1 font-weight-bold">{{ __('general.payments') }}</small>

			<a href="{{url('my/payments')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/payments')) active @endif">
					<div>
							<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/usd-circle.svg">
							<span>{{__('general.payments')}} feitos</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>

			@if (auth()->user()->verified_id == 'yes')
			<a href="{{url('my/payments/received')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/payments/received')) active @endif">
					<div>
							<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/usd-circle.svg">
							<span>{{__('general.payments_received')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>
		@endif

				
				<a href="{{url('settings/payout/method')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/payout/method')) active @endif">
						<div>
								<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/atm-card.svg">
								<span>Conta báncaria</span>
						</div>
						<div>
								<i class="feather icon-chevron-right"></i>
						</div>
				</a>

				<a href="{{url('settings/withdrawals')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/withdrawals')) active @endif">
						<div>
								<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/saques.svg">
								<span>{{__('general.withdrawals')}}</span>
						</div>
						<div>
								<i class="feather icon-chevron-right"></i>
						</div>
				</a>
		

					</div>
				</div><!-- End Payments -->

	<!-- Start Privacy and security -->
	<div class="card shadow-sm card-settings mb-3">
			<div class="list-group list-group-sm list-group-flush">

	<small class="text-muted px-4 pt-3 mb-1 font-weight-bold">{{ __('general.privacy_security') }}</small>

	@if (auth()->user()->verified_id == 'yes') <a href="{{url('privacy/security')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('privacy/security')) active @endif">
			<div>
					<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/cog-11.svg">
					<span>{{__('general.privacy_security')}}</span>
			</div>
			<div>
					<i class="feather icon-chevron-right"></i>
			</div>
	</a> @endif

	<a href="{{url('settings/password')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/password')) active @endif">
			<div>
					<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/key.svg">
					<span>{{__('auth.password')}}</span>
			</div>
			<div>
					<i class="feather icon-chevron-right"></i>
			</div>
	</a>

<a href="{{url('settings/restrictions')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/restrictions')) active @endif">
		<div>
				<img class="mr-2" width="22" heigth="22" style="margin-bottom:5px!important;" src="/public/img/block2.svg">
				<span>{{__('general.restricted_users')}}</span>
		</div>
		<div>
				<i class="feather icon-chevron-right"></i>
		</div>
</a>
				<a href="{{url('privacy/security')}}" class="list-group-item list-group-item-action d-flex justify-content-between">
		<div>
				<img class="mr-2" width="23" heigth="23" style="margin-bottom:5px!important;" src="/public/img/block.svg">
				<span>Encerrar conta</span>
		</div>
		<div>
				<i class="feather icon-chevron-right"></i>
		</div>
</a>

			</div>
		</div><!-- End Privacy and security -->

	</div>
</div>

<style>
@media (min-width: 992px){.card {
 border: 0px solid #dbdbdb!important;}}
	
@media (min-width: 992px){	.col-md-6.col-lg-3.mb-3 {
    border-right: 1px solid #dbdbdb;
}}
	
	</style>
