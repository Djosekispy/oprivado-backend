@extends('layouts.app')

@section('title'){{__('general.live_streaming')}} {{__('general.by')}} {{ '@'.$creator->username }} -@endsection

  @section('css')
    <script type="text/javascript">
        var liveOnline = {{ $live ? 'true' : 'false' }};
        @if ($live)
          var appIdAgora = '{{ $settings->agora_app_id }}'; // set app id
          var agorachannelName = '{{ $live->channel }}'; // set channel name
          var liveMode = true;
          var liveType = '{{ $live->type }}';
          var liveCreator = {{ $creator->id == auth()->id() ? 'true' : 'false' }};
          var role = "{{ $creator->id == auth()->id() ? 'host' : 'audience' }}";
          var availability = '{{ $live->availability }}';
          var textMuteAudio = "{{ __('general.mute_audio') }}";
          var textUnmuteAudio = "{{ __('general.unmute_audio') }}";
          var textMuteVideo = "{{ __('general.mute_video') }}";
          var textUnmuteVideo = "{{ __('general.unmute_video') }}";
        @endif
    </script>

    @if ($live)
      <script src="{{ asset('public/js/agora/AgoraRTCSDK-v4.js') }}"></script>
    @endif
  @endsection

@section('content')
<section class="section section-sm pt-0 pb-0 h-100 section-msg position-fixed live-data" @if ($live) data="{{ $live->id}}" data-creator="{{ $creator->id}}" @endif>
      <div class="container mw-100 h-100">
        <div class="row justify-content-center h-100 position-relative">

          <div class="col-md-9 h-100 p-0 liveContainerFullScreen" @if ($live) data-id="{{ $live->id }}" @endif>
            <div class="card w-100 rounded-0 h-100 border-0 liveContainer @if (! $live) live_offline @endif" @if (! $live) style="background:url('{{Helper::getFile(config('path.avatar').$creator->avatar)}}') no-repeat center center; background-size: cover; background-color: #000;" @endif>

              <div class="content @if (! $live) px-4 py-3 @endif d-scrollbars container-msg">
                @if (! $live)
                  <div class="flex-column d-flex justify-content-center text-center h-100 text-content-live">
                    <div class="w-100">

                      @if (! $live && $creator->id == auth()->id())
                        <h2 class="mb-0 font-montserrat text-white"><i class="bi bi-broadcast mr-2"></i> {{__('general.stream_live')}}</h2>
                        <p class="lead mt-0 text-white">{{__('general.create_live_stream_subtitle')}}</p>
                        <button class="btn btn-primary btn-sm w-small-100 btnCreateLive">
                          <i class="bi bi-plus-lg mr-1"></i> Iniciar live
                        </button>

                        <div class="mt-3 d-block ">
                          <a href="{{ url('/') }}" class="text-white"><i class="bi bi-arrow-left"></i> {{ __('error.go_home') }}</a>
                        </div>

                      @elseif (! $live && $creator->id != auth()->id())

                        <h2 class="mb-0 none font-montserrat text-white"><i class="bi bi-broadcast mr-2"></i> {{__('general.welcome_live_room')}}</h2>
                        @if ($checkSubscription)
                          <p class="lead text-white mt-0">{{__('general.info_offline_live')}}</p>

                          <div class="mt-3 d-block ">
                            <a href="{{ url('/') }}" class="text-white"><i class="bi bi-arrow-left"></i> {{ __('error.go_home') }}</a>
                          </div>

                        @else
                          <p class="lead text-white mt-0">{{__('general.info_offline_live_non_subscribe')}}</p>
                          <a href="{{url($creator->username)}}" class="btn btn-primary btn-sm w-small-100">
                            <i class="feather icon-unlock mr-1"></i> {{__('general.subscribe_now')}}
                          </a>

                          <div class="mt-3 d-block ">
                            <a href="{{ url('/') }}" class="text-white"><i class="bi bi-arrow-left"></i> {{ __('error.go_home') }}</a>
                          </div>

                        @endif

                      @endif
                    </div>
                  </div><!-- flex-column -->
                @else

                  <div class="live-top-menu">
                  	<div class="w-100">
                      <img src="{{Helper::getFile(config('path.avatar').$creator->avatar)}}" class="rounded-circle avatar-live mr-2" width="50" height="50">
                  		<span class="text-white text-shadow-sm d-lg-inline-block d-none">{{ $creator->username }}</span>
                      <span class="text-white text-shadow-sm d-lg-none d-inline-block">{{ str_limit($creator->username, 7, '...') }}</span>
                  		<small class="font-weight-bold none text-white text-shadow-sm" style="position: absolute; top: {{ $limitLiveStreaming ? '65px;' : '50px;' }} left: 67px;">
                        
                        @if (!$limitLiveStreaming)
                        {{ __('general.started') }} <span class="timeAgo" data="{{date('c', strtotime($live->created_at))}}"></span>
                        @endif
                        
                        @if ($creator->id == auth()->id())
                        <span class="w-100 @if ($amountTips === 0) display-none @else d-block @endif" id="earned">
                          <i class="bi-coin mr-1"></i> <span id="amountTip">{{ Helper::formatPrice($amountTips) }}</span>
                        </span>
                        @endif
                      </small>


                      @if ($live && ! $paymentRequiredToAccess && $limitLiveStreaming)
                        <small class="text-white text-shadow-sm limitLiveStreaming">
                          <i class="bi bi-clock mr-1"></i> <span>{{ $limitLiveStreaming }}</span> {{ __('general.minutes') }}
                        </small>
                      @endif


                      <div class="float-right">
                        <span class="live text-uppercase mr-2">{{ __('general.live') }}</span>
                        <span class="live-views text-uppercase mr-2">
                          <i class="bi bi-eye mr-2"></i> <span id="liveViews">{{ $live->onlineUsers->count() }}</span>
                        </span>

                        @if ($creator->id != auth()->id())
                          <button class="live-views text-uppercase mr-2" id="liveAudio">
                            <i class="fas fa-volume-mute"></i>
                          </button>
                        @endif

                        @if ($creator->id == auth()->id())
                          <span class="live-options text-shadow-sm mr-2" id="optionsLive" role="button" data-toggle="dropdown">
                            <i class="bi bi-gear"></i>
                          </span>

                          <div class="dropdown-menu dropdown-menu-right menu-options-live mb-1" aria-labelledby="optionsLive">
                            <div id="mute-audio">
                              <a class="dropdown-item"><i class="bi-mic-mute mr-1"></i> {{ __('general.mute_audio') }}</a>
                            </div>
                            <div id="mute-video">
                              <a class="dropdown-item"><i class="bi-camera-video-off mr-1"></i> {{ __('general.mute_video') }}</a>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div id="camera-list"></div>
                            <div id="mic-list"></div>
                          </div>

                          <form method="POST" action="{{ url('end/live/stream', $live->id) }}" accept-charset="UTF-8" class="d-none" id="formEndLive">
                            @csrf
                            </form>
                        <span class="close-live text-shadow-sm" id="endLive" data-toggle="tooltip" data-placement="top" title="{{ __('general.end_live') }}">
                          <i class="bi bi-x-lg"></i>
                        </span>

                      @else
                      <span class="exit-live text-shadow-sm" id="menuToggle" role="button" data-toggle="dropdown">
                        <i class="bi-three-dots"></i>
                      </span>

                      <div class="dropdown-menu dropdown-menu-right menu-options-live mb-1" aria-labelledby="menuToggle">
                        @if ($live->type == 'normal')
                        <a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" data-target="#reportLiveStream">
                          <i class="bi-flag mr-2"></i> {{ __('general.report_live_stream') }}
                        </a>
                        @endif

                        <a href="javascript:void(0);" class="dropdown-item" id="exitLive">
                          <i class="feather icon-log-out mr-2"></i> {{ __('general.exit_live_stream') }}
                        </a>
                      </div>

                        
                      @endif

                      </div>
                    </div>
                  </div>

                  <div id="full-screen-video"></div>

                @endif

              </div><!-- container-msg -->

              </div><!-- card -->
            </div><!-- end col-md-8 -->

          <!-- Chat Box -->
          <div class="col-md-3 h-100 p-0 border-right wrapper-msg-inbox wrapper-live-chat">

          <div class="card w-100 rounded-0 h-100 border-0">

            <div class="w-100 p-3 border-bottom titleChat">
            	<div class="w-100">
            		<span class="h5 align-top font-weight-bold">{{ __('general.chat') }}</span>
              </div>
            </div>

            <div class="content px-4 py-3 d-scrollbars container-msg chat-msg" id="contentDIV">

              <div class="div-flex"></div>

              @if ($live && ! $paymentRequiredToAccess)
              <ul class="list-unstyled mb-0" id="allComments">
                @include('includes.comments-live')
              </ul>
              @endif


            </div>

        <div class="card-footer bg-transparent position-relative @if (! $live) offline-live @endif">
            <!-- Alert -->
            <div class="alert alert-danger my-3 display-none" id="errorMsg">
             <ul class="list-unstyled m-0" id="showErrorMsg"></ul>
           </div><!-- Alert -->

            <form action="{{ url('comment/live') }}" method="post" accept-charset="UTF-8" id="formSendCommentLive" enctype="multipart/form-data">

              @if ($live)
                <input type="hidden" name="live_id" value="{{ $live->id }}">
              @endif

              @csrf

                  <div class="d-flex">

                    <div class="w-100 h-100 position-relative">
                      <div class="live-blocked rounded-pill blocked @if ($live && ! $paymentRequiredToAccess) display-none @endif"></div>
                      <input type="text" class="form-control border-0 emojiArea" id="commentLive" placeholder="{{ __('general.write_something') }}" name="comment" autocomplete="off" />
                    </div>

                    @if ($live && ! $paymentRequiredToAccess)
                      <button class="none" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn icons-live e-none align-bottom buttons-live @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill">
                          <i class="bi-emoji-smile f-size-25"></i>
                      </button>

                      <div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar" aria-labelledby="dropdownEmoji">
                        @include('includes.emojis')
                      </div>
                    @endif

                    @if ($creator->id != auth()->id())
                      @if ($live && ! $paymentRequiredToAccess)

                      @if ($settings->gifts)
                      <button type="button" data-toggle="modal" title="{{__('general.gifts')}}" data-target="#giftsForm" class="btn icons-live f-size-25 btn-tooltip e-none align-bottom buttons-live @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill">
                        <i class="bi-gift"></i>
                      </button>

                      @else
                      <button style="background:#fff!important;border-radius: 50%!important; margin-left:5px;" type="button" class="btn icons-live btn-tooltip e-none align-bottom buttons-live @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="modal" data-target="#tipForm" title="{{__('general.tip')}}" data-cover="{{Helper::getFile(config('path.cover').$creator->cover)}}" data-avatar="{{Helper::getFile(config('path.avatar').$creator->avatar)}}" data-name="{{$creator->hide_name == 'yes' ? $creator->username : $creator->name}}" data-userid="{{$creator->id}}">
                        <img width="22" heigth="22" style="" src="/public/img/usd-circle.svg">
                      </button>
                      @endif

                      @endif
                    @endif

                    @if ($live && ! $paymentRequiredToAccess)
                    <span class="btn none icons-live e-none align-bottom buttons-live {{ $likeActive ? 'active' : null }} button-like-live @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill">
                      <i class="bi bi-heart{{ $likeActive ? '-fill' : null }}"></i>
                    </span>

                    <div class="py-3 none">
                      <small id="counterLiveLikes">
                        @if ($live && $likes != 0)
                          {{ $likes }}
                        @endif
                      </small>
                    </div>
                    @endif

                  </div><!-- justify-content-between -->
                </form>
              </div>

            </div><!-- end card -->

          </div><!-- end col-md-3 -->

          </div><!-- end row -->
        </div><!-- end container -->
</section>

@if ($live && $paymentRequiredToAccess)
  @include('includes.modal-pay-live')
@endif

@if ($creator->id != auth()->id() && $live && ! $paymentRequiredToAccess)
<div class="modal fade modalReport" id="reportLiveStream" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-danger modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title font-weight-light" id="modal-title-default"><i class="fas fa-flag mr-1"></i> {{__('general.report_live_stream')}}</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </button>
      </div>
 <!-- form start -->
 <form method="POST" action="{{url('report/live', $creator->id)}}" enctype="multipart/form-data">
    <div class="modal-body">
      @csrf
      <!-- Start Form Group -->
      <div class="form-group">
        <label>{{__('admin.please_reason')}}</label>
          <select name="reason" class="form-control custom-select">
           <option value="spoofing">{{__('admin.spoofing')}}</option>
              <option value="copyright">{{__('admin.copyright')}}</option>
              <option value="privacy_issue">{{__('admin.privacy_issue')}}</option>
              <option value="violent_sexual">{{__('admin.violent_sexual_content')}}</option>
              <option value="spam">{{__('general.spam')}}</option>
              <option value="fraud">{{__('general.fraud')}}</option>
            </select>

            <textarea name="message" rows="" cols="40" maxlength="200" placeholder="{{__('general.message')}}*" class="form-control mt-2 textareaAutoSize"></textarea>
            
            </div><!-- /.form-group-->
        </div><!-- Modal body -->

       <div class="text-center pb-4">         
         <button type="submit" class="btn btn-xs btn-white sendReport ml-auto"><i></i> {{__('general.report_live_stream')}}</button>
         <div class="w-100 mt-2">
          <button type="button" class="btn border text-white" data-dismiss="modal">{{__('admin.cancel')}}</button>
        </div>
       </div>

       </form>
      </div><!-- Modal content -->
    </div><!-- Modal dialog -->
  </div>
@endif

@endsection

@section('javascript')

  @if ($live && $paymentRequiredToAccess)
    <script>
    // Payment Required
  		$('#payLiveForm').modal({
  				 backdrop: 'static',
  				 keyboard: false,
  				 show: true
  		 });

       //<---------------- Pay Live ----------->>>>
 			 $(document).on('click','#payLiveBtn',function(s) {
 				 s.preventDefault();
 				 var element = $(this);
 				 element.attr({'disabled' : 'true'});
 				 element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

 				 (function(){
 						$('#formPayLive').ajaxForm({
 						dataType : 'json',
 						success:  function(result) {

 							if (result.success) {
 								window.location.reload();
 							} else {

 								if (result.errors) {

 									var error = '';
 									var $key = '';

 									for ($key in result.errors) {
 										error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
 									}

 									$('#showErrorsPayLive').html(error);
 									$('#errorPayLive').show();
 									element.removeAttr('disabled');
 									element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
 								}
 							}

 						 },
 						 error: function(responseText, statusText, xhr, $form) {
 								 // error
 								 element.removeAttr('disabled');
 								 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
 								 swal({
 										 type: 'error',
 										 title: error_oops,
 										 text: error_occurred+' ('+xhr+')',
 									 });
 						 }
 					 }).submit();
 				 })(); //<--- FUNCTION %
 			 });//<<<-------- * END FUNCTION CLICK * ---->>>>
    </script>
  @endif

  @if ($live && !$paymentRequiredToAccess)
    <script src="{{ asset('public/js/live.js') }}?v={{$settings->version}}"></script>
    <script src="{{ asset('public/js/agora/agora-broadcast-client-v4.js') }}?v={{$settings->version}}"></script>

    @if ($creator->id == auth()->id() || !$paymentRequiredToAccess)
      <script>
      // Start Live
      $(document).ready(async function() {
        await joinChannel();
		  
		     // Notify subscribers
        try {
          if (typeof liveCreator !== 'undefined' && liveCreator === true) {
            var liveId = $('.liveContainerFullScreen').data('id');
            if (liveId && !window._liveNotified) {
              window._liveNotified = true;
              $.ajax({
                url: '/live/' + liveId + '/notify',
                type: 'POST',
                dataType: 'json',
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                  console.log('Subscribers notified', res);
                },
                error: function(err) {
                  console.log('Failed to notify subscribers', err);
                }
              });
            }
          }
        } catch (e) {
          console.log('Notify subscribers error', e);
        }
      });
    	</script>
      @endif

  @endif
@endsection
