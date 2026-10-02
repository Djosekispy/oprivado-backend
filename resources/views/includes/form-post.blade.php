@include('includes.alert-payment-disabled')
  <div class="w-100 mb-1 display-none pl-3" id="dateScheduleContainer">
    <small class="font-weight-bold">
     <i class="bi-calendar-event mr-1"></i> {{ __('general.date_schedule') }} <span id="dateSchedule"></span>
    </small>
    <a href="javascript:void(0)" id="deleteSchedule" class="text-danger p-1 px-2 btn-tooltip-form" data-toggle="tooltip" data-placement="top" title="{{__('general.delete')}}"><i class="fa fa-times-circle"></i></a>
    </div>

@if (is_null(auth()->user()->content_type))
  <div class="alert alert-danger my-3 alert-dismissible fade show mt-3" role="alert">
   Para lhe permitir fazer posts, precisa definir o tipo de conteúdo nas suas definições de perfil: <a style=" color: #fff; text-decoration: underline; " href="{{ url('settings/page') }}">
      Clique aqui</a>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if (auth()->check() && ! auth()->user()->planActive() && auth()->user()->free_subscription == 'no')
   <div class="alert alert-danger mb-3">
                 <ul class="list-unstyled m-0">
                   <li><i class="fa fa-exclamation-triangle"></i> {{__('general.alert_not_subscription')}} <a href="{{url('settings/subscription')}}" class="text-white link-border">{{__('general.activate')}}</a></li>
                 </ul>
               </div>
@endif

     
		   <form method="POST" action="{{url('update/create')}}" enctype="multipart/form-data" id="formUpdateCreate" @if (! auth()->user()->planActive() && auth()->user()->free_subscription == 'no') style="pointer-events: none; opacity: 0.6;" @endif>
      
        @csrf
      <div class="card mb-4 card-border-0 rounded-large shadow-large">
		  
        <div class="blocked display-none"></div>
        <div class="card-body pb-0">

          <div class="media">
          <div class="media-body position-relative">

            <textarea style="font-size:14px!important;" class="form-control textareaAutoSize border-0 emojiArea mentions" name="description" id="updateDescription" data-post-length="{{$settings->update_length}}" rows="4" cols="40" placeholder="Em que estás a pensar?"></textarea>
          </div>
        </div><!-- media -->

            <input class="custom-control-input d-none" id="customCheckLocked" type="checkbox" {{auth()->user()->post_locked == 'no' ? 'checked' : ''}} name="locked" value="no">

          <!-- Alert -->
          <div class="alert alert-danger my-3 display-none" id="errorUdpate">
           <ul class="list-unstyled m-0" id="showErrorsUdpate"></ul>
         </div><!-- Alert -->

        </div>
        <div class="card-footer bg-white border-0 pt-0 mb-2 rounded-large">
          <div class="justify-content-between align-items-center">

            <div class="form-group display-none" id="price" >
              <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text">{{$settings->currency_symbol}}</span>
              </div>
                  <input class="form-control isNumber" id="onlyNumber" autocomplete="off" name="price" placeholder="{{__('general.price')}}" type="text">
              </div>
            </div><!-- End form-group -->

            <div class="form-group display-none" id="titlePost" >
              <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="bi-type"></i></span>
              </div>
                  <input class="form-control" autocomplete="off" name="title" maxlength="100" placeholder="{{__('admin.title')}}" type="text">
              </div>
              <small class="form-text text-muted mb-4">
                {{ __('general.title_post_info', ['numbers' => 100]) }}
              </small>
            </div><!-- End form-group -->

            <div class="w-100 mb-2">
              <small id="previewImage"></small>
              <a href="javascript:void(0)" id="removePhoto" class="text-danger p-1 small display-none btn-tooltip-form" data-toggle="tooltip" data-placement="top" title="{{__('general.delete')}}"><i class="fa fa-times-circle"></i></a>
            </div>

            <div class="w-100 mb-2">
              <small id="previewEpub"></small>
              <a href="javascript:void(0)" id="removeEpub" class="text-danger p-1 small display-none btn-tooltip-form" data-toggle="tooltip" data-placement="top" title="{{__('general.delete')}}"><i class="fa fa-times-circle"></i></a>
            </div>

            <input type="file" name="photo[]" id="filePhoto" accept="image/*,video/mp4,video/x-m4v,video/quicktime,audio/mp3" multiple class="visibility-hidden filepond">

            <button type="button" class="btn btn-post btnMultipleUpload btn-tooltip-form e-none @if (auth()->user()->dark_mode == 'off') @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_media')}} ({{ __('general.media_type_upload') }})">
              <i class="feather icon-image f-size-20 align-bottom"></i> <span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Ficheiro</span>
            </button>
			  
			  <button type="button" class="btn btn-post btnMultipleUpload btn-tooltip-form e-none @if (auth()->user()->dark_mode == 'off') @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_media2')}} ({{ __('general.media_type_upload2') }})">
              <i class="feather icon-mic f-size-20 align-bottom"></i> <span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Aúdio</span>
            </button>

            @if ($settings->allow_zip_files)
            <input type="file" name="zip" id="fileZip" accept="application/x-zip-compressed" class="visibility-hidden">

            <button type="button" class="btn btn-post btn-tooltip-form p-bottom-8 e-none @if (auth()->user()->dark_mode == 'off') @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_file_zip')}}" onclick="$('#fileZip').trigger('click')">
              <i class="bi-file-earmark-zip f-size-20 align-bottom"></i>
            </button>
          @endif

          @if ($settings->allow_epub_files)
            <input type="file" name="epub" id="ePubFile" accept="application/epub+zip" class="visibility-hidden">

            <button type="button" class="btn btn-post btn-tooltip-form p-bottom-8 e-none @if (auth()->user()->dark_mode == 'off') @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_epub_file')}}" onclick="$('#ePubFile').trigger('click')">
              <i class="bi-book f-size-20 align-bottom"></i>
            </button>
          @endif

            <button type="button" id="setPrice" class="btn btn-post btn-tooltip-form e-none @if (auth()->user()->dark_mode == 'off') @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.price_post_ppv')}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-dollar-sign-icon lucide-badge-dollar-sign"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> <span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Preço</span>
            </button>
			  
			  
            @if ($settings->live_streaming_status == 'on')
              <button type="button" data-toggle="tooltip" data-placement="top" title="{{__('general.stream_live')}}" class="btn btn-post p-bottom-8 btn-tooltip-form e-none btnCreateLive @if (auth()->user()->dark_mode == 'off') @else text-white @endif rounded-pill">
                  <i class="bi-broadcast f-size-20 align-bottom"></i> <span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Live</span>
              </button>
            @endif

            @if ($settings->allow_scheduled_posts)
              <button type="button" data-toggle="tooltip" data-placement="top" title="{{__('general.schedule')}}" class="btn btn-post p-bottom-8 btn-tooltip-form e-none btnSchedulePost @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill">
                  <i style=" font-size: 18px; " class="bi-calendar-event text-black f-size-20 align-bottom"></i><span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Agendar</span>
              </button>

              <input type="hidden" name="scheduled_date" id="inputScheduled" value="">
            @endif

            @if (!$settings->disable_free_post)
            <button type="button" id="contentLocked" class="btn btn-post btn-tooltip-form e-none @if (auth()->user()->dark_mode == 'off') @else text-white @endif rounded-pill {{auth()->user()->post_locked == 'yes' ? '' : 'unlock'}}" data-toggle="tooltip" data-placement="top" title="{{__('users.locked_content')}}">
              <i class="feather icon-{{auth()->user()->post_locked == 'yes' ? '' : 'un'}}lock f-size-20 align-bottom"></i> <span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Público</span>
            </button>
            @endif
			  
            <div class="d-inline-block float-right mt-3 mt-lg-1 position-relative w-100-mobile">

              <span class="d-inline-block float-right position-relative rounded-pill w-100-mobile">
                <span class="btn-blocked display-none"></span>
  
                <button type="submit" {{ is_null(auth()->user()->content_type) || (! auth()->user()->planActive() && auth()->user()->free_subscription == 'no') ? 'disabled' : '' }} 
						class="btn btn-sm btn-primary rounded-pill float-right e-none w-100-mobile" data-empty="{{__('general.empty_post')}}" data-error="{{__('general.error')}}" data-msg-error="{{__('general.error_internet_disconnected')}}" id="btnCreateUpdate"
						data-blocked="{{ is_null(auth()->user()->content_type) || (! auth()->user()->planActive() && auth()->user()->free_subscription == 'no') ? 'true' : 'false' }}"
						>
                  <i></i> <span id="textPostPublish">{{__('general.publish')}}</span>
                </button>
              </span>

              <div id="the-count" class="none float-right my-2 mr-2">
                <small id="maximum">{{$settings->update_length}}</small>
              </div>
            </div>
          </div>
        </div><!-- card footer --> <p style="display:none!important;  margin-top: 8px !important; border-radius: 3px !important; margin-left: 20px !important; margin-right: 20px !important;" class="help-block margin-bottom-zero fee-wrap">
		  <span style="color: #5c5c5c !important;" class="mt-0 btn-block w-100">Regra 8º: Oprivado não é plataforma de prostituição, é proíbido postar conteúdos relacionados com prostituição, incluindo venda de serviços sexuais ou promoção dessa prática, pois pode constituir crime em Angola, conforme o artigo 189.º do Código Penal.</span>
              </p>
      </div>  <!-- card -->
    </form>

    <!-- Post Pending -->
    <div class="alert alert-primary display-none card-border-0" role="alert" id="alertPostPending">
      <button type="button" class="close mt-1" id="btnAlertPostPending">
        <span aria-hidden="true">
          <i class="bi bi-x-lg"></i>
        </span>
      </button>

        <i class="bi-info-circle mr-1"></i> {{ __('general.alert_post_pending_review') }}
        <a href="{{ url('my/posts') }}" class="link-border text-white">{{ __('general.my_posts') }}</a>
    </div>

    <!-- Post Schedule -->
    <div class="alert alert-primary display-none card-border-0" role="alert" id="alertPostSchedule">
      <button type="button" class="close mt-1" id="btnAlertPostSchedule">
        <span aria-hidden="true">
          <i class="bi bi-x-lg"></i>
        </span>
      </button>

        <i class="bi-info-circle mr-1"></i> {{ __('general.alert_post_schedule') }}
        <a href="{{ url('my/posts') }}" class="link-border text-white">{{ __('general.my_posts') }}</a>
    </div>

@include('includes.modal-post-warning')


<script>
document.addEventListener('DOMContentLoaded', function () {

    const btn       = document.getElementById('btnCreateUpdate');
    const textarea  = document.getElementById('updateDescription');
    const fileInput = document.getElementById('filePhoto');
    const zipInput  = document.getElementById('fileZip');
    const epubInput = document.getElementById('ePubFile');
    const form      = document.getElementById('formUpdateCreate');

    const userId = {{ auth()->id() ?? 0 }};
    const STORAGE_KEY = 'oprivado_post_rules_accepted_' + userId;

    function hasAcceptedRules() {
        return localStorage.getItem(STORAGE_KEY) === '1';
    }

    function acceptRules() {
        localStorage.setItem(STORAGE_KEY, '1');
    }

    function lockForm() {
        if (textarea) {
            textarea.setAttribute('readonly', true);
            textarea.style.cursor = 'not-allowed';
            textarea.style.opacity = '0.7';
        }
        if (btn) {
            btn.disabled = true;
            btn.classList.add('disabled');
        }
    }

    function unlockForm() {
        if (textarea) {
            textarea.removeAttribute('readonly');
            textarea.style.cursor = '';
            textarea.style.opacity = '';
        }
        checkContent();
    }

    if (textarea) {
        textarea.addEventListener('focus', function (e) {
            if (!hasAcceptedRules()) {
                e.preventDefault();
                lockForm();
                $('#postWarningModal').modal('show');
            }
        });

        textarea.addEventListener('click', function (e) {
            if (!hasAcceptedRules()) {
                e.preventDefault();
                lockForm();
                $('#postWarningModal').modal('show');
            }
        });
    }


    $('#confirmChangePost').on('click', function () {
        acceptRules();
        $('#postWarningModal').modal('hide');
        unlockForm();

        setTimeout(function () {
            if (textarea) textarea.focus();
        }, 300);
    });

 
    if (btn && btn.dataset.blocked === 'true') {
        btn.disabled = true;
        btn.classList.add('disabled');

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
            });
        }
        return;
    }

    function checkContent() {
      
        if (!hasAcceptedRules()) {
            if (btn) {
                btn.disabled = true;
                btn.classList.add('disabled');
            }
            return;
        }

        let hasText = textarea && textarea.value.trim().length > 0;
        let hasFiles =
            (fileInput && fileInput.files.length > 0) ||
            (zipInput  && zipInput.files.length > 0) ||
            (epubInput && epubInput.files.length > 0);

        if (!btn) return;

        if (!hasText && !hasFiles) {
            btn.disabled = true;
            btn.classList.add('disabled');
        } else {
            btn.disabled = false;
            btn.classList.remove('disabled');
        }
    }

    if (textarea)  textarea.addEventListener('input', checkContent);
    if (fileInput) fileInput.addEventListener('change', checkContent);
    if (zipInput)  zipInput.addEventListener('change', checkContent);
    if (epubInput) epubInput.addEventListener('change', checkContent);

    checkContent();
});
</script>