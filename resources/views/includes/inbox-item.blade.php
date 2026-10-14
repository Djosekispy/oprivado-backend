@php
    // Guarda o remitter uma única vez (evita ~10 queries repetidas por mensagem)
    $remitter = $msg->remitter();
@endphp

<div style="border-radius: 0px !important; border: 0px solid #dbdbdb;" class="card msg-inbox border-bottom m-0 rounded-0">
	<div class="list-group list-group-sm list-group-flush rounded-0">

		<a href="{{ $remitter->status == 'disabled' ? 'javascript:;' : url('messages', [$remitter->id, $remitter->username]) }}"
		class="item-chat list-group-item list-group-item-action text-decoration-none p-4 @if ($msg->status == 'new' && $msg->sender->id != auth()->id()) font-weight-bold unread-chat @endif  @if (request()->id == $remitter->id) active disabled @endif">
			<div class="media">

				@if ($remitter->status == 'disabled')
				<div class="media-left mr-3 position-relative">
					<img class="media-object rounded-circle" src="{{ Helper::getFile(config('path.avatar').$settings->avatar) }}" width="50" height="50">
				</div>
				@else
				<div class="media-left mr-3 position-relative @if ($remitter->active_status_online == 'yes') @if (Cache::has('is-online-' . $remitter->id)) user-online @else user-offline @endif @endif">
					<img class="media-object rounded-circle" src="{{ Helper::getFile(config('path.avatar').$remitter->avatar) }}" width="50" height="50">
				</div>
				@endif

				<div class="media-body overflow-hidden">
					<div class="d-flex justify-content-between align-items-center">
						<h6 style="font-size: 15px; color: black; font-weight: 500!important;" class="media-heading mb-2 text-truncate">
							@if ($remitter->status == 'disabled')
								{{ __('general.user_unavailable') }}
							@else
								{{ $msg->remitterName() }}
							@endif

							@if ($remitter->verified_id == 'yes' && $remitter->status == 'active')
								<small class="verified">
									<i class="bi bi-patch-check-fill"></i>
								</small>
							@endif
						</h6>
						<small style="font-size:11px;" class="timeAgo text-truncate mb-2" data="{{ date('c', strtotime($msg->created_at)) }}"></small>
					</div>

					<p style="font-size:13px;" class="text-truncate m-0">
						@if ($msg->totalMsg() != 0)
							<span class="badge badge-pill badge-primary mr-1">{{ $msg->totalMsg() }}</span>
						@endif

						@if ($msg->receiver->id != auth()->id())
							@if ($msg->status == 'readed')
								<span><i class="bi bi-check2-all mr-1"></i></span>
							@else
								<span><i class="bi bi-reply mr-1"></i></span>
							@endif
						@endif

						@if ($msg->media->count() == 1)
							@foreach ($msg->media as $media)
								@switch($media->type)
									@case('image')
										<i class="feather icon-image"></i>
										@if ($msg->message == '') {{ __('general.image') }} @endif
										@break

									@case('video')
										<i class="feather icon-video"></i>
										@if ($msg->message == '') {{ __('general.video') }} @endif
										@break

									@case('music')
										<i class="feather icon-mic"></i>
										@if ($msg->message == '') {{ __('general.music') }} @endif
										@break

									@case('zip')
										<i class="far fa-file-archive"></i>
										@if ($msg->message == '') {{ __('general.zip') }} @endif
										@break

									@case('epub')
										<i class="bi-book mr-1"></i>
										@if ($msg->message == '') EPUB @endif
										@break
								@endswitch
							@endforeach

						@elseif ($msg->media->count() > 1)
							<i class="bi bi-files"></i>
						@endif

						@if ($msg->tip == 'yes')
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin mb-1" viewBox="0 0 16 16"> <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/> <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/> <path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/> </svg>
							{{ __('general.tip') }}
						@endif

						@if ($msg->gift_id)
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-gift mb-1" viewBox="0 0 16 16"> <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A3 3 0 0 1 3 2.506zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43zM9 3h2.932l.023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0zM1 4v2h6V4zm8 0v2h6V4zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5z"/> </svg>
							@if ($msg->message == '') {{ __('general.gift') }} @endif
						@endif

						@if ($msg->price != 0.00
							&& $msg->media->count() == 0
							&& $msg->receiver->id == auth()->id()
							&& ! auth()->user()->checkPayPerViewMsg($msg->id)
						)
							<i class="feather icon-lock mr-1"></i> @lang('users.content_locked')
						@else
							{{ $msg->message }}
						@endif
					</p>
				</div><!-- media-body -->
			</div><!-- media -->
		</a>
	</div><!-- list-group -->
</div><!-- card -->
