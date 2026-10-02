@php

  $topFeatured = \App\Models\User::query()
        ->where('status', 'active')
        ->where('featured', 'yes')
        ->where('role', '!=', 'admin')
        ->whereNotNull('featured_date')
        ->select([
            'id',
            'username',
            'name',
            'avatar',
            'hide_name',
            'verified_id',
            'featured_date',
        ])
        ->get()
        ->shuffle()
        ->take(3);
@endphp

@if ($topFeatured->isNotEmpty())
<div class="largo">
    <h6 class="mb-3 mt-4 text-muted font-weight-light filter-explorer">
        <i class="verified mr-2 feather icon-award"></i>
        Criadores em destaque
        <a href="{{ url('creator/top/featured') }}" class="float-right text-decoration-none mr-2">
            <i class="feather icon-arrow-right"></i>
        </a>
    </h6>
    <div class="card border-0 mb-4 rounded-4 bg-white">
        <div class="card-body p-0">
            @foreach ($topFeatured as $index => $user)
                <a href="{{ url($user->username) }}"
                   class="text-decoration-none mb-2 text-reset d-block">
                    <div class="cora d-flex align-items-center p-3 px-lg-4 gap-4">
                        <div class="d-flex align-items-center mr-2 position-relative">
                           
                            <img src="{{ Helper::getFile(config('path.avatar').$user->avatar) }}"
                                 width="64" height="64"
                                 class="rounded-circle border-3 shadow object-fit-cover ms-3"
                                 alt="{{ $user->username }}">
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex align-items-center mb-0">
                                <h6 style="font-size: 14px;font-weight: 500;"
                                    class="mb-0 fw-bold text-truncate fs-15">
                                    {{ $user->hide_name == 'yes'
                                        ? '@'.$user->username
                                        : $user->name }}
                                </h6>
                                @if ($user->verified_id == 'yes')
                                    <i style="font-size: 13px;"
                                       class="feather icon-award text-primary ml-1 fs-6"></i>
                                @endif
                            </div>
                            <small class="text-muted d-block text-truncate mb-0">
                                {{ '@'.$user->username }}
                            </small>
                        </div>
                        <i class="bi bi-chevron-right text-muted ms-3 fs-5 opacity-75"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif