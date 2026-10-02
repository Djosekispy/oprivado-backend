@extends('layouts.app')

@section('title') {{auth()->user()->verified_id == 'yes' ? trans('general.edit_my_page') : trans('users.edit_profile')}} -@endsection

@section('css')
<style>
	.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 26px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.3s;
  border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
}

/* Quando estiver ativo */
input:checked + .slider {
  background-color: #ff6b4a; /* cor semelhante à imagem */
}

input:checked + .slider:before {
  transform: translateX(24px);
}
</style>
  <link rel="stylesheet" href="{{ asset('public/plugins/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('public/plugins/select2/select2.min.css') }}?v={{$settings->version}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

			<div class="py-5">
          <h2 class="mb-0 font-montserrat"> {{ auth()->user()->verified_id == 'yes' ? trans('general.edit_my_page') : trans('users.edit_profile')}}</h2>
        </div>

          @if (session('status'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                			<span aria-hidden="true">×</span>
                			</button>

                    {{ trans('admin.success_update') }}
                  </div>
                @endif

          @include('errors.errors-forms')

          @include('includes.alert-payment-disabled')

          <form method="POST" action="{{ url('settings/page') }}" id="formEditPage" accept-charset="UTF-8" enctype="multipart/form-data">

            @csrf

            <input type="hidden" id="featured_content" name="featured_content" value="{{auth()->user()->featured_content}}">

          <div class="form-group">
            <label>{{trans('auth.full_name')}}</label><sup>*</sup>
            <div class="input-group mb-4">
            
                <input class="form-control" name="full_name" placeholder="{{trans('auth.full_name')}}" value="{{auth()->user()->name}}" @if (auth()->user()->verified_id == 'yes') readonly @endif type="text">
            </div>
          </div><!-- End form-group -->

          <div class="form-group">
            <label>{{trans('auth.username')}}</label><sup>*</sup>
            <div class="input-group mb-2">
            <div class="input-group-prepend">
              <span class="input-group-text pr-0">{{Helper::removeHTPP(url('/'))}}/</span>
            </div>
                <input class="form-control" name="username" maxlength="25" placeholder="{{trans('auth.username')}}" value="{{auth()->user()->username}}" type="text">
            </div>
            
          </div><!-- End form-group -->

          <div class="form-group">
			   <label>{{trans('auth.email')}}</label>
                <input class="form-control" placeholder="{{trans('auth.email')}} *" readonly value="{{auth()->user()->email}}" type="text">
            </div><!-- End form-group -->

          <div class="row form-group mb-0">
			 
			   <div class="none col-md-6">
				   <label>{{trans('general.language')}}</label>
                <div class="input-group mb-4">
                
                <select name="language" class="form-control custom-select">
                  <option @if (auth()->user()->language == '') selected="selected" @endif value="">({{trans('general.language')}}) {{ __('general.not_specified') }}</option>
                  @foreach (Languages::orderBy('name')->get() as $languages)
                    <option @if (auth()->user()->language == $languages->abbreviation) selected="selected" @endif value="{{$languages->abbreviation}}">{{ $languages->name }}</option>
                  @endforeach
                  </select>
                  </div>
                </div>
			  
			  </div> <!-- ./col-md-6 -->

             <!-- ./col-md-6 -->
            <!-- End Row Form Group -->

              <div class="row form-group mb-0">
                  <div class="col-md-6">
					   <label>{{trans('general.birthdate')}}</label><sup>*</sup>
                      <div class="input-group">
                       
                        <input class="form-control datepicker" @if (auth()->user()->birthdate_changed == 'yes') disabled  @endif name="birthdate" placeholder="{{trans('general.birthdate')}} *"  value="{{ auth()->user()->birthdate ?? date(Helper::formatDatepicker(), strtotime(auth()->user()->birthdate))}}" autocomplete="off" type="text">
                      </div>
                      <small class="form-text text-muted mb-4">{{ trans('general.valid_formats') }} {{ now()->subYears(18)->format(Helper::formatDatepicker()) }}
                      </small>
                    </div><!-- ./col-md-6 -->

                    <div class="col-md-6">
						<label>{{trans('general.gender')}}</label>
                      <div class="input-group mb-4">
                     
                      <select name="gender" class="form-control custom-select">
                        <option @if (auth()->user()->gender == '' ) selected="selected" @endif value="">({{trans('general.gender')}}) {{ __('general.not_specified') }}</option>
                        @foreach ($genders as $gender)
                          <option @if (auth()->user()->gender == $gender) selected="selected" @endif value="{{$gender}}">{{ __('general.'.$gender) }}</option>
                        @endforeach
                        </select>
                        </div>
                      </div><!-- ./col-md-6 -->
                    </div><!-- End Row Form Group -->

			    @if (auth()->user()->verified_id == 'yes')  <div class="form-group">
        <label>Tipo de conteúdo </label><sup>*</sup>
        <div class="input-group mb-1">
             <select name="categories_id[]" multiple class="form-control categoriesMultiple" >
                              @foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
                                <option @if (in_array($category->id, $categories)) selected="selected" @endif value="{{$category->id}}">{{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}</option>
                                @endforeach
                              </select>
        </div> 
<small class="form-text text-muted mb-4">A sua conta será removida se adicionar categoria errada
                      <sup>*</sup></small>
    </div> @endif

              <div class="row form-group mb-0">
				  
				  <div class="col-md-6">
					<label>{{trans('general.select_your_country')}}</label>
                  <div class="input-group mb-4">
                 
                  <select name="countries_id" class="form-control custom-select">
                    <option value="">{{trans('general.select_your_country')}} *</option>
                        @foreach (Countries::orderBy('country_name')->get() as $country)
                          <option @if (auth()->user()->countries_id == $country->id ) selected="selected" @endif value="{{$country->id}}">{{ $country->country_name }}</option>
                          @endforeach
                        </select>
                        </div>
                  </div><!-- ./col-md-6 -->
				  
                 @if (auth()->user()->verified_id == 'yes')
            <div class="col-md-6">
				 <label>{{trans('users.website')}}</label>
                <div class="input-group mb-4">
                  
                  <input class="form-control" name="website" placeholder="{{trans('users.website')}}"  value="{{auth()->user()->website}}" type="text">
                </div>
              </div>  @endif 

                  <div class="none col-md-6">
                      <div class="input-group mb-4">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
                        </div>
                        <input class="form-control" name="city" placeholder="{{trans('general.city')}}"  value="{{auth()->user()->city}}" type="text">
                      </div>
                    </div><!-- ./col-md-6 -->

                    <div class="none col-md-6 @if (auth()->user()->verified_id == 'no') scrollError @endif">
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
                          </div>
                          <input class="form-control" name="address" placeholder="{{trans('general.address')}}"  value="{{auth()->user()->address}}" type="text">
                        </div>
                      </div><!-- ./col-md-6 -->

                      <div class="none col-md-6">
                          <div class="input-group mb-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                            </div>
                            <input class="form-control" name="zip" placeholder="{{trans('general.zip')}}"  value="{{auth()->user()->zip}}" type="text">
                          </div>
                        </div><!-- ./col-md-6 -->

              </div><!-- End Row Form Group -->

              @if (auth()->user()->verified_id == 'yes')
              <div class="row form-group mb-0">
                <div class="col-lg-12 py-2">
					<label>Redes sociais</label>
                </div>

                  <div class="col-md-6">
                      <div class="input-group mb-4">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                        </div>
                        <input class="form-control" name="facebook" placeholder="https://facebook.com/username"  value="{{auth()->user()->facebook}}" type="text">
                      </div>
                    </div><!-- ./col-md-6 -->

                    <div class="col-md-6">
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="bi-twitter-x"></i></span>
                          </div>
                          <input class="form-control" name="twitter" placeholder="https://twitter.com/username"  value="{{auth()->user()->twitter}}" type="text">
                        </div>
                      </div><!-- ./col-md-6 -->
                    </div><!-- End Row Form Group -->

                    <div class="row form-group mb-0">
                        <div class="col-md-6">
                            <div class="input-group mb-4">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                              </div>
                              <input class="form-control" name="instagram" placeholder="https://instagram.com/username"  value="{{auth()->user()->instagram}}" type="text">
                            </div>
                          </div><!-- ./col-md-6 -->

                          <div class="col-md-6">
                              <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                </div>
                                <input class="form-control" name="youtube" placeholder="https://youtube.com/username"  value="{{auth()->user()->youtube}}" type="text">
                              </div>
                            </div><!-- ./col-md-6 -->
                          </div><!-- End Row Form Group -->

                            <div class="row form-group mb-0">
                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi-snapchat"></i></span>
                                      </div>
                                      <input class="form-control" name="snapchat" placeholder="https://www.snapchat.com/add/username"  value="{{auth()->user()->snapchat}}" type="text">
                                    </div>
                                  </div><!-- ./col-md-6 -->

                                  <div class="col-md-6">
                                      <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="bi-tiktok"></i></span>
                                        </div>
                                        <input class="form-control" name="tiktok" placeholder="https://www.tiktok.com/@username"  value="{{auth()->user()->tiktok}}" type="text">
                                      </div>
                                    </div><!-- ./col-md-6 -->
                              </div><!-- End Row Form Group -->

                              <div class="row form-group mb-0">
                                  <div class="col-md-6">
                                      <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="bi-telegram"></i></span>
                                        </div>
                                        <input class="form-control" name="telegram" placeholder="https://t.me/username"  value="{{auth()->user()->telegram}}" type="text">
                                      </div>
                                    </div><!-- ./col-md-6 -->

                                    <div class="col-md-6">
                                        <div class="input-group mb-4">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-twitch"></i></span>
                                          </div>
                                          <input class="form-control" name="twitch" placeholder="https://www.twitch.tv/username"  value="{{auth()->user()->twitch}}" type="text">
                                        </div>
                                      </div><!-- ./col-md-6 -->
                                </div><!-- End Row Form Group -->      

                          <div class="form-group">
                            <label class="w-100"> Biografia <sup>*</sup>
                              <span id="the-count" class="float-right d-inline">
                                <span id="current"></span>
                                <span id="maximum">/ {{$settings->story_length}}</span>
                              </span>
                            </label>
                            <textarea name="story" id="story" rows="5" cols="40" class="form-control textareaAutoSize scrollError">{{auth()->user()->story ? auth()->user()->story : old('story') }}</textarea>

                          </div><!-- End Form Group -->
                        @endif
<div class="form-group mt-4 mb-4 d-flex align-items-center" id="adulto" style="gap:10px;border: 1px solid #ced4da;padding: 11px;border-radius: 5px;">
    
    <label class="switch mb-0">
        <input type="checkbox" 
               name="acceptSensitiveContent"
               {{ auth()->user()->acceptSensitiveContent ? 'checked' : '' }}>
        <span class="slider"></span>
    </label>
    
    <span style="font-size:14px; font-weight:400;">
        Aceito ver conteúdo sensível (adulto)
    </span>
</div>

			  
                          <!-- Alert -->
                          <div class="alert alert-danger my-3 display-none" id="errorUdpateEditPage">
                           <ul class="list-unstyled m-0" id="showErrorsUdpatePage"><li></li></ul>
                         </div><!-- Alert -->

			  
 
                          <button class="btn btn-1 btn-primary btn-block" data-msg-success="{{ trans('admin.success_update') }}" id="saveChangesEditPage" type="submit"><i></i> {{trans('general.save_changes')}}</button>
                  </form>
                </div><!-- end col-md-6 -->
              </div>
            </div>
  </section>
@endsection

@section('javascript')
  <script src="{{ asset('public/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
  @if (config('app.locale') != 'en')
    <script src="{{ asset('public/plugins/datepicker/locales/bootstrap-datepicker.'.config('app.locale').'.js') }}"></script>
  @endif

  <script src="{{ asset('public/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/plugins/select2/i18n/'.config('app.locale').'.js') }}" type="text/javascript"></script>

<script type="text/javascript">

@if (auth()->user()->verified_id == 'yes')
$('#current').html($('#story').val().length);
@endif

$('.categoriesMultiple').select2({
  tags: false,
  tokenSeparators: [','],
  maximumSelectionLength: {{$settings->limit_categories}},
  placeholder: '{{trans('admin.categories')}}',
  language: {
    maximumSelected: function() {
      return "{{trans('general.maximum_selected_categories', ['limit' => $settings->limit_categories])}}";
    },
    searching: function() {
      return "{{trans('general.searching')}}";
    },
    noResults: function () {
          return '{{trans('general.no_results')}}';
        }
  }
});

$('.datepicker').datepicker({
    format: '{{ Helper::formatDatepicker(true) }}',
    startDate: '01/01/1920',
    endDate: '{{ now()->subYears(18)->format(Helper::formatDatepicker()) }}',
    language: '{{config('app.locale')}}'
});

</script>
@endsection
