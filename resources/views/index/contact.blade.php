@extends('layouts.app')

@section('title') {{trans('general.contact')}} -@endsection

@section('css')
  <script type="text/javascript">
      var error_scrollelement = {{ count($errors) > 0 ? 'true' : 'false' }};
  </script>
@endsection

@section('content')
  <div class="jumbotron home m-0">
    <div class="container pt-lg-md">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div style="border: 1px solid #dbdbdb!important; border-radius: 4px !important;" class="card bg-white shadow border-0 b-radio-custom">
            <div class="card-body px-lg-5 py-lg-5">

              @if (session('notification'))
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    			<span aria-hidden="true">×</span>
                    			</button>

                        {{ session('notification') }}
                      </div>
                    @endif

              @include('errors.errors-forms')
				
				  <div class="p-0 mb-4">
            <div class="text-center mb-2"> <h2  class="mb-0 font-montserrat">
            Fale connosco
          </h2></div>
			  <p style="color:black;font-size: 14px;font-weight: 400;" class="text-center">{{ trans('general.subtitle_contact') }}</p>
          </div>

              <form method="POST" action="{{ url('contact') }}" id="contactForm">
                  @csrf

                <div class="form-group mb-3">
                  <div class="input-group">
                    
                    <input class="form-control" required value="{{Auth::user()->name ??  old('full_name')}}" placeholder="{{trans('auth.full_name')}}" name="full_name" type="text">
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                   
                    <input name="email" required type="email" value="{{Auth::user()->email ??  old('email')}}" class="form-control" placeholder="Email válido">
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group ">
                  
                    <input name="subject" required type="text" value="{{old('subject')}}" class="form-control" placeholder="{{ trans('general.subject') }}">
                  </div>
                </div>

                <div class="form-group">
                  <textarea placeholder="Mensagem" name="message" required rows="4" class="form-control">{{old('message') }}</textarea>
                  </div><!-- End Form Group -->

                  @if ($settings->link_terms != '' && $settings->link_privacy != '')
                  <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" required id=" customCheckLogin" name="agree_terms_privacy" type="checkbox">
                    <label class="custom-control-label" for=" customCheckLogin">
                      <span>{{trans('general.i_agree_with')}}
                        <a href="{{$settings->link_terms}}" target="_blank">{{trans('admin.terms_conditions')}}</a>
                        {{trans('general.and')}} <a href="{{$settings->link_privacy}}" target="_blank">{{trans('admin.privacy_policy')}}</a>
                      </span>
                    </label>
                  </div>
                @endif

                <div class="text-center">
                  @if ($settings->captcha_contact == 'on')
                  {!! NoCaptcha::displaySubmit('contactForm', __('auth.send').' ', ['data-size' => 'invisible', 'class' => 'btn btn-primary my-4 w-100']) !!}

                  {!! NoCaptcha::renderJs() !!}
                  @else
                  <button type="submit" class="btn btn-primary my-4 w-100">{{__('auth.send')}}</button>
                  @endif
                </div>
              </form>
              @if ($settings->captcha_contact == 'on')
                <small class="btn-block text-center">{{trans('auth.protected_recaptcha')}}
                  <a href="https://policies.google.com/privacy" target="_blank">{{trans('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('general.terms')}}</a>
                </small>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


<style>
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
