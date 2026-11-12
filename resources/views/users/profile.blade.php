@extends('layouts.app')

@section('title'){{ $user->hide_name == 'yes' ? $mediaTitle.$user->username : $mediaTitle.$user->name }} -@endsection

@section('css')

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "{{$user->hide_name == 'yes' ? $user->username : $user->name}}",
  "image": "https://oprivado.ao/public/img/pico.png",
  "url": "{{url($user->username.$media)}}"
}
</script>
<meta name="description" content="Olá, siga o meu perfil no Oprivado para ver os meus conteúdos exclusivos!">
<meta property="og:type" content="website" />
<meta property="og:image" content="https://oprivado.ao/public/img/profile.png" />
<meta name="robots" content="index, follow, noimageindex">
<meta name="googlebot" content="index, follow, noimageindex">
 <meta property="og:image:width" content="200"/>
  <meta property="og:image:height" content="200"/>

  <!-- Og Meta Tags -->
  <link rel="canonical" href="{{url($user->username.$media)}}"/>
  <meta property="og:site_name" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }} - {{$settings->title}}"/>
  <meta property="og:url" content="{{url($user->username.$media)}}"/>

  <meta property="og:title" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }} - {{$settings->title}}"/>
  <meta property="og:description" content="Olá, siga o meu perfil no Oprivado para ver os meus conteúdos exclusivos!"/>
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:image" content="{{Helper::getFile(config('path.avatar').$user->avatar)}}" />
  <meta name="twitter:title" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }}" />
  <meta name="twitter:description" content="Olá, siga o meu perfil no Oprivado para ver os meus conteúdos exclusivos!"/>

  <script type="text/javascript">
      var profile_id = {{$user->id}};
      var sort_post_by_type_media = "{!! $sortPostByTypeMedia !!}";
  </script>
  @endsection

@section('content')
<div class="jumbotron perfil jumbotron-cover-user home m-0 position-relative" style="padding: @if ($user->cover != '') @if (request()->path() == $user->username) 190px @else 125px @endif @else 125px @endif 0; background: #505050 @if ($user->cover != '') url('{{Helper::getFile(config('path.cover').$user->cover)}}') no-repeat center center; background-size: cover; @endif">
	
  @if (auth()->check() && auth()->user()->status == 'active' && auth()->id() == $user->id)
    <div class="progress-upload-cover"></div>

    <form action="{{url('upload/cover')}}" method="POST" id="formCover" accept-charset="UTF-8" enctype="multipart/form-data">
      @csrf
    <input type="file" name="image" id="uploadCover" accept="image/*" class="visibility-hidden">
  </form>

  <div class="flex justify-between wraper-cover-upload">
    <button class="btn btn-cover-upload p-lg-6 px-3" id="coverFile">
      <i class="fa fa-camera mr-lg-1"></i>  <span class="d-none d-lg-inline">{{__('general.change_cover')}}</span>
    </button>
  
    <button class="btn none btn-cover-upload px-3 deleteCover">
      <i class="bi-trash3-fill"></i> 
    </button>
  </div>	
@endif
	
		<div style="position: absolute;top: 58px;padding: 10px;color: white;font-size:18px;" class="ocultar-pc card-text">
                            <span class="btn-block">
								 @if ($user->verified_id == 'yes')
                                    <small><i class="feather icon-image"></i> {{ $totalPhotos }}</small> <span style="margin-left: 2px;margin-right: 2px;font-size: 14px;">&#x2022;</span>
								<small><i class="feather icon-video"></i> {{ $totalVideos }}</small>
								@endif 

								@if ($subscriptionsActive != 0 && $user->hide_count_subscribers == 'no')
                    <span style="margin-left: 2px;margin-right: 2px;font-size: 14px;">&#x2022;</span> <small><i class="feather icon-users"></i> {{ Helper::formatNumber($subscriptionsActive) }}</small>@endif
								
								@if ($likeCount != 0)
               <span style="margin-left: 2px;margin-right: 2px;font-size: 14px;">&#x2022;</span> <small><i class="feather icon-heart"></i> {{ $likeCount }}</small>
                @endif 
                              </span>
                </div>
</div>

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="w-100 text-center py-2 img-profile-user">

          <div @if (Helper::isCreatorLive($getCurrentLiveCreators, $user->id) && auth()->check() && auth()->id() != $user->id) data-url="{{ url('live', $user->username) }}" @endif class="text-center position-relative @if (Helper::isCreatorLive($getCurrentLiveCreators, $user->id) && auth()->check() && auth()->id() != $user->id) avatar-wrap-live liveLink @else avatar-wrap @endif shadow @if (auth()->check() && auth()->id() != $user->id && Cache::has('is-online-' . $user->id) && $user->active_status_online == 'yes' || auth()->guest() && Cache::has('is-online-' . $user->id) && $user->active_status_online == 'yes') user-online-profile overflow-visible @elseif (auth()->check() && auth()->id() != $user->id && !Cache::has('is-online-' . $user->id) && $user->active_status_online == 'yes' || auth()->guest() && !Cache::has('is-online-' . $user->id) && $user->active_status_online == 'yes') user-offline-profile overflow-visible @endif">

            @if (auth()->check() && auth()->id() != $user->id && Helper::isCreatorLive($getCurrentLiveCreators, $user->id))
              <span class="live-span">{{ __('general.live') }}</span>
              <div class="live-pulse"></div>
            @endif

            <div class="progress-upload">0%</div>

            @if (auth()->check() && auth()->user()->status == 'active' && auth()->id() == $user->id)

              <form action="{{url('upload/avatar')}}" method="POST" id="formAvatar" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
              <input type="file" name="avatar" id="uploadAvatar" accept="image/*" class="visibility-hidden">
            </form>

        <a href="javascript:;" class="position-absolute button-avatar-upload" id="avatarWarningTrigger">
    <i class="fa fa-camera"></i>
</a>
          @endif
            <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" width="150" height="150" alt="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" class="rounded-circle img-user mb-2 avatarUser @if (auth()->check() && auth()->id() != $user->id && Helper::isCreatorLive($getCurrentLiveCreators, $user->id)) border-0 @endif">
          </div><!-- avatar-wrap -->

          <div class="media-body">
            <h4 style="font-size:16px;" class="mt-2">
              {{$user->hide_name == 'yes' ? $user->username : $user->name}} @if ($user->is_pro == '1')
               <i style="font-size: 12px;color: #fd7350;" class="bi-star-fill mr-1"></i>
              @endif
              @if ($user->verified_id == 'yes')
              <small class="verified" title="{{__('general.verified_account')}}" data-toggle="tooltip" data-placement="top">
                <i class="bi-patch-check-fill"></i> @if ($user->featured == 'yes')
               <i class="feather icon-award"></i>
              @endif
              </small> 
				<br><small style=" color: #969696 !important; " class="text-muted font-14">{{ '@' . trim($user->username) }}</small>
            @endif
          </h4>
		@if ($user->verified_id == 'yes')
            <p style=" margin-bottom: 6px; ">
            <span>
              @if (! Cache::has('is-online-' . $user->id) && $user->hide_last_seen == 'no')
              <span style=" color: #7f7f7f!important; " class="w-100 d-block">
                <small>{{ __('general.active') }}</small>
                <small class="timeAgo"data="{{ date('c', strtotime($user->last_seen ?? $user->date)) }}"></small>
               </span>
               @endif
          </span>
            </p>  @endif
			  
			  @if ($user->verified_id == 'yes')
                  <div style="font-size:13px!important;" class="truncated mb-3">
                    {!! Helper::checkText($user->story)  !!}
                  </div>
                  <a  style="font-size: 10px;" href="javascript:void(0);" class="display-none link-border">{{ __('general.view_all') }}</a>
            @endif
			  
			  @if ($user->categories_id != '0' && $user->categories_id != '' && $user->verified_id == 'yes')
              <small class="btn-block mb-1 ocultar-pc">
              @foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
                @foreach ($categories as $categoryKey)
                  @if ($categoryKey == $category->id)
                 <i class="feather icon-gitlab mr-1"></i>Conteúdo {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                @endif
                @endforeach
            @endforeach
              </small>
            @endif
			  
			  <div class="mb-3"> 
              @if ($user->facebook != '')
                <a href="{{$user->facebook}}" title="{{$user->facebook}}" target="_blank" class="share-btn-user"><i class="bi-facebook mr-2"></i></a>
              @endif

              @if ($user->twitter != '')
                <a href="{{$user->twitter}}" title="{{$user->twitter}}" target="_blank" class="share-btn-user"><i class="bi-twitter-x mr-2"></i></a>
              @endif

              @if ($user->instagram != '')
                <a href="{{$user->instagram}}" title="{{$user->instagram}}" target="_blank" class=" share-btn-user"><i class="fab fa-instagram mr-2"></i></a>
              @endif

              @if ($user->youtube != '')
                <a href="{{$user->youtube}}" title="{{$user->youtube}}" target="_blank" class="share-btn-user"><i class="fab fa-youtube mr-2"></i></a>
              @endif

              @if ($user->pinterest != '')
                <a href="{{$user->pinterest}}" title="{{$user->pinterest}}" target="_blank" class=" share-btn-user"><i class="fab fa-pinterest-p mr-2"></i></a>
              @endif

              @if ($user->github != '')
                <a href="{{$user->github}}" title="{{$user->github}}" target="_blank" class="share-btn-user"><i class="fab fa-github mr-2"></i></a>
              @endif

              @if ($user->snapchat != '')
                <a href="{{$user->snapchat}}" title="{{$user->snapchat}}" target="_blank" class=" share-btn-user"><i class="bi-snapchat mr-2"></i></a>
              @endif

              @if ($user->tiktok != '')
                <a href="{{$user->tiktok}}" title="{{$user->tiktok}}" target="_blank" class="share-btn-user"><i class="bi-tiktok mr-2"></i></a>
              @endif

              @if ($user->telegram != '')
                <a href="{{$user->telegram}}" title="{{$user->telegram}}" target="_blank" class=" share-btn-user"><i class="bi-telegram mr-2"></i></a>
              @endif

              @if ($user->twitch != '')
                <a href="{{$user->twitch}}" title="{{$user->twitch}}" target="_blank" class=" share-btn-user"><i class="bi-twitch mr-2"></i></a>
              @endif

              @if ($user->discord != '')
                <a href="{{$user->discord}}" title="{{$user->discord}}" target="_blank" class="share-btn-user"><i class="bi-discord mr-2"></i></a>
              @endif

              @if ($user->vk != '')
                <a href="{{$user->vk}}" title="{{$user->vk}}" target="_blank" class="share-btn-user"><i class="fab fa-vk mr-2"></i></a>
              @endif

              @if ($user->reddit != '')
                <a href="{{$user->reddit}}" title="{{$user->reddit}}" target="_blank" class="share-btn-user"><i class="bi-reddit mr-2"></i></a>
              @endif

              @if ($user->spotify != '')
                <a href="{{$user->spotify}}" title="{{$user->spotify}}" target="_blank" class="share-btn-user"><i class="bi-spotify mr-2"></i></a>
              @endif

              @if ($user->threads != '')
                <a href="{{$user->threads}}" title="{{$user->threads}}" target="_blank" class="share-btn-user"><i class="bi-threads mr-2"></i></a>
              @endif

              @if ($user->kick != '')
                <a href="{{$user->kick}}" title="{{$user->kick}}" target="_blank" class="share-btn-user"><i class="fab fa-kickstarter mr-2"></i></a>
              @endif
 			</div>
            <div class="d-flex-user justify-content-center mb-2">
            @if (auth()->check() && auth()->id() == $user->id)
              <a href="{{url('settings/page')}}" class="btn btn-primary btn-profile mr-1"> {{ auth()->user()->verified_id == 'yes' ? __('general.edit_my_page') : __('users.edit_profile')}}</a>
            @endif

              @if ($userPlanMonthlyActive
                  && $user->verified_id == 'yes'
                  || $user->free_subscription == 'yes'
                  && $user->verified_id == 'yes')

              @if (auth()->check() && auth()->id() != $user->id
                  && ! $checkSubscription
                  && ! $paymentIncomplete
                  && $user->free_subscription == 'no'
                  && $totalPosts != 0
                  )
                <a href="javascript:void(0);" data-toggle="modal" data-target="#subscriptionForm" class="btn btn-primary btn-profile mr-1">
                  <i class="feather icon-unlock mr-1"></i> {{__('general.subscribe_month', ['price' => Helper::formatPrice($user->getPlan('monthly', 'price'))])}}
                </a>
              @elseif (auth()->check() && auth()->id() != $user->id && ! $checkSubscription && $paymentIncomplete)
                <a href="{{ route('cashier.payment', $paymentIncomplete->last_payment) }}" class="btn btn-warning btn-profile mr-1">
                  <i class="fa fa-exclamation-triangle"></i> {{__('general.confirm_payment')}}
                </a>
              @elseif (auth()->check() && auth()->id() != $user->id && $checkSubscription)

                @if ($checkSubscription->stripe_status == 'active' && $checkSubscription->stripe_id != '')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/cancel/$checkSubscription->stripe_id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button(' '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate(auth()->user()->subscription('main', $checkSubscription->stripe_price)->asStripeSubscription()->current_period_end, true), 'class' => 'btn btn-primary btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($checkSubscription->stripe_id == '' && $checkSubscription->free == 'yes')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/free/cancel/$checkSubscription->id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button(''.__('general.your_subscribed'), ['data-expiration' => __('general.confirm_cancel_subscription'), 'class' => 'btn btn-primary btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($paymentGatewaySubscription == 'Paystack' && $checkSubscription->cancelled == 'no')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/paystack/cancel/$checkSubscription->subscription_id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button(' '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate($checkSubscription->ends_at), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($paymentGatewaySubscription == 'Wallet' && $checkSubscription->cancelled == 'no')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/wallet/cancel/$checkSubscription->id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button(' '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate($checkSubscription->ends_at), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($paymentGatewaySubscription == 'PayPal' && $checkSubscription->cancelled == 'no')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/paypal/cancel/$checkSubscription->id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button(''.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate($checkSubscription->ends_at), 'class' => 'btn btn-primary btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($paymentGatewaySubscription == 'CCBill' && $checkSubscription->cancelled == 'no')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/ccbill/cancel/$checkSubscription->id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button(' '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate($checkSubscription->ends_at), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($checkSubscription->cancelled == 'yes' || $checkSubscription->stripe_status == 'canceled')
                <a href="javascript:void(0);" class="btn btn-success btn-profile mr-1 disabled">
                   {{__('general.subscribed_until')}} {{ Helper::formatDate($checkSubscription->ends_at) }}
                </a>
              @endif

              @elseif (auth()->check() && auth()->id() != $user->id && $user->free_subscription == 'yes' && $totalPosts != 0)
                <a href="javascript:void(0);" data-toggle="modal" data-target="#subscriptionFreeForm" class="btn btn-primary btn-profile mr-1">
                  {{__('general.subscribe_for_free')}}
                </a>
              @elseif (auth()->guest() && $totalPosts != 0)
                <a href="{{url('login')}}" data-toggle="modal" data-target="#loginFormModal" class="btn btn-primary btn-profile mr-1">
                  @if ($user->free_subscription == 'yes')
                    {{__('general.subscribe_for_free')}}
                  @else
                   {{__('general.subscribe_month', ['price' => Helper::formatPrice($user->getPlan('monthly', 'price'))])}}
                @endif
                </a>
            @endif

            @endif
					 
@if (auth()->check() && auth()->id() == $user->id && $user->verified_id != 'yes')
    @if ($user->isPro())
        <button class="btn btn-primary btn-profile mr-1" disabled>
            Você já é Pro <i style="font-size: 10px;color: #fff;" class="bi-star-fill mr-1"></i>
        </button>
    @else
        <button type="button"
                class="btn btn-google btn-profile mr-1"
                data-toggle="modal"
                data-target="#becomeProModal"
                title="Tornar-se Pro por 31 dias">
            Tornar-se Pro <i style="font-size: 11px;color: #fd7350;" class="bi-star-fill mr-1"></i>
        </button>
    @endif
@endif

@if (auth()->check() && auth()->id() == $user->id && $user->verified_id == 'yes')
    @if ($user->featured == 'yes')
        <button class="btn btn-primary btn-profile mr-1" disabled>
         Perfil em destaque
        </button>
    @else
       <button type="button" 
                class="btn btn-google btn-profile mr-1" 
                data-toggle="modal" 
                data-target="#highlightProfileModal"
                title="Destacar meu Perfil por 20 dias">
			Destacar meu perfil
        </button>
    @endif
@endif	

            @if (auth()->check() && auth()->id() != $user->id && $totalPosts <> 0 && $settings->disable_tips == 'off' && $user->verified_id == 'yes')
              <a href="javascript:void(0);" data-toggle="modal" title="{{__('general.tip')}}" data-target="#tipForm" class="btn btn-google btn-profile mr-1" data-cover="{{Helper::getFile(config('path.cover').$user->cover)}}" data-avatar="{{Helper::getFile(config('path.avatar').$user->avatar)}}" data-name="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" data-userid="{{$user->id}}">
                 {{__('general.tip_')}}
              </a>
            @elseif (auth()->guest() && $totalPosts <> 0)
              <a href="{{url('login')}}" data-toggle="modal" data-target="#loginFormModal" class="btn btn-google btn-profile mr-1" title="{{__('general.tip')}}">
                 {{__('general.tip_')}}
              </a>
            @endif

            @if (auth()->guest() && $user->verified_id == 'yes' || auth()->check() && auth()->id() != $user->id && $user->verified_id == 'yes')
              <button 
    @guest 
        data-toggle="modal" data-target="#loginFormModal" 
    @else 
        @if(is_null($user->block_message) || $user->block_message === '' || $user->block_message === 'No')
            id="sendMessageUser" data-url="{{ url('messages/'.$user->id, $user->username) }}"
        @endif
    @endguest
    title="{{ __('general.message') }}"
    class="btn btn-google btn-profile mr-1"
    @if($user->block_message == 'Yes') disabled style="opacity:0.6; cursor:not-allowed;" @endif
>
    {{ __('general.message') }}
</button>
            @endif

            @if ($user->verified_id == 'yes')
              <button style="border:1px solid #fff;" class="btn btn-profile btn-google" title="{{__('general.share')}}" id="dropdownUserShare" role="button" data-toggle="modal" data-target=".share-modal"><i class="fa fa-ellipsis-h"></i> </button>

				<div class="modal fade share-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
          		<div class="modal-content">
               
                <div class="modal-body">

                  @if ($settings->generate_qr_code)
                  <div class="d-block w-100 text-center mb-5">
                    <div id="QrCode" class="d-block w-100 text-center mb-3"></div>

                    <div class="d-block w-100 text-center">
                      <a class="btn btn-primary" id="downloadQr" href="javascript:;">
                      <i class="bi-download mr-1"></i>  {{ __('general.download') }}
                      </a>
                    </div>
                  </div>
                @endif
					
					 @if (auth()->check() && auth()->id() != $user->id)
            <div class="text-left">
              <button type="button" class="dropdown-item" data-toggle="modal" data-target="#reportCreator">
               <i class="text-black fas fa-flag mr-1"></i> {{__('general.report_user')}}
              </button>

              @if (auth()->user()->isRestricted($user->id))
                <button type="button" class="dropdown-item" data-user="{{$user->id}}" id="restrictUser">
                  <i class="text-black fas fa-ban mr-1"></i> {{__('general.remove_restriction')}}
                </button>

              @else
                <button type="button" class="dropdown-item" data-user="{{$user->id}}" id="restrictUser">
                  <i class="text-black fas fa-ban mr-1"></i> {{__('general.restrict')}}
                </button>
              @endif

            </div>
          @endif
					 <div class="text-left"> <a href="https://www.facebook.com/sharer/sharer.php?u={{url($user->username).Helper::referralLink()}}" title="Facebook" target="_blank" class="social-share text-muted dropdown-item h6">
          								<i class="fab text-black mr-1 fa-facebook-square facebook-btn"></i>
          								Partilhar no Facebook
          							</a>
					
					<a href="whatsapp://send?text={{url($user->username).Helper::referralLink()}}" data-action="share/whatsapp/share" class="social-share text-muted dropdown-item h6" title="WhatsApp">
          								<i class="mr-1 text-black fab fa-whatsapp btn-whatsapp"></i> Partilhar no WhatsApp
          							</a>
	
					<a href="https://twitter.com/intent/tweet?url={{url($user->username).Helper::referralLink()}}&text={{ e( $user->hide_name == 'yes' ? $user->username : $user->name ) }}" data-url="{{url($user->username)}}" class="social-share text-muted dropdown-item h6" target="_blank" title="Twitter">
          								<i class="mr-1 bi-twitter-x text-black text-dark"></i> Partilhar no X
          		</a> 
					<a href="javascript:void(0);" id="btn_copy_url" class="social-share text-muted dropdown-item h6 link-share" title="{{__('general.copy_link')}}"> <i class="mr-1 text-black fas fa-link"></i>{{__('general.copy_link')}}
						<input type="hidden" readonly="readonly" id="copy_link" class="form-control" value="{{url($user->username).Helper::referralLink()}}">
          						</a>
					</div>
            </div>
          </div>
          </div><!-- d-flex-user -->
          </div> 
          @endif

          </div><!-- d-flex-user -->
          </div>
			
			<!-- media-body -->
        </div><!-- media -->

       @if ($user->verified_id == 'yes')
        <ul class="nav nav-profile justify-content-center nav-fill">

          <li style=" padding: 11px 0rem;" class="nav-link @if (request()->path() == $user->username)active @endif">
              <a href="{{request()->path() == $user->username ? 'javascript:;' : url($user->username)}}" title="{{__('general.posts')}}"><i class="feather icon-file-text"></i> <span class="d-lg-inline-block">{{ $totalPosts }} {{__('general.posts')}}</span></a>
            </li>

            <li style=" padding: 11px 0rem;" class="nav-link @if (request()->path() == $user->username.'/photos')active @endif">
              <a href="{{request()->path() == $user->username.'/photos' ? 'javascript:;' : url($user->username, 'photos')}}" title="{{__('general.photos')}}"><i class="feather icon-image"></i> <span class="d-lg-inline-block">{{ $totalPhotos }} {{__('general.photos')}}</span></a>
            </li>

            <li style=" padding: 11px 0rem;" class="nav-link @if (request()->path() == $user->username.'/videos')active @endif">
              <a href="{{request()->path() == $user->username.'/videos' ? 'javascript:;' : url($user->username, 'videos')}}" title="{{__('general.video')}}"><i class="feather icon-video"></i> <span class="d-lg-inline-block">{{ $totalVideos }} {{__('general.videos')}}</span></a>
              </li>
			
			<li style=" padding: 11px 0rem;" class="nav-link @if (request()->path() == $user->username.'/audio')active @endif">
              <a href="{{request()->path() == $user->username.'/music' ? 'javascript:;' : url($user->username, 'audio')}}" title="{{__('general.Music')}}"><i class="feather icon-mic"></i> <span class="d-lg-inline-block">{{ $totalMusic }} {{__('general.Music')}}</span></a>
              </li>
        </ul>
      @endif

      </div><!-- col-lg-12 -->
    </div><!-- row -->
  </div><!-- container -->

  @if ($user->verified_id == 'yes' && request('media') != 'shop')
  <div class="container py-4 pb-5">
    <div class="row">
      <div class="col-lg-4 ocultar-phone mb-3">
		  
      <div class="sticky-top  navbar-collapse d-lg-block" id="navbarUserHome">
        <div class="card mb-3 rounded-large shadow-large">
          <div class="card-body">
            <h6 class="card-title">Sobre @switch($user->gender)
    @case('male')
        o
        @break

    @case('female')
        a
        @break

    @case('gay')
        o
        @break

    @case('lesbian')
        a
        @break

    @case('bisexual')
        o
        @break

    @case('transgender')
        a
        @break

    @case('metrosexual')
        a
        @break
@endswitch {{$user->hide_name == 'yes' ? $user->username : $user->name}}</h6>
            <p class="card-text position-relative">
				
				@if ($likeCount != 0 || $subscriptionsActive != 0)
              <span class="btn-block">
                @if ($subscriptionsActive != 0 && $user->hide_count_subscribers == 'no')
                    <small><i class="feather icon-users mr-1"></i> {{ Helper::formatNumber($subscriptionsActive) }} {{ trans_choice('general.subscribers', $subscriptionsActive) }}</small>
                @endif
              </span>
            @endif

              @if ($likeCount != 0 || $subscriptionsActive != 0)
              <span>
                @if ($likeCount != 0)
                <small class="mr-2"><i class="far fa-heart mr-1"></i> {{ $likeCount }} {{ __('general.likes') }}</small>
                @endif
              </span>
            @endif
				
              @if (isset($user->country()->country_name) && $user->hide_my_country == 'no')
              <small class="btn-block">
                <i class="feather icon-map-pin mr-1"></i> {{$user->country()->country_name}}
              </small>
              @endif

              @if ($user->show_my_birthdate == 'yes')
                <small class="btn-block m-0 mb-1">
                  <i class="fas fa-birthday-cake mr-1"></i> {{ Helper::formatDate($user->birthdate) }} ({{ \Carbon\Carbon::parse($user->birthdate)->age }} {{ __('general.years') }})
                </small>
              @endif
				
				@if ($user->categories_id != '0' && $user->categories_id != '' && $user->verified_id == 'yes')
              <small class="btn-block mb-1">
              @foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
                @foreach ($categories as $categoryKey)
                  @if ($categoryKey == $category->id)
                 <i class="feather icon-gitlab mr-1"></i>Conteúdo {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                @endif
                @endforeach
            @endforeach
              </small>
            @endif

            </p>

              @if ($user->website != '')
                <small class="btn-block m-0 mb-1">
                  <a href="{{$user->website}}" title="{{$user->website}}" target="_blank" class="text-muted"><i class="fa fa-link mr-1"></i> {{Helper::removeHTPP($user->website)}}</a>
                </small>
              @endif
              
          </div><!-- card-body -->
        </div><!-- card -->

        <div class="d-lg-block d-none">
        @include('includes.footer-tiny')
      </div>

        </div><!-- navbar-collapse -->
      </div><!-- col-lg-4 -->

      <div class="col-lg-8 wrap-post">
<!---
        @if (auth()->check()
            && auth()->id() == $user->id
            && ! $userPlanMonthlyActive
            && auth()->user()->free_subscription == 'no'
            )
        <div class="alert alert-danger mb-3">
                 <ul class="list-unstyled m-0">
                   <li><i class="fa fa-exclamation-triangle"></i> {{__('general.alert_not_subscription')}} <a href="{{url('settings/subscription')}}" class="text-white link-border">{{__('general.activate')}}</a></li>
                 </ul>
               </div>
               @endif
-->
        @if (auth()->check()
            && auth()->id() == $user->id
            && request()->path() == $user->username
            && auth()->user()->verified_id != 'reject'
            )
          @include('includes.form-post')
        @endif

        @if ($updates->count() == 0 || $updates->count() == 0 && $media)
            <div class="grid-updates"></div>

            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="fa fa-photo-video ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{__('general.no_posts_posted')}}</h4>
            </div>
          @else

            @if (! request()->get('sort') && $totalPosts > $settings->number_posts_show || request()->get('sort'))
            
        @endif

        @if (auth()->guest() && !$user->posts_privacy)
        <div class="my-5 text-center no-updates">
          <span class="btn-block mb-3">
            <i class="fa fa-lock ico-no-result"></i>
          </span>
        <h4 class="font-weight-light">{{__('general.alert_posts_privacy', ['user' => '@'.$user->username])}}</h4>
        </div>

        @elseif (auth()->guest())

		  <!-- Conteudo para visitante -->
		  
        <div class="grid-updates position-relative">
			
			
			<div class="card mb-3 card-updates views rounded-large shadow-large card-border-0">
	<div class="card-body">
	<div class="media">
		<span class="rounded-circle mr-3 position-relative">
			<a href="">
			<img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" class="rounded-circle avatarUser" width="40" height="40">
				</a>
		</span>

		<div class="media-body">
				<h5 class="mb-0 font-montserrat">
					<a style="color:black!important;" href="#">
					{{$user->name}}</a>
					<small class="verified" title="" data-toggle="tooltip" data-placement="top" data-original-title="Conta verificada">
						<i class="bi bi-patch-check-fill"></i>
					</small> 
					
					<small class="text-muted font-14">@ {{$user->username}}</small>
							</h5>

				<small class="text-muted">Conteúdo exclusivo</small>

				<small class="text-muted type-post" title="Conteúdo bloqueado.">

					<i class="feather icon-lock mr-1"></i>

									</small>
					</div><!-- media body -->
	</div><!-- media -->
</div><!-- card body -->
	<div class="btn-block p-sm text-center content-locked pt-lg pb-lg px-3" style="background: url(https://oprivado.ao/public/images/icon3.png) no-repeat center center #f9f6f2;background-size: 49%;background-repeat: no-repeat;background-position: left 151%;">
		<span class="btn-block text-center mb-3"><i class="feather icon-lock ico-no-result border-0"></i></span>

						<a href="javascript:void(0);" data-toggle="modal" data-target="#loginFormModal" class="btn btn-primary w-100">Subscreva para ver conteúdo exclusivo de {{$user->name}}</a>
		
		<ul class="list-inline mt-3">

					<li class="list-inline-item"><i class="feather icon-file-text"></i>{{ $totalPosts }} <i class="feather icon-image"></i>{{ $totalPhotos }} <i class="feather icon-video"></i> {{ $totalVideos }} </li>

	</ul>

</div><!-- btn-block parent -->
<!-- card-footer -->
	</div>

        </div>
		  
		  @else
		  
		   <div class="grid-updates position-relative" id="updatesPaginator">
          @include('includes.updates')
        </div>
		  
        @endif   

          @endif
      </div>
      </div><!-- row -->
    </div><!-- container -->
  @endif

  @if ($user->verified_id == 'yes' && request('media') == 'shop')
    <div class="container py-5">

      @if ($userProducts->count() != 0)
      <div class="@if (auth()->check() && auth()->user()->verified_id == 'yes' && $user->id == auth()->id())d-flex justify-content-between align-items-center @else d-block @endif mb-3 text-right">

        @if (auth()->check() && auth()->user()->verified_id == 'yes' && $user->id == auth()->id())
        <div>
          @if ($settings->digital_product_sale && ! $settings->custom_content)
            <a class="btn btn-primary" href="{{ url('add/product') }}">
              <i class="bi-plus"></i> <span class="d-lg-inline-block d-none">{{ __('general.add_product') }}</span>
            </a>

          @elseif (! $settings->digital_product_sale && $settings->custom_content)
            <a class="btn btn-primary" href="{{ url('add/custom/content') }}">
              <i class="bi-plus"></i> <span class="d-lg-inline-block d-none">{{ __('general.add_custom_content') }}</span>
            </a>

          @else
            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#addItemForm">
              <i class="bi-plus"></i> <span class="d-lg-inline-block d-none">{{ __('general.add_new') }}</span>
            </a>
          @endif
        </div>
      @endif

        <div>
          <select class="ml-2 custom-select mb-2 mb-lg-0 w-auto" id="filter">
              <option @if (! request()->get('sort')) selected @endif value="{{url($user->username).'/shop'}}">{{__('general.latest')}}</option>
              <option @if (request()->get('sort') == 'oldest') selected @endif value="{{url($user->username).'/shop?sort=oldest'}}">{{__('general.oldest')}}</option>
              <option @if (request()->get('sort') == 'priceMin') selected @endif value="{{url($user->username).'/shop?sort=priceMin'}}">{{__('general.lowest_price')}}</option>
              <option @if (request()->get('sort') == 'priceMax') selected @endif value="{{url($user->username).'/shop?sort=priceMax'}}">{{__('general.highest_price')}}</option>
              @if ($settings->physical_products)
              <option @if (request()->get('sort') == 'physical') selected @endif value="{{url($user->username).'/shop?sort=physical'}}">{{__('general.physical_products')}}</option>
              @endif
              <option @if (request()->get('sort') == 'digital') selected @endif value="{{url($user->username).'/shop?sort=digital'}}">{{__('general.digital_products')}}</option>
              <option @if (request()->get('sort') == 'custom') selected @endif value="{{url($user->username).'/shop?sort=custom'}}">{{__('general.custom_content')}}</option>
            </select>

            @if ($shopCategories->count())
              <select class="ml-2 custom-select mb-2 mb-lg-0 w-auto filter">
                  <option @if (! request()->get('cat')) selected @endif value="{{url($user->username, 'shop')}}">{{__('general.all_categories')}}</option>

                    @foreach ($shopCategories as $category)
                      <option @if (request()->get('cat') == $category->slug) selected @endif value="{{url($user->username, 'shop')}}{{ '?cat='.$category->slug }}">
                        {{ Lang::has('shop-categories.' . $category->slug) ? __('shop-categories.' . $category->slug) : $category->name }}
                      </option>
                    @endforeach

                </select>
            @endif
        </div>
      </div>
    @endif

      <div class="row">

        @if ($userProducts->count() != 0)

          @foreach ($userProducts as $product)
          <div class="col-md-4 mb-4">
            @include('shop.listing-products')
          </div><!-- end col-md-4 -->
          @endforeach

          @if ($userProducts->hasPages())
            <div class="w-100 d-block">
              {{ $userProducts->onEachSide(0)->appends(['sort' => request('sort')])->links() }}
            </div>
          @endif

        @else

          <div class="my-5 text-center no-updates w-100">
            <span class="btn-block mb-3">
              <i class="feather icon-shopping-bag ico-no-result"></i>
            </span>
          <h4 class="font-weight-light">{{__('general.no_results_found')}}</h4>

        @if (auth()->check() && auth()->user()->verified_id == 'yes' && auth()->id() == $user->id)
          <div class="mt-3">
            @if ($settings->digital_product_sale && ! $settings->custom_content && ! $settings->physical_products)
              <a class="btn btn-primary" href="{{ url('add/product') }}">
                <i class="bi-plus"></i> {{ __('general.add_product') }}
              </a>

            @elseif (! $settings->digital_product_sale && $settings->custom_content && ! $settings->physical_products)
              <a class="btn btn-primary" href="{{ url('add/custom/content') }}">
                <i class="bi-plus"></i> {{ __('general.add_custom_content') }}
              </a>

            @elseif (! $settings->digital_product_sale && $settings->physical_products && ! $settings->custom_content)
              <a class="btn btn-primary" href="{{ url('add/physical/product') }}">
                <i class="bi-plus"></i> {{ __('general.add_physical_product') }}
              </a>

            @else
              <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#addItemForm">
                <i class="bi-plus"></i> {{ __('general.add_new') }}
              </a>
            @endif
          </div>
        @endif

          </div>

        @endif
      </div>
    </div><!-- container -->

    @includeWhen(auth()->check() && auth()->user()->verified_id == 'yes', 'shop.modal-add-item')

  @endif

    @if (auth()->check() && auth()->id() != $user->id)
   <div class="modal fade modalReport" id="reportCreator" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
     <!-- form start -->
			<form method="POST"
      action="{{ url('report/creator', $user->id) }}"
      enctype="multipart/form-data"
      id="reportForm">

    <div class="modal-body">
        @csrf

        <!-- Motivo -->
        <div class="form-group">
            <label>{{ __('admin.please_reason') }}</label>
            <select name="reason" class="form-control custom-select" required>
                <option value="spoofing">{{ __('admin.spoofing') }}</option>
                <option value="copyright">{{ __('admin.copyright') }}</option>
                <option value="privacy_issue">{{ __('admin.privacy_issue') }}</option>
                <option value="violent_sexual">{{ __('admin.violent_sexual_content') }}</option>
                <option value="spam">{{ __('general.spam') }}</option>
                <option value="fraud">{{ __('general.fraud') }}</option>
            </select>

            <textarea
                name="message"
                maxlength="200"
                placeholder="{{ __('general.message') }} ({{ __('general.optional') }})"
                class="form-control mt-2 textareaAutoSize"></textarea>
        </div>

        <!-- Upload do comprovante -->
        <div class="form-group mt-3">
            <label>
                {{ __('general.proof_of_complaint') }}
            </label>

            <p class="text-muted mb-2" style="font-size: 12px;">
                {{ __('general.proof_of_complaint_description') }}
            </p>

            <input
                type="file"
                name="proof_of_complaint"
                id="proofOfComplaint"
                accept=".jpg,.jpeg,.png,.pdf"
                hidden
            >

            <button
                type="button"
                class="btn btn-outline-primary btn-sm"
                onclick="document.getElementById('proofOfComplaint').click()">
                {{ __('general.select_proof_of_complaint') }}
            </button>

            <span id="proofFileName" class="ml-2 text-muted" style="font-size: 12px;"></span>

            <small style="font-size:10px;" class="text-muted d-block mt-2">
                (JPG, PNG, PDF • 5MB)
            </small>
        </div>
    </div>

    <!-- Footer -->
    <div class="modal-footer" style="border:0">

        <button type="submit"
                class="btn btn-block btn-primary"
                id="submitBtn">
            <span class="btn-text">{{ __('general.report_user') }}</span>
            <span class="spinner-border spinner-border-sm d-none"
                  role="status"
                  aria-hidden="true"></span>
        </button>
    </div>
</form>
          </div><!-- Modal content -->
        </div><!-- Modal dialog -->
      </div><!-- Modal reportCreator -->
    @endif

    @if (auth()->check() && auth()->id() != $user->id && ! $checkSubscription  && $user->verified_id == 'yes')

    @if ($user->free_subscription == 'no')
    <div class="modal fade" id="subscriptionForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="card bg-white shadow border-0">
              <div class="card-header pb-2 border-0 position-relative" style="height: 100px; background: {{$settings->color_default}} @if ($user->cover != '')  url('{{Helper::getFile(config('path.cover').$user->cover)}}') no-repeat center center @endif; background-size: cover;">

              </div>
              <div class="card-body px-lg-5 py-lg-5 position-relative">

                <div class="text-muted text-center mb-3 position-relative modal-offset">
                  <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" width="100" alt="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" class="avatar-modal rounded-circle mb-1">
                  <h6>
                    {!! __('general.subscribe_month', ['price' => '<span class="font-weight-bold">'.Helper::formatPrice($user->getPlan('monthly', 'price'), true).'</span>']) !!} {{__('general.unlocked_content')}} {{$user->hide_name == 'yes' ? $user->username : $user->name}}
                  </h6>
                </div>

                @if ($totalPosts == 0)
                  <div class="alert alert-warning fade show small" role="alert">
                    <i class="fa fa-exclamation-triangle mr-1"></i> {{ $user->first_name }} {{ __('general.not_posted_any_content') }}
                  </div>
                @endif
				  
                <ul class="list-unstyled none">
                  <li><i class="fa fa-check mr-2 @if (auth()->user()->dark_mode == 'on') text-white @else text-primary @endif"></i> {{__('general.full_access_content')}}</li>
                  <li><i class="fa fa-check mr-2 @if (auth()->user()->dark_mode == 'on') text-white @else text-primary @endif"></i> {{__('general.direct_message_with_this_user')}}</li>
                  <li><i class="fa fa-check mr-2 @if (auth()->user()->dark_mode == 'on') text-white @else text-primary @endif"></i> {{__('general.cancel_subscription_any_time')}}</li>
                </ul>

                <form method="post" action="{{url('buy/subscription')}}" id="formSubscription">
                  @csrf

                  <input type="hidden" name="id" value="{{$user->id}}"  />
                  <input name="interval" value="monthly" id="plan-monthly" class="d-none" type="radio">

                  @foreach ($plans as $plan)
                    <input name="interval" value="{{ $plan->interval }}" id="plan-{{ $plan->interval }}" class="d-none" type="radio">
                  @endforeach

                  @if ($settings->disable_wallet == 'on' && Helper::userWallet('balance') != 0 || $settings->disable_wallet == 'off')
                  <div class="custom-control none custom-radio mb-3">
                    <input name="payment_gateway" required value="wallet" id="radio0" class="custom-control-input" checked type="radio">
                    <label class="custom-control-label" for="radio0">
                      <span>
                        <strong>
                        <i class="fas fa-wallet mr-1 icon-sm-radio"></i> {{ __('general.wallet') }}
                        <span class="w-100 d-block font-weight-light">
                          {{ __('general.available_balance') }}: <span class="font-weight-bold mr-1">{{Helper::userWallet()}}</span>

                          @if (Helper::userWallet('balance') != 0 && $settings->wallet_format != 'real_money')
                            <i class="bi-info-circle text-muted" data-toggle="tooltip" data-placement="top" title="{{Helper::equivalentMoney($settings->wallet_format)}}"></i>
                          @endif

                          @if (Helper::userWallet('balance') == 0)
                          <a href="{{ url('my/wallet') }}" class="link-border">{{ __('general.recharge') }}</a>
                        @endif
                        </span>
                        <span class="w-100 d-block small">{{ __('general.automatically_renewed_wallet') }}</span>
                      </strong>
                      </span>
                    </label>
                  </div>
                @endif

                  <div class="alert alert-danger display-none" id="error">
                      <span class="m-0" id="showErrors"></span>
                    </div>

                  @if ($taxRatesCount != 0 && auth()->user()->isTaxable()->count())
                  <ul class="list-group list-group-flush border-dashed-radius mt-3">
                  	@foreach (auth()->user()->isTaxable() as $tax)
                  		<li class="list-group-item py-1 list-taxes">
                  	    <div class="row">
                  	      <div class="col">
                  	        <small>{{ $tax->name }} {{ $tax->percentage }}% {{ __('general.applied_price') }}</small>
                  	      </div>
                  	    </div>
                  	  </li>
                  	@endforeach
                  </ul>
                @endif

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-4 w-100 subscriptionBtn" onclick="$('#plan-monthly').trigger('click');">
                      <i></i> {{__('general.subscribe_month', ['price' => Helper::formatPrice($user->getPlan('monthly', 'price'), true)])}}
                    </button>

                    @if ($plans->count())
                      <a style=" font-size: 14px; " class="d-block my-3 btn-arrow-expand-bi" data-toggle="collapse" href="#collapseSubscriptionBundles" role="button" aria-expanded="false" aria-controls="collapseExample">
                         {{ __('general.subscription_bundles') }} <i class="bi-chevron-down transition-icon"></i>
                      </a>

                      <div class="collapse" id="collapseSubscriptionBundles">
                        @foreach ($plans as $plan)
                          <button type="submit" class="btn btn-primary mt-2 w-100 subscriptionBtn" onclick="$('#plan-{{$plan->interval}}').trigger('click');">
                            <i></i> {{__('general.subscribe_'.$plan->interval, ['price' => Helper::formatPrice($plan->price, true)])}}
                          </button>

                          @if (Helper::calculateSubscriptionDiscount($plan->interval, $user->getPlan('monthly', 'price'), $plan->price) > 0)
                            <small class="@if (auth()->user()->dark_mode == 'on') text-white @else text-success @endif subscriptionDiscount">
                              <em>{{ Helper::calculateSubscriptionDiscount($plan->interval, $user->getPlan('monthly', 'price'), $plan->price) }}% {{ __('general.discount') }}  </em>
                            </small>
                          @endif

                        @endforeach
                      </div>

                    @endif

                    <div class="w-100 mt-2">
                      <button type="button" class="btn e-none p-0" data-dismiss="modal">{{__('admin.cancel')}}</button>
                    </div>
                  </div>

                  @include('includes.site-billing-info')
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Modal Subscription -->
    @endif

    <!-- Subscription Free -->
    <div class="modal fade" id="subscriptionFreeForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="card bg-white shadow border-0">
              <div class="card-header pb-2 border-0 position-relative" style="height: 100px; background: {{$settings->color_default}} @if ($user->cover != '')  url('{{Helper::getFile(config('path.cover').$user->cover)}}') no-repeat center center @endif; background-size: cover;">

              </div>
              <div class="card-body px-lg-5 py-lg-5 position-relative">

                <div class="text-muted text-center mb-3 position-relative modal-offset">
                  <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" width="100" alt="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" class="avatar-modal rounded-circle mb-1">
                  <h6>
                    {{__('general.subscribe_free_content') }} {{$user->hide_name == 'yes' ? $user->username : $user->name}}
                  </h6>
                </div>

                @if ($totalPosts == 0)
                  <div class="alert alert-warning fade show small" role="alert">
                    <i class="fa fa-exclamation-triangle mr-1"></i> {{ $user->first_name }} {{ __('general.not_posted_any_content') }}
                  </div>
                @endif

                <ul class="list-unstyled none">
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{__('general.full_access_content')}}</li>
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{__('general.direct_message_with_this_user')}}</li>
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{__('general.cancel_subscription_any_time')}}</li>
                </ul>

                <div class="w-100 text-center">
                  <a href="javascript:void(0);" data-id="{{ $user->id }}" id="subscribeFree" class="btn btn-primary btn-profile mr-1">
                    <i class="feather icon-user-plus mr-1"></i> {{__('general.subscribe_for_free')}}
                  </a>
                  <div class="w-100 mt-2">
                    <button type="button" class="btn e-none p-0" data-dismiss="modal">{{__('admin.cancel')}}</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Modal Subscription Free -->
  @endif

 @include('includes.modal-highlightProfile')
 @include('includes.modal-become-pro')

<!-- Modal de aviso antes de trocar avatar -->
<div class="modal fade" id="avatarWarningModal" tabindex="-1" role="dialog" aria-labelledby="avatarWarningLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger" id="avatarWarningLabel">
                    <i class="bi bi-exclamation-triangle-fill mr-2"></i> Aviso importante
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-left pt-2">
                <p style="font-weight: 400 !important;" class="mb-3">
                   É proibido usar imagens com nudez, órgãos genitais ou que não sejam tuas como foto de perfil ou capa.
                </p>
				
                <p class="text-muted small mb-0">
                   A violação das Regras n.º 6 e 9 do Oprivado poderá resultar na suspensão ou remoção da sua conta, sem aviso prévio, sobretudo no caso de criadores de conteúdos
                </p>
				
				<img src="https://oprivado.ao/public/images/reco.png" alt="Foto de perfil" width="100%">
				
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary mr-1" id="confirmChangeAvatar">
                    Escolher imagem
                </button>
				
				<button type="button" class="btn btn-google" data-dismiss="modal">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')


@if (auth()->check() && auth()->id() == $user->id)
<script src="{{ asset('public/js/upload-avatar-cover.js') }}?v={{$settings->version}}"></script>
@endif

<script src="{{ asset('public/js/qrcode.min.js') }}?v={{$settings->version}}"></script>

<script type="text/javascript">
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('select-proof')) {
        const id = e.target.getAttribute('data-target');
        document.getElementById('proofOfComplaint-' + id).click();
    }
});

document.addEventListener('change', function (e) {
    if (e.target.type === 'file' && e.target.id.startsWith('proofOfComplaint-')) {
        const id = e.target.id.replace('proofOfComplaint-', '');
        const fileName = e.target.files.length ? e.target.files[0].name : '';
        document.getElementById('proofFileName-' + id).innerText = fileName;
    }
});

document.getElementById('proofOfComplaint').addEventListener('change', function () {
    const fileName = this.files.length ? this.files[0].name : '';
    document.getElementById('proofFileName').innerText = fileName;
});

document.getElementById('reportForm').addEventListener('submit', function () {
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    submitBtn.disabled = true;
    cancelBtn.disabled = true;

    submitBtn.querySelector('.btn-text').classList.add('d-none');
    submitBtn.querySelector('.spinner-border').classList.remove('d-none');
});

@if ($settings->generate_qr_code && $user->verified_id == 'yes')
new QRCode(document.getElementById("QrCode"), "{{ url($user->username).Helper::referralLink() }}");

const downloadQR = () => {
    let link = document.createElement('a');
    link.download = "QR {{ '@'.$user->username }}.png";
    link.href = document.querySelector('#QrCode canvas').toDataURL();
    link.click();
}

$(document).on('click','#downloadQr', function(e) {
    downloadQR();
});
@endif

@auth
$('.subsCCBill').on('click', function() {
    $(this).blur();
    var expiration = $(this).attr('data-expiration');
    swal({
        html: true,
        title: "{{ __('general.unsubscribe') }}",
        text: "{!! __('general.cancel_subscription_ccbill', ['ccbill' => '<a href=\'https://support.ccbill.com/\' target=\'_blank\'>https://support.ccbill.com</a>']) !!} " + expiration,
        type: "info",
        confirmButtonText: "{{ __('users.ok') }}"
    });
});
@endauth

@if (session('noty_error'))
swal({
    title: "{{ __('general.error_oops') }}",
    text: "{{ __('general.already_sent_report') }}",
    type: "error",
    confirmButtonText: "{{ __('users.ok') }}"
});
@endif

@if (session('noty_success'))
swal({
    title: "{{ __('general.thanks') }}",
    text: "{{ __('general.reported_success') }}",
    type: "success",
    confirmButtonText: "{{ __('users.ok') }}"
});
@endif

$('.dropdown-menu.d-menu').on({
    "click": function(e) {
        e.stopPropagation();
    }
});

@if (session('subscription_success'))
swal({
    html: true,
    title: "{{ __('general.congratulations') }}",
    text: "{!! session('subscription_success') !!}",
    type: "success",
    confirmButtonText: "{{ __('users.ok') }}"
});
@endif

@if (session('subscription_cancel'))
swal({
    title: "{{ __('general.canceled') }}",
    text: "{{ session('subscription_cancel') }}",
    type: "error",
    confirmButtonText: "{{ __('users.ok') }}"
});
@endif

@if (session('success_verify'))
swal({
    title: "{{ __('general.welcome') }}",
    text: "{{ __('users.account_validated') }}",
    type: "success",
    confirmButtonText: "{{ __('users.ok') }}"
});
@endif

@if (session('error_verify'))
swal({
    title: "{{ __('general.error_oops') }}",
    text: "{{ __('users.code_not_valid') }}",
    type: "error",
    confirmButtonText: "{{ __('users.ok') }}"
});
@endif

@if (session('error_cancel'))
swal({
    title: "{{ __('general.error_oops') }}",
    text: "{{ __('general.payment_card_error') }}",
    type: "error",
    confirmButtonText: "{{ __('users.ok') }}"
});
@endif

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-proof')) {
            const id = e.target.getAttribute('data-target');
            const input = document.getElementById('proofOfComplaint-' + id);
            if (input) input.click();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.type === 'file' && e.target.id.startsWith('proofOfComplaint-')) {
            const id = e.target.id.split('-')[1];
            const span = document.getElementById('proofFileName-' + id);
           
            if (span && e.target.files.length > 0) {
                span.textContent = e.target.files[0].name;
            }
        }
    });
});
</script>

<script>
$(document).ready(function() {

 
    let pendingUploadType = null; 


    $('#avatarWarningTrigger').on('click', function(e) {
        e.preventDefault();
        pendingUploadType = 'avatar';
        $('#avatarWarningModal').modal('show');
    });


    $('#coverFile').on('click', function(e) {
        e.preventDefault();
        pendingUploadType = 'cover';
        $('#avatarWarningModal').modal('show');
    });

  
    $('#confirmChangeAvatar').on('click', function() {
        $('#avatarWarningModal').modal('hide');

        setTimeout(function() {
            if (pendingUploadType === 'avatar') {
                $('#uploadAvatar').trigger('click');
            } 
            else if (pendingUploadType === 'cover') {
                $('#uploadCover').trigger('click');
            }

            pendingUploadType = null;
        }, 300);
    });


    $('#formHighlightProfile').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#btnConfirmHighlight');
        btn.data('original-text', btn.html());
        btn.prop('disabled', true)
           .html('<i class="spinner-border spinner-border-sm mr-1"></i> Processando...');

        $.ajax({
            url: '/highlight-profile',
            type: 'POST',
            data: $(this).serialize(),

            success: function(response) {
                if (response.success) {
                    $('#highlightProfileModal').modal('hide');
                    swal({
                        title: "{{ __('general.congratulations') }}",
                        text: response.message,
                        type: "success",
                        confirmButtonText: "{{ __('users.ok') }}"
                    }, function() {
                        location.reload();
                    });
                } else {
                    if (response.message && response.message.toLowerCase().includes('saldo')) {
                        $('#insufficientWarning').removeClass('display-none');
                    } else {
                        $('#errorHighlight')
                            .removeClass('display-none')
                            .find('span')
                            .text(response.message);
                    }
                    resetButton();
                }
            },

            error: function(xhr) {
                let message = xhr.responseJSON?.message || 'Ocorreu um erro ao processar.';
                $('#errorHighlight')
                    .removeClass('display-none')
                    .find('span')
                    .text(message);
                resetButton();
            }
        });
    });

    $('#formBecomePro').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#btnConfirmBecomePro');
        btn.data('original-text', btn.html());
        btn.prop('disabled', true)
           .html('<i class="spinner-border spinner-border-sm mr-1"></i> Processando...');

        $.ajax({
            url: '/become-pro',
            type: 'POST',
            data: $(this).serialize(),

            success: function(response) {
                if (response.success) {
                    $('#becomeProModal').modal('hide');
                    swal({
                        title: "{{ __('general.congratulations') }}",
                        text: response.message,
                        type: "success",
                        confirmButtonText: "{{ __('users.ok') }}"
                    }, function() {
                        location.reload();
                    });
                } else {
                    if (response.message && response.message.toLowerCase().includes('saldo')) {
                        $('#insufficientProWarning').removeClass('display-none');
                    } else {
                        $('#errorBecomePro')
                            .removeClass('display-none')
                            .find('span')
                            .text(response.message);
                    }
                    resetProButton();
                }
            },

            error: function(xhr) {
                let message = xhr.responseJSON?.message || 'Ocorreu um erro ao processar.';
                $('#errorBecomePro')
                    .removeClass('display-none')
                    .find('span')
                    .text(message);
                resetProButton();
            }
        });
    });

    function resetButton() {
        const btn = $('#btnConfirmHighlight');
        btn.prop('disabled', false)
           .html(btn.data('original-text') || 'Destacar Agora');
    }

    function resetProButton() {
        const btn = $('#btnConfirmBecomePro');
        btn.prop('disabled', false)
           .html(btn.data('original-text') || 'Confirmar');
    }

});
</script>
@endsection
@php session()->forget('subscription_cancel') @endphp
@php session()->forget('subscription_success') @endphp
