@foreach ($users as $user)

<a href="{{url($user->username)}}">
<div class="w-100 mb-2 h-100 d-block" style="background: @if ($user->cover != '') url({{ Helper::getFile(config('path.cover').$user->cover)}})  @endif #505050 center center; border-radius: 6px; background-size: cover;">

	
  <div class="card-cover position-relative" style="height: 30px">
    @if ($user->free_subscription == 'yes')
    <span class="badge-free px-2 py-1 position-absolute rounded">{{ __('general.free') }}</span>
  @else ($user->free_subscription == 'no')
    <span class="badge-free px-2 py-1 position-absolute rounded">{{ __('general.price_per_month', ['price' => Helper::priceWithoutFormat($user->getPlan('monthly', 'price'))]) }}</span>
  @endif
  </div>
	
  <li class="list-group-item mb-2 border-0" style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.8) 0%, transparent 100%);">
         <div class="media">
          <div class="media-left mr-3">
              <img class="media-object rounded-circle avatar-user-home" src="{{Helper::getFile(config('path.avatar').$user->avatar)}}"  width="80" height="80">
          </div>
          <div class="media-body text-truncate">
            <h5 class="media-heading mb-1">
              <a href="{{url($user->username)}}" class="stretched-link text-white">
                <strong>{{$user->hide_name == 'yes' ? $user->username : $user->name}}</strong> <small style=" font-size: 13px; " class="verified mr-1" title="{{__('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
                   <i class="bi bi-patch-check"></i>
                 </small>
              </a>

               @if ($user->featured == 'yes')
              <small class="text-featured" title="{{__('users.creator_featured')}}" data-toggle="tooltip" data-placement="top">
                <i class="fas fa fa-award"></i>
              </small>
              @endif

               <small class=" text-white w-100 d-block text-truncate">{{'@'.$user->username}}</small>
            </h5>
          </div>
      </div>
  </li>
	</div><!-- cover -->
  </a>
  @endforeach
