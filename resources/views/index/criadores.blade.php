@extends('layouts.app')

@section('title') {{trans('general.criador')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
			<h2 class="mb-0 font-montserrat">Criadores de conteúdos</h2>
        </div>
      </div>
		
		@include('includes.listing-categories')
	
<style>
	.card.p-2.text-center.border-0.bg-transparent {
    position: fixed!important;
    bottom: 0px!important;
}</style>	
@endsection

