@extends('layouts.app')

@section('title') Conteúdo exclusivo -@endsection

@section('brother')
<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "WebSite",
  "name" : "Oprivado Angola",
 "url" : "https://oprivado.ao",
 "description": "Oprivado é a plataforma de conteúdo exclusivo em Angola. Crie seu perfil para monetizar fotos e vídeos ou assine para ter acesso a conteúdo único!",
 "sameAs" : [
   "https://www.facebook.com/oprivado.ao/",
   "https://www.instagram.com/oprivado.ao/"
   ]
}
</script>

<meta property="og:image" content="https://oprivado.ao/public/img/profile.png" />
@endsection

@section('content')

  <div class="jumbotron m-0">
    <div class="container pt-lg-md">
      <div class="row">
        <div class="col-lg-7">
          <img src="https://oprivado.ao/public/img/homeprivadonovo.png" class="img-center img-fluid d-lg-block d-none">
        </div>
        <div class="col-lg-4">
			
          <div class="card bg-white b-radio-custom">
            <div class="card-body px-lg-5 py-lg-5 pt-4">
				
				          <div class="text-center d-block w-100">
            <img src="/public/img/logotipo.svg" alt="Oprivado Angola" width="160px" class="logo align-baseline mb-2" />
          </div>
<p style="color:black;font-size: 14px;font-weight: 400;" class="text-center pc">A maior rede social de conteúdos exclusivos em Angola</p>
				<p style="color:black;font-size: 14px;font-weight: 400;" class="text-center phone">Conecta-te na maior rede social de conteúdos exclusivos em Angola</p>

        @if (session('login_required'))
    			<div class="alert alert-danger" id="dangerAlert">
                		<i class="fa fa-exclamation-triangle"></i> {{session('login_required')}}
                		</div>
                	@endif  

          @if (! $settings->disable_login_register_email)
              <form method="POST" action="{{ route('login') }}" data-url-login="{{ route('login') }}" data-url-register="{{ route('register') }}" id="formLoginRegister" enctype="multipart/form-data">
                  @csrf

                  <input type="hidden" name="return" value="{{ count($errors) > 0 ? old('return') : url()->previous() }}">

                  <div class="form-group mb-3 display-none" id="full_name">
                    <div class="input-group ">
                      
                      <input class="form-control"  value="{{ old('name')}}" placeholder="{{__('auth.full_name')}}" name="name" type="text">
                    </div>
                  </div>

                <div class="form-group mb-3 display-none" id="email">
                  <div class="input-group">
                    
                    <input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}} válido" name="email" type="text">
                  </div>
                </div>

                <div class="form-group mb-3" id="username_email">
                  <div class="input-group ">
                    
                    <input class="form-control" value="{{ old('username_email') }}" placeholder="{{ __('auth.username_or_email') }}" name="username_email" type="text">

                  </div>
                </div>
                <div class="form-group">
                   <div class="form-group">
                  <div class="input-group " id="showHidePassword">
                   
                    <input style=" border-right: 1px solid #fafafa!important; margin-right: -8px!important; " name="password" type="password" class="form-control" placeholder="{{ trans('auth.password') }}">
                    <div class="input-group-append">
                      <span class="input-group-text c-pointer"><i style=" font-size: 13px; " class="feather icon-eye-off"></i></span>
                  </div>
                </div>
                <small class="form-text text-muted">
                  <a href="{{url('password/reset')}}" id="forgotPassword">
                    {{__('auth.forgot_password')}}
                  </a>
                </small>
                </div>

                <div class="form-group d-none">
                  <div class="input-group mb-4">
                  
                  <select name="countries_id" class="form-control custom-select">
                    <option value="">{{__('general.select_your_country')}}</option>
                        @foreach(  Countries::orderBy('country_name')->get() as $country )
                          <option id="{{$country->country_code}}" value="{{$country->id}}">{{ $country->country_name }}</option>
                          @endforeach
                        </select>
                        </div>
                  </div>

					<div class="custom-control-alternative custom-checkbox display-none" id="agree_gdpr">
                    <span style="font-size:12px!important;color: #737373;margin-bottom: 10px!important;">
                        Ao clicar em "Criar conta", você concorda que é maior de 18 de anos e com os Termos e Condições da plataforma.</span></div>


                <div class="alert alert-danger display-none mb-0 mt-3" id="errorLogin">
                    <ul class="list-unstyled m-0" id="showErrorsLogin"></ul>
                  </div>

                  <div class="alert alert-success display-none mb-0 mt-3" id="checkAccount"></div>

                <div class="text-center">
                  @if ($settings->captcha == 'on')
                  {!! NoCaptcha::displaySubmit('formLoginRegister', '<i></i> '.__('auth.login'), ['data-size' => 'invisible', 'id' => 'btnLoginRegister', 'class' => 'btn btn-primary mt-4 w-100']) !!}

                  {!! NoCaptcha::renderJs() !!}

                  @else
                  <button type="submit" id="btnLoginRegister" class="btn btn-primary mt-2 w-100"><i></i> {{__('auth.login')}}</button>
                  @endif
                </div>
              </form>
				  
				  <div style=" margin-bottom: -30px!important; " class="text-left mt-4"><img class="mr-0" alt="pessoas" width="50px" src="/public/images/creators2.png"> <span style="font-size:11px!important;color: black;">{!! Helper::formatNumbersStats($usersTotal) !!} pessoas estão no Oprivado</span> </div>

              @if ($settings->captcha == 'on')
                <small class="btn-block text-center mt-3">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
              @endif

            </div>
          </div>
        </div>

			<div class="card bg-white mt-3 b-radio-custom">
            <div class="card-body pt-4">
				
				    @if ($settings->registration_active == '1')
              <div class="row">
                <div class="col-12 text-center">
                  <a style=" font-size: 14px; " href="javascript:void(0);" id="toggleLogin" data-not-account="{{__('auth.not_have_account')}}" data-already-account="{{__('auth.already_have_an_account')}}" data-text-login="{{__('auth.login')}}" data-text-register="{{__('auth.sign_up')}}">
                    <strong>{{__('auth.not_have_account')}}</strong>
                  </a>
                </div>
              </div>
            @endif

          @else
            <div class="row">
              <div class="col-12 text-center">
                <a style=" font-size: 14px; " href="javascript:void(0);" id="toggleLogin" data-not-account="{{__('auth.not_have_account')}}" data-already-account="{{__('auth.already_have_an_account')}}" data-text-login="{{__('auth.login')}}" data-text-register="{{__('auth.sign_up')}}">
                  <strong>{{__('auth.not_have_account')}}</strong>
                </a>
              </div>
            </div>
          @endif 
          
            </div>
          </div>
			
        </div>
      </div>
    </div>
  </div>

  <div class="text-center py-3 px-3 footer_background_color footer_text_color">
    @include('includes.footer-tiny')
  </div>

<style>
	@media (min-width: 425px) {
    .phone {
        display:none;
    }
}
	
		@media (max-width: 426px) {
    .pc {
        display:none;
    }
}
	
	@media (max-width: 992px) {
    .pl-lg-5, .px-lg-5 {
        padding-top: 3rem !important;
    }
}
	.tag {color: #858585;
    font-size: 11px;
    margin: 2px;margin-bottom:-4px}

@media (max-width: 992px) {
    .pb-lg-5, .py-lg-5 {
        padding-bottom: 3rem !important;
    }
}
		.form-control {
    background-color: #fafafa!important;

}
	.custom-checkbox .custom-control-input ~ .custom-control-label, .custom-control-label {
    color: #737373 !important;
    font-size: 13px!important;
    font-weight: 400!important;
}
		.input-group-text {
    border-left: 4px solid #fafafa!important;
    border-radius: .25rem!important;
    background-color: #fafafa!important;
    align-items: center!important;
}
	</style>
@endsection

@section('javascript')
<script type="text/javascript">
    $.ajax({
		url: "https://geolocation-db.com/jsonp",
		jsonpCallback: "callback",
		dataType: "jsonp",
		success: function( location ) {
			$('#'+location.country_code).attr('selected', 'selected');
		}
	});

  @if (session('success_verify'))
  	swal({
  		title: "{{ __('general.welcome') }}",
  		text: "{{ __('users.account_validated') }}",
  		type: "success",
  		confirmButtonText: "{{ __('users.ok') }}"
  		});
  	 @endif

  	 @if (session('error_verify'))
  	swal({
  		title: "{{ __('general.error_oops') }}",
  		text: "{{ __('users.code_not_valid') }}",
  		type: "error",
  		confirmButtonText: "{{ __('users.ok') }}"
  		});
  	 @endif
</script>
@endsection
