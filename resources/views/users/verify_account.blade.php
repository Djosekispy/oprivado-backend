@extends('layouts.app')

@section('title') {{trans('general.verify_account')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
		 <div class="py-5">
          <h2 class="mb-0 font-montserrat">{{trans('general.verify_account')}}</h2>
        </div>
			
@if (auth()->user()->verified_id != 'yes')
<div class="modal fade" id="verifyWarningModal" tabindex="-1" role="dialog" aria-labelledby="verifyWarningModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
		  <h5 class="modal-title font-montserrat" id="verifyWarningModalLabel">
         <i class="fa fa-exclamation-triangle text-red mr-2"></i> Aviso importante
        </h5>
      </div>

      <div class="modal-body">
          <p style="font-size: 14px;" class="lead text-muted mb-0">
            Apenas perfis reais podem se tornar criadores de conteúdo no Oprivado. O não cumprimento das Regras da plataforma resultará na suspensão ou remoção imediata da conta de criador de conteúdo sem aviso prévio:  <a target="_blank" style="text-decoration: underline; font-weight: 400!important; " href="https://oprivado.ao/p/regras">Ler todas as regras <i class="bi-arrow-up-right"></i>
        </a> 
            <br><br>
            A criação de perfil falso com o objetivo de enganar ou prejudicar pessoas dentro ou fora do Oprivado não é tolerada. 
            Nesses casos, os documentos associados à conta poderão ser divulgados às partes lesadas e às autoridades competentes, quando aplicável.
          </p>
      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn btn-primary px-5" data-dismiss="modal">Entendi</button>
      </div>
    </div>
  </div>
</div>
@endif
          @if (session('status'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                			<span aria-hidden="true">×</span>
                			</button>

                    {{ session('status') }}
                  </div>
                @endif

          @include('errors.errors-forms')

        @if ($settings->requests_verify_account == 'on'
            && auth()->user()->verified_id != 'yes'
            && auth()->user()->verificationRequests() != 1
            && auth()->user()->verified_id != 'reject')

            @if (auth()->user()->countries_id != ''
                && auth()->user()->birthdate != ''
                && auth()->user()->cover != ''
                && auth()->user()->cover != $settings->cover_default
                && auth()->user()->avatar != $settings->avatar
              )

          <div class="alert alert-primary mr-1">
          <span class="alert-inner--text"> {{trans('general.warning_verification_info')}}</span>
        </div>

          <form method="POST" id="formVerify" action="{{ url('settings/verify/account') }}" accept-charset="UTF-8" enctype="multipart/form-data">

            @csrf
			  
			  <div class="none form-group">
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
              </div>
              <input class="form-control" name="address" placeholder="{{trans('general.address')}}" value="Angola" type="text">
            </div>
            </div>

            <div class="none form-group">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
                  </div>
                  <input class="form-control" name="city" placeholder="{{trans('general.city')}}" value="Angola" type="text">
                </div>
              </div>

              @if ($settings->zip_verification_creator)
              <div class="form-group">
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                    </div>
                    <input class="form-control" name="zip" placeholder="{{trans('general.zip')}}" value="{{old('zip')}}" type="text">
                  </div>
                </div>
                @endif

                @if (auth()->user()->countries_id == 1)
                  <div class="mb-5 text-center">

                    <h6 class="text-muted">{{trans('general.form_w9_required')}}</h6>
                      <input type="file" name="form_w9" id="fileVerifiyAccountFormW9" accept="application/pdf" class="visibility-hidden">
                      <button class="btn btn-1 w-100 btn-outline-primary mb-2 border-dashed" type="button" id="btnFileFormW9">{{trans('general.upload_form_w9')}} (PDF) {{trans('general.maximum')}}: {{Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}}</button>
                      <span class="btn-block mb-2" id="previewImageFormW9"></span>

                    <h6 class="btn-block text-center font-weight-bold">
                      <a href="https://www.irs.gov/pub/irs-pdf/fw9.pdf" target="_blank">{{ __('general.complete_form_W9_here') }} <i class="feather icon-external-link"></i></a>
                    </h6>
                  </div>
                @endif
			  
                <!-- Image Front -->
                <div class="mb-5 mt-5 text-center previewImageVerification">
                  <h6 class="text-muted">Foto frontal do seu documento de identidade</h6>
                    <input type="file" name="image" id="fileVerifiyAccount" accept="image/*" class="fileVerifiyAccount visibility-hidden">
                    <button style="height: 80px;" class="btn btn-1 w-100 btn-outline-primary mb-2 border-dashed btnFilePhoto" type="button" id="btnFilePhoto">Adicionar imagem</button>
                    <span style=" font-size: 12px; " class="btn-block mb-2 previewImage" id="previewImage"></span>
                </div>

                <!-- Image Reverse -->
                <div class="mb-5 text-center previewImageVerification">
                  <h6 class="text-muted">Foto traseira do seu documento de identidade</h6>
                    <input type="file" name="image_reverse" id="fileVerifiyAccount" accept="image/*" class="fileVerifiyAccount visibility-hidden">
                    <button style="height: 80px;" class="btn btn-1 w-100 btn-outline-primary mb-2 border-dashed btnFilePhoto" type="button" id="btnFilePhoto">Adicionar imagem</button>
					
                    <span style=" font-size: 12px; " class="btn-block mb-2 previewImage" id="previewImage"></span>
                </div>

                <!-- Image Selfie -->
                <div class="mb-3 text-center previewImageVerification">
                  <h6 class="text-muted">Foto segurando o seu documento de identidade ao lado do seu rosto</h6>
                    <input type="file" name="image_selfie" id="fileVerifiyAccount" accept="image/*" class="fileVerifiyAccount visibility-hidden">
                    <button style="height: 80px;" class="btn btn-1 w-100 btn-outline-primary mb-2 border-dashed btnFilePhoto" type="button" id="btnFilePhoto">Adicionar imagem</button>
                    <span style=" font-size: 12px; " class="btn-block mb-2 previewImage" id="previewImage"></span>
                </div>
			  
                <button class="btn btn-1 btn-primary btn-block mt-5" id="sendData" type="submit">Solicitar verificação</button>
			  
          </form>

        @else

          <div class="alert alert-primary">
          <span class="alert-inner--text"><i class="fa fa-exclamation mr-1"></i> {{trans('general.complete_profile_alert')}}</span>

          <ul class="list-unstyled">
            <br>

            @if (auth()->user()->avatar == $settings->avatar)
              <li>
                <i class="far fa-times-circle"></i> {{ __('general.set_avatar') }} <a href="{{ url(auth()->user()->username) }}" class=" link-border">{{ __('general.upload') }} <i class="feather icon-arrow-right"></i></a>
              </li>
            @endif

            @if (auth()->user()->cover == '' || auth()->user()->cover == $settings->cover_default)
            <li>
              <i class="far fa-times-circle"></i> {{ __('general.set_cover') }} <a href="{{ url(auth()->user()->username) }}" class=" link-border">{{ __('general.upload') }} <i class="feather icon-arrow-right"></i></a>
            </li>
          @endif

          @if (auth()->user()->countries_id == '')
            <li>
              <i class="far fa-times-circle"></i> {{ __('general.set_country') }} <a href="{{ url('settings/page') }}" class=" link-border">{{ __('admin.edit') }} <i class="feather icon-arrow-right"></i></a>
            </li>
            @endif

            @if (auth()->user()->birthdate == '')
            <li>
              <i class="far fa-times-circle"></i> {{ __('general.set_birthdate') }} <a href="{{ url('settings/page') }}" class=" link-border">{{ __('admin.edit') }} <i class="feather icon-arrow-right"></i></a>
            </li>
          @endif
          </ul>
        </div>

            @endif

        @elseif (auth()->user()->verificationRequests() == 1)
          <div class="alert alert-primary alert-dismissible text-center fade show" role="alert">
            <span class="alert-inner--icon mr-2"><i class="fa fa-info-circle"></i></span>
          <span class="alert-inner--text">{{trans('admin.pending_request_verify')}}</span>
        </div>
      @elseif (auth()->user()->verified_id == 'reject')
        <div class="alert alert-danger alert-dismissible text-center fade show" role="alert">
          <span class="alert-inner--icon mr-2"><i class="fa fa-info-circle"></i></span>
        <span class="alert-inner--text">{{trans('admin.rejected_request')}}</span>
      </div>
    @elseif (auth()->user()->verified_id != 'yes' && $settings->requests_verify_account == 'off')
      <div class="alert alert-primary alert-dismissible text-center fade show" role="alert">
        <span class="alert-inner--icon mr-2"><i class="fa fa-info-circle"></i></span>
      <span class="alert-inner--text">{{trans('general.info_receive_verification_requests')}}</span>
    </div>

        @else
			
			<div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="bi bi-check-lg ico-no-result"></i>
            </span>
          <h4 class="font-weight-light">Conta verificada</h4>
          <a class="btn btn-primary" href="/">
            <i class="bi-plus"></i> Postar conteúdos
          </a>
          </div>
        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>

@if (auth()->user()->verified_id != 'yes')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    $('#verifyWarningModal').modal('show');
  });
</script>
@endif

@endsection
