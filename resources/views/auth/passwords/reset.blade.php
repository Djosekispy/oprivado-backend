@extends('layouts.app')

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
          <div class="card bg-white border b-radio-custom">

            <div class="p-4">
              <h6 class="text-center mb-0 text-black">
                {{__('auth.reset_password')}}
              </h6>
              </div>

            <div class="card-body px-lg-5 py-lg-5">

              @if (session('status'))
                      <div class="alert alert-success">
                        {{ session('status') }}
                      </div>
                    @endif

              @include('errors.errors-forms')

              <form method="POST" action="{{url('password/reset')}}" id="passwordResetForm">
                  @csrf
                  <input type="hidden" name="token" value="{{$token}}">

                <div class="form-group mb-3">
                  <div class="input-group ">
                  
                    <input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" required type="text">
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group" id="showHidePassword">
                    
                    <input style="border-right: 0px solid!important;" name="password" type="password" class="form-control" required placeholder="{{__('auth.password')}}">
                    <div style="border-left: 0px solid!important; background-color: #fafafa!important;" class="input-group-append">
                      <span style="
    background-color: #fafafa !important;
" class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    
                    <input name="password_confirmation" type="password" class="form-control" required placeholder="{{__('auth.confirm_password')}}">
                  </div>
                </div>

                <div class="text-center">
                  @if ($settings->captcha == 'on')
                  {!! NoCaptcha::displaySubmit('passwordResetForm', __('auth.reset_password'), ['data-size' => 'invisible', 'class' => 'btn btn-primary my-4 w-100']) !!}

                  {!! NoCaptcha::renderJs() !!}
                  @else
                  <button type="submit" class="btn btn-primary my-4 w-100">{{__('auth.reset_password')}}</button>
                  @endif
                </div>
              </form>

              @if ($settings->captcha == 'on')
                <small class="btn-block text-center">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
              @endif

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 <style>
.form-control {
    background-color: #fafafa !important;
}
.form-control {
    font-size: 13px;
    color: #495057;
    border: 1px solid #ced4da;
    border-radius: 4px;
    height: calc(2.75rem + -5px);
    transition: none;
    font-family: Inter Tight, system-ui !important;
}</style>
@endsection
