@extends('layouts.app')

@section('title') {{trans('general.privacy_security')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
			
			<div class="py-5">
          <h2 class="mb-0 font-montserrat"> {{trans('general.privacy_security')}}</h2>
        
        </div>

          @if (session('status'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                			<span aria-hidden="true">×</span>
                			</button>

                    {{ session('status') }}
                  </div>
                @endif

          @include('errors.errors-forms')

              <div class="mb-4 none mt-2">
				  <label>{{ __('general.login_sessions') }}</label>
                <div>

                  @if ($agents->count() || $currentSession)
                  <small class="w-100 d-block"><strong>{{ __('general.last_login_record') }}</strong></small>

                  @if ($currentSession)
                  <p class="card-text mb-4 border-bottom pb-2">
                    <i class="bi-{{ $currentSession->device_type == 'phone' ? 'phone' : 'display' }} mr-1"></i> 
                    <strong>{{ $currentSession->getNameBrowser() }} {{ __('general.on') }} {{ $currentSession->getNamePlatform() }}{{ $currentSession->device_type == 'phone' ? ', '.$currentSession->device : null }}</strong>
                  <span class="badge badge-pill badge-success">{{ __('general.active_now') }}</span>

                  <small class="text-muted w-100 d-block mt-2 mb-0">
                    {{ $currentSession->ip }} - {{ $currentSession->country ? $currentSession->country.' - ' : null }} <span class="timeAgo" data="{{date('c', strtotime($currentSession->updated_at))}}"></span> 
                  </small> 
                  </p>
                  @endif
                  
                  @foreach ($agents as $agent)
                  <p class="card-text mb-1">
                    <i class="bi-{{ $agent->device_type == 'phone' ? 'phone' : 'display' }} mr-1"></i> 
                    <strong>{{ $agent->getNameBrowser() }} {{ __('general.on') }}  {{ $agent->getNamePlatform() }} {{ $agent->device_type == 'phone' ? ', '.$agent->device : null }}</strong> 
                  </p>
                  <small class="text-muted w-100 d-block mb-2">
                    {{ $agent->ip }} - {{ $agent->country ? $agent->country.' - ' : null }} <span class="timeAgo" data="{{date('c', strtotime($agent->updated_at))}}"></span> 
                  </small> 
                  @endforeach
                
                  <small class="text-muted w-100 d-block my-3"> <i class="bi-exclamation-triangle mr-1"></i> {{ __('general.login_session_alert') }}</small> 

                  @if ($agents->count() != 0)
                  <a href="#" class="btn btn-sm btn-primary mt-2" data-toggle="modal" data-target="#logoutDevices">
                    <i class="bi-x-circle mr-1"></i> {{ __('general.close_all_sessions') }}
                  </a>

                  @include('includes.modal-logout-devices')

                  @endif

                  @else
                   {{ __('general.no_results_found') }}
                  @endif
                </div>
              </div>

          @if (auth()->user()->verified_id == 'yes')
            <label class="mt-4">{{ __('general.privacy') }}</label>

            <form method="POST" action="{{ url('privacy/security') }}">

              @csrf

              <div class="form-group">
				   <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="block_message" value="yes" @if (auth()->user()->block_message == 'Yes') checked @endif id="customSwitch6">
                    <label class="custom-control-label switch" for="customSwitch6">Não receber mensagens</label>
                  </div>
                </div>
				  
                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="hide_profile" value="yes" @if (auth()->user()->hide_profile == 'yes') checked @endif id="customSwitch1">
                    <label class="custom-control-label switch" for="customSwitch1">{{ __('general.hide_profile') }} {{ __('general.info_hide_profile') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="hide_last_seen" value="yes" @if (auth()->user()->hide_last_seen == 'yes') checked @endif id="customSwitch2">
                    <label class="custom-control-label switch" for="customSwitch2">{{ __('general.hide_last_seen') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="active_status_online" value="yes" @if (auth()->user()->active_status_online == 'yes') checked @endif id="customSwitch6">
                    <label class="custom-control-label switch" for="customSwitch6">{{ __('general.active_status_online') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="hide_count_subscribers" value="yes" @if (auth()->user()->hide_count_subscribers == 'yes') checked @endif id="customSwitch3">
                    <label class="custom-control-label switch" for="customSwitch3">{{ __('general.hide_count_subscribers') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="hide_my_country" value="yes" @if (auth()->user()->hide_my_country == 'yes') checked @endif id="customSwitch4">
                    <label class="custom-control-label switch" for="customSwitch4">{{ __('general.hide_my_country') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="show_my_birthdate" value="yes" @if (auth()->user()->show_my_birthdate == 'yes') checked @endif id="customSwitch5">
                    <label class="custom-control-label switch" for="customSwitch5">{{ __('general.show_my_birthdate') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="posts_privacy" value="1" @if (auth()->user()->posts_privacy) checked @endif id="posts_privacy">
                    <label class="custom-control-label switch" for="posts_privacy">{{ __('general.posts_privacy') }}</label>
                  </div>
                </div>
  
                <label class="mt-3">{{ __('general.security') }}</label>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="two_factor_auth" value="yes" @if (auth()->user()->two_factor_auth == 'yes') checked @endif id="customSwitch7">
                    <label class="custom-control-label switch" for="customSwitch7">
                      {{ __('general.two_step_auth') }}
                      <i class="bi bi-info-circle text-muted" data-toggle="tooltip" data-placement="top" title="{{trans('general.two_step_auth_info')}}"></i>
                    </label>
                  </div>
                </div>
              </div><!-- End form-group -->

              <button class="btn btn-1 btn-primary btn-block" onClick="this.form.submit(); this.disabled=true; this.innerText='{{ __('general.please_wait')}}';" type="submit">{{ __('general.save_changes')}}</button>

            </form>
          @endif

          @if (! auth()->user()->isSuperAdmin())
			
			@if (auth()->user()->verified_id == 'yes' && auth()->user()->free_subscription == 'yes' )
			 <label style=" margin-bottom: 0px; " class="mt-5">Desactivar conta</label><br>
            <small class="w-100">{{ __('general.deactivate_your_account_alert') }}</small>
            <div class="w-100 d-block mt-2">
              <form action="{{ route('deactivate.account') }}" method="POST">
                @csrf
                <button class="btn btn-block btn-main btn-primary pr-3 pl-3" id="actionDeactivate">
                   Desactivar conta
                </button>
              </form>
              
            </div>
          @endif
			
			  <label class="mt-5">{{ __('general.delete_account') }}</label><br>
          <small class="none w-100">* {{ __('general.delete_account_alert') }}</small>
          <div class="w-100 d-block mt-2">
            <a class="btn btn-main btn-block btn-primary pr-3 pl-3" href="{{ url('account/delete') }}">
              {{ __('general.delete_account') }}
            </a>
          </div> 
            
        @endif
		

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>

<style> .custom-switch {
    padding-left: 1.25rem!important;
} .custom-checkbox .custom-control-input ~ .custom-control-label, .custom-control-label {
    font-size: 14px!important;
    font-weight: 400!important;
} </style>
@endsection

@section('javascript')
<script type="text/javascript">
    $("#actionDeactivate").on('click', function (e) {
		e.preventDefault();

		var element = $(this);
		var form = $(element).parents('form');

		element.blur();

		swal(
			{
				title: delete_confirm,
				type: "warning",
				showLoaderOnConfirm: true,
				showCancelButton: true,
				confirmButtonColor: "#ffc107",
				confirmButtonText: "{{ __('general.yes_confirm_deactivate') }}",
				cancelButtonText: cancel_confirm,
				closeOnConfirm: false,
			},
			function (isConfirm) {
				if (isConfirm) {
					form.submit();
				}
			});
	});
</script>
@endsection