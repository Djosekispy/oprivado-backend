@extends('layouts.app')

@section('title') {{$title}} -@endsection

    @section('description_custom'){{$description ? $description : trans('seo.description')}}@endsection
    @section('keywords_custom'){{$keywords ? $keywords.',' : null}}@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
			<h2 class="mb-0 font-montserrat">Criadores de {{$title}}</h2>
        </div>
      </div>

<div class="row justify-content-center">

        @if ($users->count() != 0)
          <div class="col-md-9 mb-4">
			  
            <div id="containerWrapCreators">

              @foreach ($users as $user)
             
                @include('includes.listing-creators')
             
              @endforeach

              @include('includes.paginator-creators')

            </div><!-- row -->
          </div><!-- col-md-9 -->

        @else
          <div class="col-md-9">
            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="fa fa-user-slash ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{trans('general.not_found_creators_category')}}</h4>
				<a href="javascript:history.back();" class="error-link mt-5">
						<i class="fa fa-long-arrow-alt-left mr-2"></i> Voltar nas categorias
					</a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection

@section('javascript')
<script src="{{ url('public/js/paginator-creators.js') }}?v={{$settings->version}}"></script>
@endsection
