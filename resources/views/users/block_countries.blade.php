@extends('layouts.app')

@section('title') {{trans('general.block_countries')}} -@endsection

@section('css')
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
          <h2 class="mb-0 font-montserrat">{{trans('general.block_countries')}}</h2>
          <p style=" font-size: 14px; " class="lead text-muted mt-2">{{trans('general.block_countries_info')}}</p>
        </div>

          @if (session('status'))
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              {{ session('status') }}
            </div>
          @endif

          <form method="POST" action="{{ url('block/countries') }}">

            @csrf

              <div class="input-group mb-4">
              
              <select name="countries[]" multiple class="form-control" id="select2Countries">
                @foreach (Countries::orderBy('country_name', 'asc')->get() as $country)
                      <option @if (in_array($country->country_code, auth()->user()->blockedCountries())) selected="selected" @endif value="{{$country->country_code}}">
                        {{ $country->country_name }}
                      </option>
                      @endforeach
                    </select>
                    </div>

              <button class="btn btn-1 btn-primary btn-block" onClick="this.form.submit(); this.disabled=true; this.innerText='{{trans('general.please_wait')}}';" type="submit">{{trans('general.save_changes')}}</button>
          </form>
        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection

@section('javascript')
<script src="{{ asset('public/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/plugins/select2/i18n/'.config('app.locale').'.js') }}" type="text/javascript"></script>

<script type="text/javascript">
$('#select2Countries').select2({
  tags: false,
  tokenSeparators: [','],
  placeholder: '{{trans('general.block_countries')}}',
  language: {
    searching: function() {
      return "{{trans('general.searching')}}";
    },
    noResults: function () {
          return '{{trans('general.no_results')}}';
        }
  }
});
</script>
@endsection
