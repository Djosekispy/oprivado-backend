 @foreach ($updates as $response)
		
 @php
    $isAdultContent = false;

    if (isset($response->categories) && $response->categories->isNotEmpty()) {
        $adultSlugs = ['adulto', '18+', 'nsfw', 'erotic', 'conteudo-adulto', 'xxx'];
        
        $isAdultContent = $response->categories->contains(function ($category) use ($adultSlugs) {
            return in_array(strtolower($category->slug ?? ''), $adultSlugs)
                || (isset($category->is_adult) && $category->is_adult == true);
        });
    }

    if (!$isAdultContent && isset($response->creator) && isset($response->creator->content_type)) {
        $isAdultContent = $response->creator->content_type == 15;
    }

    $userAcceptsSensitive = false;
    if (auth()->check()) {
        $userAcceptsSensitive = auth()->user()->acceptSensitiveContent == true 
                             || auth()->user()->acceptSensitiveContent == 1
                             || auth()->user()->acceptSensitiveContent === 'yes';
    }

    $shouldBlur = $isAdultContent && !$userAcceptsSensitive;

    $adultClass = $shouldBlur ? 'adult-content-blur' : '';

	$canComment = auth()->check() && auth()->user()->wallet > 10 && !in_array(auth()->user()->verified_id, ['no', 'reject']);
@endphp

@php
			if (auth()->check()) {
				$checkUserSubscription = auth()->user()->checkSubscription($response->creator);
				$checkPayPerView = auth()->user()->payPerView()->where('updates_id', $response->id)->first();
			}
		$activePromotion = $response->activePromotion()->first();
				$creatorLive = Helper::isCreatorLive($getCurrentLiveCreators , $response->creator->id);

		$totalLikes = number_format($response->likes->count());
		$totalComments = $response->totalComments();
		$mediaCount = $response->media->count();
		$allFiles = $response->media()->groupBy('type')->get();
		$getFirstFile = $response->media()->whereIn('type', ['image', 'video'])->where('video_embed', '')->first();

		$mediaImageVideo = $response->media()
				->whereIn('type', ['image', 'video'])
				->where('video_embed', '')
				->get();

		if ($getFirstFile && $getFirstFile->type == 'image') {
			$urlMedia =  url('/public/images/icon3.png');
			$backgroundPostLocked = 'background: url('.$urlMedia.') no-repeat center center #f9f6f2;background-size: 49%;background-repeat: no-repeat;background-position: left 151%;';
			$textWhite = 'text-white';

		} elseif ($getFirstFile && $getFirstFile->type == 'video' && $getFirstFile->video_poster) {
				$videoPoster = url('/public/images/icon3.png');
				$backgroundPostLocked = 'background: url('.$videoPoster.') no-repeat center center #f9f6f2;background-size: 49%;background-repeat: no-repeat;background-position: left 151%;';
				$textWhite = 'text-white';

		} else {
			$backgroundPostLocked = "background-image: url('/public/images/icon3.png'); background-size: 49%;background-repeat: no-repeat; background-position: left 151%;";

			$textWhite = null;
		}

		$countFilesImage = $response->media->where('type', 'image')->count();
		$countFilesVideo = $response->media->whereIn('type', ['video', 'video_embed'])->count();
		$countFilesAudio = $response->media->where('type', 'music')->count();
		$mediaImageVideoTotal = $response->media->whereIn('type', ['image', 'video'])->count();

		$isVideoEmbed = $response->media[0]->video_embed ?? false;

		$nth = 0; // nth foreach nth-child(3n-1)
		
	@endphp

 
	<div class="card mb-3 card-updates views rounded-large shadow-large card-border-0 @if ($shouldBlur) adult-content-blur @endif"  @if ($response->status == 'pending') post-pending @endif @if ($response->fixed_post == '1' && request()->path() == $response->creator->username || auth()->check() && $response->fixed_post == '1' && $response->creator->id == auth()->user()->id) pinned-post @endif" data="{{$response->id}}">
	<div class="card-body">
		<div class="pinned_post text-muted small w-100 mb-2 {{ $response->fixed_post == '1' && request()->path() == $response->creator->username || auth()->check() && $response->fixed_post == '1' && $response->creator->id == auth()->user()->id ? 'pinned-current' : 'display-none' }}">
			<i class="bi bi-pin mr-2"></i> {{ __('general.pinned_post') }}
		</div>

		
		@if ($response->status == 'pending')
			<h6 class="text-muted w-100 mb-4">
				<i class="bi bi-eye-fill mr-1"></i> <em>{{ __('general.post_pending_review') }}</em>
			</h6>
		@endif

		@if ($response->status == 'schedule')
			<h6 class="text-muted w-100 mb-4">
				<i class="bi-calendar-fill mr-1"></i> <em>{{ __('general.date_schedule') }} {{ Helper::formatDateSchedule($response->scheduled_date) }}</em>
			</h6>
		@endif

	<div class="media ">
		<span class="rounded-circle mr-3 position-relative">
			<a href="{{$creatorLive ? url('live', $response->creator->username) : url($response->creator->username)}}">

				@if (auth()->check() && $creatorLive)
					<span class="live-span">{{ __('general.live') }}</span>
				@endif

				<img src="{{ Helper::getFile(config('path.avatar').$response->creator->avatar) }}" alt="{{$response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name}}" class="rounded-circle avatarUser" width="40" height="40">
				</a>
		</span>

		<div class="media-body">
				<h5 class="mb-0 font-montserrat">
					<a style="color:black!important;" href="{{url($response->creator->username)}}">
					{{$response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name}}
				</a>

				@if($response->creator->verified_id == 'yes')
					<small class="verified" title="{{__('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
						<i class="bi bi-patch-check-fill"></i>
					</small>
				@endif
					
					 @if(
			auth()->check()
			&& auth()->id() != $response->creator->id
			&& !auth()->user()->checkSubscription($response->creator)
			&& $response->creator->free_subscription == 'yes'
		)
					
            <button
                class="seguir ml-2 followCreator"
                data-id="{{ $response->creator->id }}"
				onClick="javascript:void(0);" 
				id="subscribeFree"
					>
                Seguir
            </button>
		 @elseif(
			auth()->check()
			&& auth()->id() != $response->creator->id
			&& !auth()->user()->checkSubscription($response->creator)
			&& $response->creator->free_subscription == 'no'
		)
					
            <a class="seguir none ml-2 followCreator" href="{{url($response->creator->username)}}">
               Subscrever
            </a>
        @endif

				@if (auth()->check() && auth()->user()->id == $response->creator->id)
				<a href="javascript:void(0);" class="text-muted float-right" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="fa fa-ellipsis-h"></i>
				</a>

				<!-- Target -->
				<button class="d-none copy-url" id="url{{$response->id}}" data-clipboard-text="{{url($response->creator->username.'/post', $response->id)}}">{{__('general.copy_link')}}</button>

				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">
					@if (request()->path() != $response->creator->username.'/post/'.$response->id)
						<a class="dropdown-item mb-1" href="{{url($response->creator->username.'/post', $response->id)}}"><i class="bi bi-box-arrow-in-up-right mr-2"></i> {{__('general.go_to_post')}}</a>
					@endif

					@if ($response->status == 'active')
						<a class="dropdown-item mb-1 pin-post" href="javascript:void(0);" data-id="{{$response->id}}">
							<i class="bi bi-pin mr-2"></i> {{$response->fixed_post == '0' ? __('general.pin_to_your_profile') : __('general.unpin_from_profile') }}
						</a>
					@endif

					<button class="dropdown-item mb-1" onclick="$('#url{{$response->id}}').trigger('click')"><i class="feather icon-link mr-2"></i> {{__('general.copy_link')}}</button>
					
						@if ($activePromotion)
						{!! Form::open([
							'method' => 'POST',
							'url' => route('post.promote.remove', $activePromotion->id),
							'class' => 'd-inline'
						]) !!}
						{!! Form::button('<i class="bi bi-x-circle mr-2"></i> Remover promoção', ['class' => 'dropdown-item mb-1 text-danger', 'onclick' => 'return confirm("Tem a certeza que deseja remover esta promoção?")']) !!}
						{!! Form::close() !!}
					@else
						<button type="button" class="dropdown-item mb-1" data-toggle="modal" data-target="#promotePostModal-{{$response->id}}">
							<i class="bi bi-megaphone mr-2"></i> Promover publicação
						</button>
					@endif


					<a href="{{ route('post.edit', ['id' => $response->id]) }}" class="dropdown-item mb-1">
						<i class="bi bi-pencil mr-2"></i> {{__('general.edit_post')}}
					</a>

					{!! Form::open([
						'method' => 'POST',
						'url' => "update/delete/$response->id",
						'class' => 'd-inline'
					]) !!}

					@if (isset($inPostDetail))
					{!! Form::hidden('inPostDetail', 'true') !!}
				@endif

					{!! Form::button('<i class="feather icon-trash-2 mr-2"></i> '.__('general.delete_post'), ['class' => 'dropdown-item mb-1 actionDelete']) !!}
					{!! Form::close() !!}
	      </div>
			@endif

				@if(auth()->check()
					&& auth()->user()->id != $response->creator->id
					&& $response->locked == 'yes'
					&& $checkUserSubscription && $response->price == 0.00

					|| auth()->check()
						&& auth()->user()->id != $response->creator->id
						&& $response->locked == 'yes'
						&& $checkUserSubscription
						&& $response->price != 0.00
						&& $checkPayPerView

					|| auth()->check()
						&& auth()->user()->id != $response->creator->id
						&& $response->price != 0.00
						&& ! $checkUserSubscription
						&& $checkPayPerView

					|| auth()->check() && auth()->user()->id != $response->creator->id && auth()->user()->role == 'admin' && auth()->user()->permission == 'all'
					|| auth()->check() && auth()->user()->id != $response->creator->id && $response->locked == 'no'
					)
					<a href="javascript:void(0);" class="text-muted float-right" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="fa fa-ellipsis-h"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">

						<!-- Target -->
						<button class="d-none copy-url" id="url{{$response->id}}" data-clipboard-text="{{url($response->creator->username.'/post', $response->id).Helper::referralLink()}}">
							{{__('general.copy_link')}}
						</button>

						@if (request()->path() != $response->creator->username.'/post/'.$response->id)
							<a class="dropdown-item" href="{{url($response->creator->username.'/post', $response->id)}}">
								<i class="bi bi-box-arrow-in-up-right mr-2"></i> {{__('general.go_to_post')}}
							</a>
						@endif

						<button class="dropdown-item" onclick="$('#url{{$response->id}}').trigger('click')">
							<i class="feather icon-link mr-2"></i> {{__('general.copy_link')}}
						</button>

						<button type="button" class="dropdown-item" data-toggle="modal" data-target="#reportUpdate{{$response->id}}">
							<i class="bi bi-flag mr-2"></i>  {{__('admin.report')}}
						</button>

					</div>

			<div class="modal fade modalReport" id="reportUpdate{{$response->id}}" tabindex="-1" role="dialog" aria-hidden="true">
     		<div class="modal-dialog modal-sm">
     			<div class="modal-content">
					<!-- form start -->
					<form method="POST" action="{{url('report/update', $response->id)}}" enctype="multipart/form-data">
				  <div class="modal-body">
						@csrf
				    <!-- Start Form Group -->
            <div class="form-group">
				
              <label>{{__('admin.please_reason')}}</label>
              	<select name="reason" class="form-control custom-select">
                    <option value="copyright">{{__('admin.copyright')}}</option>
                    <option value="privacy_issue">Violação de regulamentos</option>
                    <option value="violent_sexual">{{__('admin.violent_sexual_content')}}</option>
                  </select>

				  <textarea name="message" rows="" cols="40" maxlength="200" placeholder="{{__('general.message')}} ({{ __('general.optional') }})" class="form-control mt-2 textareaAutoSize"></textarea>
                  </div><!-- /.form-group-->
					  
					  
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
    id="proofOfComplaint-{{ $response->id }}"
    accept=".jpg,.jpeg,.png,.pdf"
    hidden
>

<button
    type="button"
    class="btn btn-outline-primary btn-sm select-proof"
    data-target="{{ $response->id }}">
    {{ __('general.select_proof_of_complaint') }}
</button>

<span
    id="proofFileName-{{ $response->id }}"
    class="ml-2 text-muted"
    style="font-size: 13px;">
</span>

            <small style="font-size:10px;" class="text-muted d-block mt-2">
                (JPG, PNG, PDF • 5MB)
            </small>
        </div>
				      </div><!-- Modal body -->

							<div style="border-top: 0px solid;" class="modal-footer">
								<button type="submit" class="btn btn-primary btn-block sendReport "><i></i> {{__('admin.report_update')}}</button>
							</div>
							</form>
     				</div><!-- Modal content -->
     			</div><!-- Modal dialog -->
     		</div><!-- Modal -->
				@endif
			</h5>

				<small class="timeAgo text-muted" data="{{date('c', strtotime($response->date))}}"></small>

				@if ($response->locked == 'no')
				<small class="text-muted type-post" title="{{__('general.public')}}">
					<i class="iconmoon icon-WorldWide mr-1"></i>
				</small>
				@endif

			@if ($response->locked == 'yes')

				<small class="text-muted type-post" title="{{__('users.content_locked')}}">

					<i class="feather icon-lock mr-1"></i>

					@if (auth()->check() && $response->price != 0.00
							&& $checkUserSubscription
							&& ! $checkPayPerView
							|| auth()->check() && $response->price != 0.00
							&& ! $checkUserSubscription
							&& ! $checkPayPerView
						)
						{{ Helper::formatPrice($response->price) }}

					@elseif (auth()->check() && $checkPayPerView)
						{{ __('general.paid') }}
					@endif
				</small>
			@endif

		</div><!-- media body -->
	</div><!-- media -->
</div><!-- card body -->

		@if (auth()->check() && auth()->user()->id == $response->creator->id)
	<div class="modal fade" id="promotePostModal-{{$response->id}}" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form method="POST" action="{{ route('post.promote', $response->id) }}">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title">Promover publicação</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="text-muted mb-2">Custo diário: <strong>2.000 Kz</strong></div>
						<label for="days-{{$response->id}}">Nº de dias</label>
						<input id="days-{{$response->id}}" type="number" name="days" min="1" max="365" value="1" class="form-control promo-days-input" data-target="#promo-total-{{$response->id}}" data-wallet="{{ auth()->user()->wallet }}" data-daily-price="2000">
						<div class="mt-3 font-weight-bold">Total: <span id="promo-total-{{$response->id}}">2.000 Kz</span></div>
						<div class="alert alert-danger promo-insufficient-funds mt-3 mb-0 @if (auth()->user()->wallet >= 2000) d-none @endif" role="alert">
							Saldo insuficiente, por favor carregue a carteira.
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary btn-sm promo-confirm-button" @if (auth()->user()->wallet < 2000) disabled @endif>Confirmar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endif
		
@if (auth()->check() && auth()->user()->id == $response->creator->id
	|| $response->locked == 'yes' && $mediaCount != 0

	|| auth()->check() && $response->locked == 'yes'
	&& $checkUserSubscription
	&& $response->price == 0.00

	|| auth()->check() && $response->locked == 'yes'
	&& $checkUserSubscription
	&& $response->price != 0.00
	&& $checkPayPerView

	|| auth()->check() && $response->locked == 'yes'
	&& $response->price != 0.00
	&& ! $checkUserSubscription
	&& $checkPayPerView

	|| auth()->check() && auth()->user()->role == 'admin' && auth()->user()->permission == 'all'
	|| $response->locked == 'no'
	)
	<div class="card-body pt-0 pb-3">
		<p class="mb-0 truncated position-relative text-word-break">
			{!! Helper::linkText(Helper::checkText($response->description, $isVideoEmbed ?? null)) !!}
		</p>
		<a href="javascript:void(0);" class="display-none link-border">{{ __('general.view_all') }}</a>
	</div>

@else
	@if ($response->title)
	<div class="card-body pt-0 pb-3">
		<p class="mb-0 update-text position-relative text-word-break font-weight-bold">
			{!! Helper::linkText($response->title) !!}
		</p>
	</div>
	@endif
@endif

		@if (auth()->check() && auth()->user()->id == $response->creator->id

		|| auth()->check() && $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price == 0.00

		|| auth()->check() && $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price != 0.00
		&& $checkPayPerView

		|| auth()->check() && $response->locked == 'yes'
		&& $response->price != 0.00
		&& ! $checkUserSubscription
		&& $checkPayPerView

		|| auth()->check() && auth()->user()->role == 'admin' && auth()->user()->permission == 'all'
		|| $response->locked == 'no'
		)

	@if ($mediaImageVideoTotal <> 0 && $shouldBlur)

    <div style="background: url(https://oprivado.ao/public/images/icon3.png) no-repeat center center #f9f6f2; background-size: 49%; background-repeat: no-repeat; background-position: left 151%; margin: 0 auto; max-width: 100%; box-sizing: border-box;" class="btn-block text-center pt-lg pb-lg px-3">
        <span class="btn-block text-center mb-3">
            <i class="feather icon-eye-off ico-no-result border-0"></i>
        </span>
<a href="https://oprivado.ao/settings/page#adulto" class="btn btn-primary w-100">
				Conteúdo sensível (adulto)
			</a>
		<ul class="list-inline mt-3"> <li class="list-inline-item"><small class="ml-1">Este conteúdo foi ocultado porque você decidiu não ver conteúdos sensíveis: <a href="https://oprivado.ao/settings/page#adulto">editar</a></small> </li> </ul>
    </div>

@elseif ($mediaImageVideoTotal <> 0)

    <div class="btn-block">

        @include('includes.media-post')

        @foreach ($response->media as $media)

            @if ($media->music != '')
            <div class="mx-3 border rounded @if ($mediaCount > 1) mt-3 @endif">
                <audio id="music-{{$media->id}}" preload="metadata" class="js-player w-100 @if (!request()->ajax())invisible @endif" controls>
                    <source src="{{ Helper::getFile(config('path.music').$media->music) }}" type="audio/mp3">
                </audio>
            </div>
            @endif

            @if ($media->type == 'file')
            <a href="{{url('download/file', $response->id)}}" class="d-block text-decoration-none @if ($mediaCount > 1) mt-3 @endif">
                <div class="card mb-3 mx-3">
                    <div class="row no-gutters">
                        <div class="col-md-2 text-center bg-primary rounded-left">
                            <i class="far fa-file-archive m-4 text-white" style="font-size: 40px;"></i>
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <h5 class="card-title text-primary text-truncate mb-0">
                                    {{ $media->file_name }}.zip
                                </h5>
                                <p class="card-text">
                                    <small class="text-muted">{{ $media->file_size }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endif

            @if ($media->type == 'epub')
            <a href="{{url('viewer/epub', $media->id)}}" target="_blank" class="d-block text-decoration-none @if ($mediaCount > 1) mt-3 @endif">
                <div class="card mb-3 mx-3">
                    <div class="row no-gutters">
                        <div class="col-md-2 text-center bg-primary rounded-left">
                            <i class="fas fa-book-open m-4 text-white" style="font-size: 40px;"></i>
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <h5 class="card-title text-primary text-truncate mb-1">
                                    {{ $media->file_name }}.epub
                                </h5>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <strong>{{ __('general.view_online') }}</strong>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endif

        @endforeach

        @if ($isVideoEmbed)

            @if (in_array(Helper::videoUrl($isVideoEmbed), ['youtube.com','www.youtube.com','youtu.be','www.youtu.be','m.youtube.com']))
                <div class="embed-responsive embed-responsive-16by9 mb-2">
                    <iframe class="embed-responsive-item" height="360"
                        src="https://www.youtube.com/embed/{{ Helper::getYoutubeId($isVideoEmbed) }}"
                        allowfullscreen>
                    </iframe>
                </div>
            @endif

            @if (in_array(Helper::videoUrl($isVideoEmbed), ['vimeo.com','player.vimeo.com']))
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item"
                        src="https://player.vimeo.com/video/{{ Helper::getVimeoId($isVideoEmbed) }}"
                        allowfullscreen>
                    </iframe>
                </div>
            @endif

        @endif

    </div>
@endif
@else

	<div class="btn-block p-sm text-center content-locked pt-lg pb-lg px-3" style="{{$backgroundPostLocked}}">
		<span class="btn-block text-center mb-3"><i class="feather icon-lock ico-no-result border-0"></i></span>

		@if ($response->creator->planActive() && $response->price == 0.00
				|| $response->creator->free_subscription == 'yes' && $response->price == 0.00)
			<a href="{{ request()->route()->named('profile') ? 'javascript:void(0);' : url($response->creator->username) }}" @guest data-toggle="modal" data-target="#loginFormModal" @else @if (request()->route()->named('profile')) @if ($response->creator->free_subscription == 'yes') data-toggle="modal" data-target="#subscriptionFreeForm" @else data-toggle="modal" data-target="#subscriptionForm" @endif @endif @endguest class="btn btn-primary w-100">
				{{ __('general.content_locked_user_logged') }}
			</a>
		@elseif ($response->creator->planActive() && $response->price != 0.00
				|| $response->creator->free_subscription == 'yes' && $response->price != 0.00)
				<a href="javascript:void(0);" @guest data-toggle="modal" data-target="#loginFormModal" @else @if ($response->status == 'active') data-toggle="modal" data-target="#payPerViewForm" data-mediaid="{{$response->id}}" data-price="{{Helper::formatPrice($response->price, true)}}" data-subtotalprice="{{Helper::formatPrice($response->price)}}" data-pricegross="{{$response->price}}" @endif @endguest class="btn btn-primary w-100">
					@guest
						{{ __('general.content_locked_user_logged') }}
					@else

						@if ($response->status == 'active')
								<i class="feather icon-unlock mr-1"></i> {{ __('general.unlock_post_for') }} {{Helper::formatPrice($response->price)}}

							@else
								{{ __('general.post_pending_review') }}
						@endif
						@endguest
				</a>
		@else
			<a href="javascript:void(0);" class="btn btn-primary disabled w-100">
				{{ __('general.subscription_not_available') }}
			</a>
		@endif

		<ul class="list-inline mt-3">

		@if ($mediaCount == 0)
			<li class="list-inline-item"><i class="bi bi-file-font"></i> {{ __('admin.text') }}</li>
		@endif

@if ($mediaCount != 0)
	@foreach ($allFiles as $media)

		@if ($media->type == 'image')
			<li class="list-inline-item"><i class="feather icon-image"></i> {{$countFilesImage}}</li>
		@endif

		@if ($media->type == 'video')
			<li class="list-inline-item"><i class="feather icon-video"></i> {{$countFilesVideo}} @if ($media->duration_video && $countFilesVideo == 1 || $media->quality_video && $countFilesVideo == 1) <small class="ml-1">@if ($media->quality_video)<span class="quality-video">{{ $media->quality_video }}</span>@endif {{ $media->duration_video }}</small> @endif</li>
		@endif

		@if ($media->type == 'music')
			<li class="list-inline-item"><i class="feather icon-mic"></i> {{$countFilesAudio}}</li>
			@endif

			@if ($media->type == 'file')
			<li class="list-inline-item"><i class="far fa-file-archive"></i> {{$media->file_size}}</li>
		@endif

		@if ($media->type == 'epub')
			<li class="list-inline-item"><i class="bi-book"></i> {{$media->file_size}}</li>
		@endif

	@endforeach
@endif
</ul>
		
</div><!-- btn-block parent -->

	@endif

@if ($response->status == 'active')
<div class="card-footer bg-white border-top-0 rounded-large">
    <h4 style="line-height: 0px!important;display: flex;" class="mb-3">
			@php
			$likeActive = auth()->check() && auth()->user()->likes()->where('updates_id', $response->id)->where('status','1')->first();
			$bookmarkActive = auth()->check() && auth()->user()->bookmarks()->where('updates_id', $response->id)->first();

			if(auth()->check() && auth()->user()->id == $response->creator->id

			|| auth()->check() && $response->locked == 'yes'
			&& $checkUserSubscription
			&& $response->price == 0.00

			|| auth()->check() && $response->locked == 'yes'
			&& $checkUserSubscription
			&& $response->price != 0.00
			&& $checkPayPerView

			|| auth()->check() && $response->locked == 'yes'
			&& $response->price != 0.00
			&& ! $checkUserSubscription
			&& $checkPayPerView

			|| auth()->check() && auth()->user()->role == 'admin' && auth()->user()->permission == 'all'
			|| auth()->check() && $response->locked == 'no') {
				$buttonLike = 'likeButton';
				$buttonBookmark = 'btnBookmark';
			} else {
				$buttonLike = null;
				$buttonBookmark = null;
			}
			@endphp

			<a class="pulse-btn btnLike @if ($likeActive)active @endif {{$buttonLike}} text-muted mr-14px" href="javascript:void(0);" @guest data-toggle="modal" data-target="#loginFormModal" @endguest @auth data-id="{{$response->id}}" @endauth>
				<i class="@if($likeActive)feather icon-heart @else feather icon-heart @endif fas fa-heart"></i>
			</a>

			<span class="@auth @if (auth()->user()->checkRestriction($response->creator->id) || !$response->creator->allow_comments) buttonDisabled @else text-muted @endif @else text-muted @endauth disabled mr-14px @auth @if (! isset($inPostDetail) && $buttonLike) pulse-btn toggleComments @endif @endauth">
				<i class="feather icon-message-circle"></i>
			</span>
		
			@if (auth()->check() && auth()->user()->role == 'admin')
		
		@if (auth()->check() && auth()->user()->id == $response->creator->id && ! $activePromotion)
				<button type="button" class="btn btn-outline-primary btn-sm ml-3 d-inline-flex align-items-center" data-toggle="modal" data-target="#promotePostModal-{{$response->id}}">
					<i class="bi bi-megaphone mr-2"></i> Promover Publicação
				</button>
			@endif
		
		@endif

	@auth
		@if (auth()->user()->id != $response->creator->id
					&& $checkUserSubscription && $response->price == 0.00
					&& $settings->disable_tips == 'off'

					|| auth()->user()->id != $response->creator->id
					&& $checkUserSubscription
					&& $response->price != 0.00
					&& $checkPayPerView
					&& $settings->disable_tips == 'off'

					|| auth()->check() && $response->locked == 'yes'
					&& $response->price != 0.00
					&& ! $checkUserSubscription
					&& $checkPayPerView
					&& $settings->disable_tips == 'off'

					|| auth()->user()->id != $response->creator->id
					&& $response->locked == 'no'
					&& $settings->disable_tips == 'off'
					)
<a href="javascript:void(0);" data-toggle="modal" title="{{__('general.tip_')}}" data-target="#tipForm" class="pulse-btn text-muted d-flex align-items-center gap-1 text-decoration-none" @auth data-id="{{$response->id}}" data-cover="{{Helper::getFile(config('path.cover').$response->creator->cover)}}" data-avatar="{{Helper::getFile(config('path.avatar').$response->creator->avatar)}}" data-name="{{$response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name}}" data-userid="{{$response->creator->id}}" @endauth>
	
<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-dollar-sign-icon lucide-badge-dollar-sign"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg>
				<span style="font-size: 14px;font-weight: 400;color: black;margin-left:5px!important;">@lang('general.tip_')</span>
			</a>
		@endif
	@endauth

			<a href="javascript:void(0);" @guest data-toggle="modal" data-target="#loginFormModal" @endguest class="pulse-btn @if ($bookmarkActive) text-primary @else text-muted @endif float-right {{$buttonBookmark}} ml-auto" @auth data-id="{{$response->id}}" @endauth>
				<i class="@if ($bookmarkActive)feather icon-bookmark @else feather  @endif icon-bookmark"></i>
			</a>
		</h4>

		<div class="w-100 mb-3 containerLikeComment">
			<span class="countLikes text-muted dot-item">
				{{ trans_choice('general.like_likes', $totalLikes, ['total' => number_format($totalLikes)]) }}
			</span> 
			<span class="text-muted totalComments dot-item @auth @if (! isset($inPostDetail) && $buttonLike)toggleComments @endif @endauth">
				{{ trans_choice('general.comment_comments', $totalComments, ['total' => number_format($totalComments)]) }}
			</span>
			
			<!-- @if (auth()->check() && auth()->user()->id == $response->creator->id) para apenas dono do perfil ver @endif -->

			@if ($response->video_views)
			  <span class="text-muted dot-item">
				 {{ Helper::formatNumber($response->video_views) }} visualizações
			</span>
			@endif
		</div>

@auth

@if (! auth()->user()->checkRestriction($response->creator->id))
<div class="container-comments @if ( ! isset($inPostDetail)) display-none @endif">

<div class="container-media">
@if($response->comments->count() != 0)

	@php
	  $comments = $response->comments()
	  	->with(['user:id,name,username,avatar,hide_name,verified_id', 'replies', 'likes'])
		->take($settings->number_comments_show)
		->orderBy('id', 'DESC')->get();

	  $data = [];

	  if ($comments->count()) {
	      $data['reverse'] = collect($comments->values())->reverse();
	  } else {
	      $data['reverse'] = $comments;
	  }

	  $dataComments = $data['reverse'];
	  $counter = ($response->comments()->count() - $settings->number_comments_show);
	@endphp

	@if (auth()->user()->id == $response->creator->id

		|| $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price == 0.00

		|| $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price != 0.00
		&& $checkPayPerView

		|| auth()->check() && $response->locked == 'yes'
		&& $response->price != 0.00
		&& ! $checkUserSubscription
		&& $checkPayPerView

		|| auth()->user()->role == 'admin'
		&& auth()->user()->permission == 'all'
		|| $response->locked == 'no')

		@include('includes.comments')

@endif

@endif
	</div><!-- container-media -->

	@if (auth()->user()->id == $response->creator->id

		|| $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price == 0.00

		|| $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price != 0.00
		&& $checkPayPerView

		|| auth()->check() && $response->locked == 'yes'
		&& $response->price != 0.00
		&& ! $checkUserSubscription
		&& $checkPayPerView

		|| auth()->user()->role == 'admin'
		&& auth()->user()->permission == 'all'
		|| $response->locked == 'no')

		<div class="alert alert-danger alert-small dangerAlertComments display-none">
			<ul class="list-unstyled m-0 showErrorsComments"></ul>
		</div><!-- Alert -->

		<div class="isReplyTo display-none w-100 bg-light py-2 px-3 mb-3 rounded">
			{{ __('general.replying_to') }} <span class="username-reply"></span>

			<span class="float-right c-pointer cancelReply" title="{{ __('admin.cancel') }}">
				<i class="bi-x-lg"></i>
			</span>
		</div>

	
		<p style="font-size: 11px;margin-bottom: 3px;" class="text-center"><i class="feather icon-alert-circle"></i> Ao comentar, trate as pessoas com respeito</p><div class="media position-relative pt-3 border-top">
			<div @class(['blocked', 'display-none' => $response->creator->allow_comments])></div>
			<span href="#" @class(['float-left', 'd-none' => !$response->creator->allow_comments || auth()->user()->is_pro == '0' && auth()->user()->verified_id !== 'yes' ])>
				<img src="{{ Helper::getFile(config('path.avatar').auth()->user()->avatar) }}" class="rounded-circle mr-1 avatarUser" width="40">
			</span>
			<div class="media-body">

				@if (!$response->creator->allow_comments)

    <div class="p-2 text-center">
        {{ __('general.comments_disabled') }}
    </div>

@elseif (
    auth()->user()->is_pro == '0' &&
    auth()->user()->verified_id !== 'yes'
)

    <div style="font-size:13px;" class="alert alert-primary">
       <i class="fa fa-info-circle mr-1"></i>Deve se tornar <a href="{{ url(auth()->user()->username) }}">Pro <i style="font-size: 11px;color: #fd7350;" class="bi-star-fill mr-1"></i></a> para conseguir comentar.
    </div>

@endif

				<form action="{{url('comment/store')}}" method="post" @class(['comments-form', 'd-none' => !$response->creator->allow_comments || auth()->user()->is_pro == '0' && auth()->user()->verified_id !== 'yes'])>
					@csrf
					<input type="hidden" name="update_id" value="{{$response->id}}" />
					<input class="isReply" type="hidden" name="isReply" value="" />

					<div>
					<span class="none triggerEmoji" data-toggle="dropdown">
						<i class="bi-emoji-smile"></i>
					</span>

					<div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar" aria-labelledby="dropdownMenuButton">
				    @include('includes.emojis')
				  </div>
				</div>

				<input type="text" name="comment" class="form-control comments inputComment emojiArea border-0" autocomplete="off" placeholder="{{__('general.write_comment')}}"></div>
				</form>
			</div>
			@endif

			</div><!-- container-comments -->
		@endif

			@endauth
  </div><!-- card-footer -->
	@endif
</div><!-- card -->

@if (request()->is('/') && $loop->first && $users->count() != 0
	|| request()->is('explore') && $loop->first && $users->count() != 0
	|| request()->is('my/bookmarks') && $loop->first && $users->count() != 0
	|| request()->is('my/purchases') && $loop->first && $users->count() != 0
	|| request()->is('my/likes') && $loop->first && $users->count() != 0
	)
	<div class="p-3 d-lg-none">
		@include('includes.explore_creators')
	</div>
@endif

@endforeach

		@if (session('promotion_success'))
	<script>
		swal({
			title: @json(__('general.thanks')),
			text: @json(session('promotion_success')),
			type: 'success',
			confirmButtonText: @json(__('users.ok'))
		});
	</script>
@endif

@if (session('promotion_error') || $errors->has('days'))
	<script>
		swal({
			title: @json(__('general.error_oops')),
			text: @json(session('promotion_error') ?: $errors->first('days')),
			type: 'error',
			confirmButtonText: @json(__('users.ok'))
		});
	</script>
@endif


@if (! isset($singlePost))
<div class="card mb-3 pb-4 loadMoreSpin d-none rounded-large shadow-large">
	<div class="card-body">
		<div class="media">
		<span class="rounded-circle mr-3">
			<span class="item-loading position-relative loading-avatar"></span>
		</span>
		<div class="media-body">
			<h5 class="mb-0 item-loading position-relative loading-name"></h5>
			<small class="text-muted item-loading position-relative loading-time"></small>
		</div>
	</div>
</div>
	<div class="card-body pt-0 pb-3">
		<p class="mb-1 item-loading position-relative loading-text-1"></p>
		<p class="mb-1 item-loading position-relative loading-text-2"></p>
		<p class="mb-0 item-loading position-relative loading-text-3"></p>
	</div>
</div>
@endif


@php
if (request()->ajax()) {
	$getHasPages = $updates->count() < $settings->number_posts_show ? false : true;
} else {
	if (request()->route()->named('profile')) {
		$getHasPages = $updates->count() < $settings->number_posts_show ? false : true;
	} else {
		$getHasPages = $hasPages ?? null;
	}
}
@endphp
		
@if ($getHasPages)
	<button rel="next" class="btn btn-primary w-100 text-center loadPaginator d-none" id="paginator">
		{{__('general.loadmore')}}
	</button>
@endif
			
@include('includes.sidebar-top-earners')
		
@include('includes.advertising')

		<script>
document.addEventListener('input', function(event) {
	if (!event.target.classList.contains('promo-days-input')) {
		return;
	}

	const days = Number(event.target.value || 0);
	const wallet = Number(event.target.dataset.wallet || 0);
	const dailyPrice = Number(event.target.dataset.dailyPrice || 2000);
	const total = days * dailyPrice;
	const target = document.querySelector(event.target.dataset.target);
	const form = event.target.closest('form');
	const insufficientFunds = total > wallet;
	const invalidDays = !Number.isInteger(days) || days < 1 || days > 365;

	if (target) {
		target.textContent = new Intl.NumberFormat('pt-AO', {
			minimumFractionDigits: 0,
			maximumFractionDigits: 0
		}).format(total) + ' Kz';
	}

	if (form) {
		const error = form.querySelector('.promo-insufficient-funds');
		const submit = form.querySelector('.promo-confirm-button');

		if (error) {
			error.classList.toggle('d-none', !insufficientFunds);
		}

		if (submit) {
			submit.disabled = insufficientFunds || invalidDays;
		}
	}
});
</script>
		