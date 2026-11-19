@extends('layouts.app')

@section('title'){{__('general.messages')}} -@endsection

@section('css')
  <script type="text/javascript">
      var subscribed_active = {{ $subscribedToYourContent || $subscribedToMyContent || auth()->user()->isSuperAdmin() || $user->isSuperAdmin() ? 'true' : 'false' }};
      var user_id_chat = {{ $user->id }};
      var msg_count_chat = {{ $messages->count() }};
  </script>

  <style>
    @media (min-width: 991px) {
    .fileuploader-theme-thumbnails .fileuploader-thumbnails-input,
    .fileuploader-theme-thumbnails .fileuploader-items-list .fileuploader-item {
      width: calc(14% - 16px);
      padding-top: 12%;
      }
    }

    .voice-message-player {
      min-width: 0;
    }

    .voice-message-audio-container {
      flex: 1 1 auto;
      min-width: 0;
    }

    .voice-message-audio-container .plyr {
      width: 100%;
      min-width: 0;
    }

    .voice-message-speed,
    .voice-message-remove {
      flex: 0 0 auto;
      line-height: 1;
    }

    .voice-recording-indicator {
      gap: .45rem;
    }

    .voice-recording-dot {
      width: .55rem;
      height: .55rem;
      border-radius: 50%;
      background-color: currentColor;
      animation: voice-recording-pulse 1s ease-in-out infinite;
    }

    .voice-recording-waveform {
      display: inline-flex;
      align-items: center;
      gap: 2px;
      height: 1.25rem;
    }

    .voice-recording-waveform span {
      width: 2px;
      height: 45%;
      border-radius: 2px;
      background-color: currentColor;
      animation: voice-recording-wave .65s ease-in-out infinite alternate;
    }

    .voice-recording-waveform span:nth-child(3n) { height: 90%; animation-delay: -.4s; }
    .voice-recording-waveform span:nth-child(3n + 1) { height: 65%; animation-delay: -.2s; }
    .voice-recording-waveform span:nth-child(3n + 2) { height: 35%; animation-delay: -.55s; }

    @keyframes voice-recording-pulse {
      50% { opacity: .35; }
    }

    @keyframes voice-recording-wave {
      to { transform: scaleY(.35); }
    }

    @media (prefers-reduced-motion: reduce) {
      .voice-recording-dot,
      .voice-recording-waveform span {
        animation: none;
      }
    }
  </style>
@endsection

@section('content')
<section class="section section-sm pb-0 h-100 section-msg position-fixed">
    <div class="container container-full-w h-100">
      <div class="row justify-content-center h-100">

        <div class="col-md-3 h-100 p-0 border-left second wrapper-msg-inbox" id="messagesContainer">
          @include('includes.sidebar-messages-inbox')
        </div>

  <div class="col-md-9 h-100 p-0 first">

  <div class="card w-100 rounded-0 h-100 border-top-0">
    <div class="card-header bg-white pt-4">
      <div class="media">
        <a href="{{url()->previous()}}" class="mr-3"><i class="fa fa-arrow-left"></i></a>
        <a href="{{url($user->username)}}" class="mr-3">
          <span class="position-relative user-status @if ($user->active_status_online == 'yes') @if (Helper::isOnline($user->id)) user-online @else user-offline @endif @endif d-block">
            <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" class="rounded-circle" width="40" height="40">
          </span>
      </a>

        <div class="media-body">
          <h6 class="m-0">
            <a style=" font-size: 15px; font-weight: 400!important; color: black!important; " href="{{url($user->username)}}">
              {{$user->hide_name == 'yes' ? $user->username : $user->name}}
            </a>

            @if ($user->verified_id == 'yes')
              <small class="verified">
                   <i class="bi bi-patch-check-fill"></i>
                 </small>
            @endif
          </h6>

        @if ($user->active_status_online == 'yes')

          @if ($user->hide_last_seen == 'no')
           <small>{{ __('general.active') }}</small>

           <span id="timeAgo">
             <small class="timeAgo @if (Helper::isOnline($user->id)) display-none @endif" id="lastSeen" data="{{ date('c', strtotime($user->last_seen ?? $user->date)) }}"></small>
            </span>
          @else
            {{'@'.$user->username}}
            @endif

          @else
            {{'@'.$user->username}}
            @endif

        </div>

        @if ($user->verified_id == 'yes' 
            && $settings->live_streaming_private == 'on' 
            && $user->allow_live_streaming_private == 'on' 
            && !auth()->user()->isRestricted($user->id)
            )
        <a href="javascript:void(0);" class="f-size-20 text-muted float-right mr-3 text-decoration-none @if (Helper::isOnline($user->id)) requestLivePrivateModal @else buttonDisabled @endif" @if (Helper::isOnline($user->id)) data-toggle="tooltip" data-placement="bottom" title="{{ __('general.request_private_live_stream') }}" @endif role="button">
					<i class="feather icon-video"></i>
				</a>
        @endif

        <a href="javascript:void(0);" class="f-size-20 text-muted float-right" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="fa fa-ellipsis-h"></i>
				</a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">

        @if ($messages->count() != 0 && $settings->users_can_delete_messages)
					{!! Form::open([
						'method' => 'POST',
						'url' => "conversation/delete/$user->id",
						'class' => 'd-inline'
					]) !!}

					{!! Form::button('<i class="feather icon-trash-2 mr-2"></i> '.__('general.delete'), ['class' => 'dropdown-item actionDelete']) !!}
					{!! Form::close() !!}

          @endif

          @if (auth()->user()->isRestricted($user->id))
            <button type="button" class="dropdown-item removeRestriction" data-user="{{$user->id}}" id="restrictUser">
              <i class="fas fa-ban mr-2"></i> {{__('general.remove_restriction')}}
            </button>

          @else
            <button type="button" class="dropdown-item" data-user="{{$user->id}}" id="restrictUser">
              <i class="fas fa-ban mr-2"></i> {{__('general.restrict')}}
            </button>
          @endif
	      </div>

      </div>

    </div>

    <div class="content px-4 py-3 custom-scrollbar container-msg" id="contentDIV" data="{{$user->id}}">

      @if ($messages->count() != 0)
      <div class="flex-column d-flex justify-content-center text-center h-100">
        <div class="w-100" id="loadAjaxChat">
          <div class="spinner-border text-primary" role="status"></div>
        </div>
      </div>
    @endif
      </div><!-- contentDIV -->
	  
	  @if ($user->block_message == 'Yes')

<div class="card-footer bg-white position-relative">
  <div class="alert alert-danger m-0">
    <i class="fa fa-ban mr-2"></i>
    O usuário desactivou as mensagens.
  </div>
</div>

	  @elseif (auth()->user()->is_pro == 0 && in_array(auth()->user()->verified_id, ['no', 'reject']))
	 <div class="card-footer bg-white position-relative">
		  
		  <div class="bg-white position-relative">
        <div class="alert alert-primary m-0 alert-dismissible fade show" role="alert">
          <i class="fa fa-info-circle mr-2"></i>
          Deve se tornar <a href="{{ url(auth()->user()->username) }}">Pro <i style="font-size: 11px;color: #fd7350;" class="bi-star-fill mr-1"></i></a> para conseguir conversar com qualquer utilizador.
        </div>
      </div>
	</div>
	  
	@else							  
	  
      @if (!auth()->user()->checkRestriction($user->id) && $user->allow_dm)
         <p style="font-size: 11px;margin-bottom: -13px;" class="text-center"><i class="feather icon-alert-circle"></i> Ao conversar, trate as pessoas com respeito</p> <div style="border: 1px solid rgba(0, 0, 0, .125);
    border-radius: 15px !important;
    margin: 20px;" class="card-footer bg-white position-relative">

          @if ($subscribedToYourContent || $subscribedToMyContent || auth()->user()->isSuperAdmin() || $user->isSuperAdmin())

            <div class="w-100 display-none" id="previewFile">
              <div class="previewFile d-inline"></div>
              <a href="javascript:;" class="text-danger" id="removeFile"><i class="fa fa-times-circle"></i></a>
            </div>

            <div class="progress-upload-cover" style="width: 0%; top:0;"></div>

            <div class="blocked display-none"></div>

            <!-- Alert -->
            <div class="alert alert-danger my-3" id="errorMsg" style="display: none;">
             <ul class="list-unstyled m-0" id="showErrorMsg"></ul>
           </div><!-- Alert -->

            <form action="{{url('message/send')}}" method="post" accept-charset="UTF-8" id="formSendMsg" data-voice-upload-url="{{route('upload.message.voice')}}" enctype="multipart/form-data">
              <input type="hidden" name="id_user" id="id_user" value="{{$user->id}}">
              <input type="hidden" name="voice_token" id="voiceMessageToken" value="">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="file" name="zip" id="zipFile" accept="application/x-zip-compressed" class="visibility-hidden">

              <div class="w-100 mr-2 position-relative">
                <div>
                
                <div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar" aria-labelledby="dropdownMenuButton">
                  @include('includes.emojis')
                </div>
              </div>
                <textarea class="form-control textareaAutoSize emojiArea border-0" data-post-length="{{$settings->update_length}}" rows="1" placeholder="Mensagem..." id="message" name="message"></textarea>
              </div>

              <div class="form-group display-none mt-2" id="price">
                <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text">{{$settings->currency_symbol}}</span>
                </div>
                    <input class="form-control isNumber" autocomplete="off" name="price" placeholder="{{__('general.price')}}" type="text">
                </div>
              </div><!-- End form-group -->

              <div class="w-100 mb-2">
                <small id="previewImage"></small>
                <a href="javascript:void(0)" id="removePhoto" class="text-danger p-1 small display-none btn-tooltip" data-toggle="tooltip" data-placement="top" title="{{__('general.delete')}}"><i class="fa fa-times-circle"></i></a>
              </div>

              <div class="w-100 mb-2">
                <small id="previewEpub"></small>
                <a href="javascript:void(0)" id="removeEpub" class="text-danger p-1 small display-none btn-tooltip-form" data-toggle="tooltip" data-placement="top" title="{{__('general.delete')}}"><i class="fa fa-times-circle"></i></a>
              </div>

              <div id="voiceMessagePreview" class="w-100 mb-2 display-none">
                <div class="voice-message-player d-flex align-items-center">
                  <div class="voice-message-audio-container">
                    <audio id="voiceMessageAudio" class="voice-message-audio" preload="metadata"></audio>
                  </div>
                  <button type="button" class="btn btn-link text-muted p-0 ml-2 voice-message-speed" data-voice-speed aria-label="Velocidade de reprodução: 1x">1x</button>
                  <button type="button" id="discardVoiceMessage" class="btn btn-link text-danger p-0 ml-2 voice-message-remove" title="Remover áudio" aria-label="Remover áudio">
                    <i class="feather icon-x"></i>
                  </button>
                </div>
              </div>
              <div id="voiceMessageError" class="text-danger small mb-2 display-none" role="alert"></div>

              <input type="file" name="media[]" id="file" accept="image/*,video/mp4,video/x-m4v,video/quicktime,audio/mp3" multiple class="visibility-hidden filepond">

              <div class="justify-content-between mt-3 align-items-center">

                    <button type="button" class="btnMultipleUpload btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_media')}} ({{ __('general.media_type_upload') }})">
                      <i class="feather text-black icon-image align-bottom f-size-25"></i> <span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Ficheiro</span>
                    </button>

                    <button type="button" id="recordVoiceMessage" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="Gravar áudio" aria-label="Gravar áudio">
                      <i class="feather icon-mic align-bottom f-size-25"></i>
                    </button>
                    <div id="voiceRecordingControls" class="align-items-center display-none">
                      <div class="voice-recording-indicator text-danger d-inline-flex align-items-center">
                        <span class="voice-recording-dot" aria-hidden="true"></span>
                        <span id="voiceRecordingTimer">00:00</span>
                        <span class="voice-recording-waveform" aria-hidden="true">
                          <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                        </span>
                        <span class="sr-only" role="status" aria-live="polite">A gravar áudio</span>
                      </div>
                      <button type="button" id="stopVoiceRecording" class="btn btn-upload e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" title="Parar gravação" aria-label="Parar gravação">
                        <i class="feather icon-square align-bottom"></i>
                      </button>
                      <button type="button" id="cancelVoiceRecording" class="btn btn-upload e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" title="Cancelar gravação" aria-label="Cancelar gravação">
                        <i class="feather icon-x align-bottom"></i>
                      </button>
                    </div>

                    @if ($settings->allow_zip_files)
                    <button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_file_zip')}}" onclick="$('#zipFile').trigger('click')">
                      <i class="bi bi-file-earmark-zip text-black align-bottom f-size-25"></i>
                    </button>
                  @endif

                  @if (auth()->user()->verified_id == 'yes' && $settings->allow_epub_files)
                  <input type="file" name="epub" id="ePubFile" accept="application/epub+zip" class="visibility-hidden">

                  <button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_epub_file')}}" onclick="$('#ePubFile').trigger('click')">
                    <i class="bi-book f-size-23 align-bottom"></i>
                  </button>
                @endif

                  @if (auth()->user()->verified_id == 'yes')
                  <button type="button" id="setPrice" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.set_price_for_msg')}}">
                    <img width="23" heigth="23" style="" src="/public/img/usd-circle.svg"> <span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Preço</span>
                  </button>
                @endif

                @if ($user->verified_id == 'yes' && $settings->disable_tips == 'off')
                  <button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="modal" title="{{__('general.tip')}}" data-target="#tipForm" data-cover="{{Helper::getFile(config('path.cover').$user->cover)}}" data-avatar="{{Helper::getFile(config('path.avatar').$user->avatar)}}" data-name="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" data-userid="{{$user->id}}">
                   <img width="23" heigth="23" style="" src="/public/img/usd-circle.svg">  <span style="margin-top:2px; font-size: 10px; font-weight: 300; color: black; ">Gorjeta</span>
                  </button>
                @endif

                @if ($user->verified_id == 'yes' && $settings->gifts)
                <button type="button" data-toggle="modal" title="{{__('general.gifts')}}" data-target="#giftsForm" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill">
                  <i class="bi-gift f-size-25 align-bottom"></i>
                </button>
                @endif

          <div class="d-inline-block float-right rounded-pill mt-1 position-relative">
            <div class="btn-blocked display-none"></div>
            <button type="submit" id="button-reply-msg" disabled data-send="{{ __('auth.send') }}" data-wait="{{ __('general.send_wait') }}" class="btn btn-sm btn-primary float-right e-none w-100-mobile">Enviar</button>
            </div>

          </div><!-- media -->
        </form>
      @else
        <div class="alert alert-primary m-0 alert-dismissible fade show" role="alert">
          <i class="fa fa-info-circle mr-2"></i>
          @php
            $nameUser = $user->hide_name == 'yes' ? $user->username : $user->first_name;
          @endphp
        {!! __('general.show_form_msg_error_subscription_', ['user' => '<a href="'.url($user->username).'" class="link-border">'.$nameUser.'</a>']) !!}
      </div>
        @endif

      </div><!-- card footer -->
	  
      @else

      <div class="card-footer bg-white position-relative">
        <div class="alert alert-primary m-0 alert-dismissible fade show" role="alert">
          <i class="fa fa-info-circle mr-2"></i>
          {{ __('general.chat_unavailable') }}
        </div>
      </div>
    @endif
@endif
	  
    </div><!-- card -->
  </div><!-- end col-md-8 -->

  </div><!-- end row -->
</div><!-- end container -->
</section>
@include('includes.modal-new-message')

  @if ($user->verified_id == 'yes' 
            && $settings->live_streaming_private == 'on' 
            && $user->allow_live_streaming_private == 'on' 
            && !auth()->user()->isRestricted($user->id)
            )
    @include('includes.modal-live-private-request')
  @endif

@endsection

@section('javascript')
<script src="{{ asset('public/js/messages.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/fileuploader/fileuploader-msg.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/paginator-messages.js') }}"></script>
<script>
(function () {
  const form = document.getElementById('formSendMsg');
  if (!form) return;

  const recordButton = document.getElementById('recordVoiceMessage');
  const recordingControls = document.getElementById('voiceRecordingControls');
  const stopButton = document.getElementById('stopVoiceRecording');
  const cancelButton = document.getElementById('cancelVoiceRecording');
  const preview = document.getElementById('voiceMessagePreview');
  const audioPreview = document.getElementById('voiceMessageAudio');
  const recordingTimer = document.getElementById('voiceRecordingTimer');
  const discardButton = document.getElementById('discardVoiceMessage');
  const errorMessage = document.getElementById('voiceMessageError');
  const tokenInput = document.getElementById('voiceMessageToken');
  const messageInput = document.getElementById('message');
  const sendButton = document.getElementById('button-reply-msg');

  let activeRecording = null;
  let voiceBlob = null;
  let voiceExtension = null;
  let previewUrl = null;
  let uploading = false;
  let startingRecording = false;
  let recordingInterval = null;
  let recordingStartedAt = null;

  function showError(message) {
    errorMessage.textContent = message;
    errorMessage.classList.remove('display-none');
  }

  function clearError() {
    errorMessage.textContent = '';
    errorMessage.classList.add('display-none');
  }

  function hasOtherAttachment() {
    const fileList = form.querySelector('input[name="fileuploader-list-media"]');
    if (fileList && fileList.value) {
      try {
        const files = JSON.parse(fileList.value);
        if (Array.isArray(files) && files.length) return true;
      } catch (error) {
        return true;
      }
    }

    return Array.from(form.querySelectorAll('input[type="file"]'))
      .some(function (input) { return input.files && input.files.length > 0; });
  }

  function updateSendButton() {
    if (!sendButton) return;
    const hasText = messageInput.value.trim().length > 0;
    sendButton.disabled = !(hasText || voiceBlob || tokenInput.value || hasOtherAttachment());
  }

  function stopTracks(stream) {
    stream.getTracks().forEach(function (track) { track.stop(); });
  }

  function showRecordingControls() {
    recordingControls.classList.remove('display-none');
    recordingControls.classList.add('d-inline-flex');
  }

  function hideRecordingControls() {
    recordingControls.classList.add('display-none');
    recordingControls.classList.remove('d-inline-flex');
  }

  function updateRecordingTimer() {
    if (!recordingStartedAt) return;
    const elapsedSeconds = Math.floor((Date.now() - recordingStartedAt) / 1000);
    const minutes = Math.floor(elapsedSeconds / 60).toString().padStart(2, '0');
    const seconds = (elapsedSeconds % 60).toString().padStart(2, '0');
    recordingTimer.textContent = minutes + ':' + seconds;
  }

  function startRecordingFeedback() {
    recordingStartedAt = Date.now();
    recordingTimer.textContent = '00:00';
    recordingInterval = window.setInterval(updateRecordingTimer, 250);
  }

  function stopRecordingFeedback() {
    if (recordingInterval) window.clearInterval(recordingInterval);
    recordingInterval = null;
    recordingStartedAt = null;
    recordingTimer.textContent = '00:00';
  }

  function clearVoiceMessage() {
    if (activeRecording) {
      const recording = activeRecording;
      activeRecording = null;
      recording.cancelled = true;
      if (recording.recorder.state !== 'inactive') recording.recorder.stop();
      stopTracks(recording.stream);
    }

    stopRecordingFeedback();
    voiceBlob = null;
    voiceExtension = null;
    tokenInput.value = '';
    if (previewUrl) URL.revokeObjectURL(previewUrl);
    previewUrl = null;
    audioPreview.removeAttribute('src');
    audioPreview.playbackRate = 1;
    audioPreview.load();
    preview.classList.add('display-none');
    preview.querySelector('[data-voice-speed]').textContent = '1x';
    hideRecordingControls();
    recordButton.classList.remove('display-none');
    clearError();
    updateSendButton();
  }

  function getExtension(mimeType) {
    const normalizedType = (mimeType || '').toLowerCase();
    if (normalizedType.indexOf('audio/webm') === 0 || normalizedType.indexOf('video/webm') === 0) return 'webm';
    if (normalizedType.indexOf('audio/ogg') === 0 || normalizedType.indexOf('application/ogg') === 0) return 'ogg';
    if (normalizedType.indexOf('audio/mp4') === 0 || normalizedType.indexOf('audio/x-m4a') === 0) return 'm4a';
    if (normalizedType.indexOf('audio/mpeg') === 0) return 'mp3';
    return null;
  }

  async function startRecording() {
    if (activeRecording || startingRecording) return;
    clearError();
    if (voiceBlob || tokenInput.value) clearVoiceMessage();

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia || !window.MediaRecorder) {
      showError('A gravação de áudio requer um navegador compatível e uma ligação segura (HTTPS).');
      return;
    }

    startingRecording = true;
    recordButton.disabled = true;
    let stream = null;

    try {
      stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      const preferredFormats = [
        'audio/webm;codecs=opus',
        'audio/ogg;codecs=opus',
        'audio/mp4'
      ];
      const supportedFormat = preferredFormats.find(function (format) {
        return MediaRecorder.isTypeSupported && MediaRecorder.isTypeSupported(format);
      });
      const recorder = supportedFormat
        ? new MediaRecorder(stream, { mimeType: supportedFormat })
        : new MediaRecorder(stream);
      const recording = { recorder: recorder, stream: stream, chunks: [], cancelled: false };
      activeRecording = recording;

      recorder.addEventListener('dataavailable', function (event) {
        if (event.data && event.data.size) recording.chunks.push(event.data);
      });

      recorder.addEventListener('stop', function () {
        stopTracks(stream);
        stopRecordingFeedback();
        if (activeRecording === recording) activeRecording = null;
        hideRecordingControls();
        recordButton.classList.remove('display-none');

        if (recording.cancelled) return;

        const blob = new Blob(recording.chunks, { type: recorder.mimeType || supportedFormat || '' });
        const extension = getExtension(blob.type);
        if (!blob.size || !extension) {
          showError('Não foi possível criar um ficheiro de áudio compatível.');
          updateSendButton();
          return;
        }

        voiceBlob = blob;
        voiceExtension = extension;
        previewUrl = URL.createObjectURL(blob);
        audioPreview.src = previewUrl;
        preview.classList.remove('display-none');
        updateSendButton();
      });

      recorder.start();
      recordButton.classList.add('display-none');
      showRecordingControls();
      startRecordingFeedback();
      recordButton.disabled = false;
      startingRecording = false;
    } catch (error) {
      if (activeRecording && activeRecording.stream === stream) activeRecording = null;
      if (stream) stopTracks(stream);
      recordButton.disabled = false;
      startingRecording = false;
      showError('Não foi possível aceder ao microfone. Verifique as permissões do navegador.');
    }
  }

  function cancelRecording() {
    if (activeRecording) {
      const recording = activeRecording;
      activeRecording = null;
      recording.cancelled = true;
      if (recording.recorder.state !== 'inactive') recording.recorder.stop();
      stopTracks(recording.stream);
    }
    stopRecordingFeedback();
    hideRecordingControls();
    recordButton.classList.remove('display-none');
    clearError();
    updateSendButton();
  }

  recordButton.addEventListener('click', startRecording);
  stopButton.addEventListener('click', function () {
    if (activeRecording && activeRecording.recorder.state !== 'inactive') {
      activeRecording.recorder.stop();
    }
  });
  cancelButton.addEventListener('click', cancelRecording);
  discardButton.addEventListener('click', clearVoiceMessage);
  messageInput.addEventListener('input', updateSendButton);

  function uploadVoiceAndSubmit() {
    if (uploading) return;
    uploading = true;
    sendButton.disabled = true;

    const formData = new FormData();
    formData.append('audio', voiceBlob, 'voice-message.' + voiceExtension);
    formData.append('_token', form.querySelector('input[name="_token"]').value);

    fetch(form.dataset.voiceUploadUrl, {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    })
      .then(async function (response) {
        let result;
        try {
          result = await response.json();
        } catch (error) {
          throw new Error('Não foi possível enviar a gravação de áudio.');
        }
        if (!response.ok || !result.success || !result.token) {
          throw new Error(result.message || 'Não foi possível enviar a gravação de áudio.');
        }
        tokenInput.value = result.token;
        updateSendButton();
        sendButton.click();
      })
      .catch(function (error) {
        showError(error.message || 'Não foi possível enviar a gravação de áudio.');
        updateSendButton();
      })
      .finally(function () {
        uploading = false;
      });
  }

  sendButton.addEventListener('click', function (event) {
    if (!voiceBlob || tokenInput.value) return;
    event.preventDefault();
    event.stopImmediatePropagation();
    uploadVoiceAndSubmit();
  }, true);

  form.addEventListener('submit', function (event) {
    if (!voiceBlob || tokenInput.value) return;
    event.preventDefault();
    event.stopImmediatePropagation();
    uploadVoiceAndSubmit();
  }, true);

  form.addEventListener('reset', clearVoiceMessage);
  window.addEventListener('pagehide', function () {
    if (activeRecording) stopTracks(activeRecording.stream);
    stopRecordingFeedback();
    if (previewUrl) URL.revokeObjectURL(previewUrl);
  });

  if (window.jQuery) {
    window.jQuery(document).on('ajaxSuccess.voiceMessage', function (event, xhr, settings) {
      if (
        settings.url &&
        settings.url.indexOf('message/send') !== -1 &&
        xhr.responseJSON &&
        xhr.responseJSON.success
      ) {
        clearVoiceMessage();
      }
    });
  }

  updateSendButton();
})();
</script>
<script>
(function () {
  function initializeVoicePlayers(root) {
    const audioElements = [];
    if (root.matches && root.matches('.voice-message-audio')) audioElements.push(root);
    if (root.querySelectorAll) {
      audioElements.push.apply(audioElements, root.querySelectorAll('.voice-message-audio'));
    }

    audioElements.forEach(function (audio) {
      if (audio.dataset.voicePlayerInitialized) return;
      if (typeof window.Plyr !== 'function') {
        console.error('Não foi possível inicializar o reprodutor de áudio das mensagens.');
        return;
      }

      new window.Plyr(audio, {
        controls: ['play', 'progress', 'current-time', 'duration']
      });

      const wrapper = audio.closest('.voice-message-player');
      const speedButton = wrapper.querySelector('[data-voice-speed]');
      function updateSpeedButton() {
        const speed = audio.playbackRate >= 1.5 ? 2 : 1;
        speedButton.textContent = speed + 'x';
        speedButton.setAttribute('aria-label', 'Velocidade de reprodução: ' + speed + 'x');
      }

      audio.playbackRate = 1;
      speedButton.addEventListener('click', function () {
        audio.playbackRate = audio.playbackRate >= 1.5 ? 1 : 2;
        updateSpeedButton();
      });
      audio.addEventListener('ratechange', updateSpeedButton);
      audio.dataset.voicePlayerInitialized = 'true';
      wrapper.classList.remove('invisible');
    });
  }

  initializeVoicePlayers(document);

  const messageObserver = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      mutation.addedNodes.forEach(function (node) {
        if (node.nodeType === Node.ELEMENT_NODE) initializeVoicePlayers(node);
      });
    });
  });
  messageObserver.observe(document.body, { childList: true, subtree: true });
})();
</script>

@if ($user->verified_id == 'yes' 
            && $settings->live_streaming_private == 'on' 
            && $user->allow_live_streaming_private == 'on' 
            && !auth()->user()->isRestricted($user->id)
            )
<script src="{{ asset('public/js/live-private-request.js') }}"></script>
@endif

@endsection
