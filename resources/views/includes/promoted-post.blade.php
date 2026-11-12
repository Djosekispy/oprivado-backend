@php
    $post = $promotion->post;
    $creator = $post->creator;
    $media = $post->media->first();
    $thumbnail = null;

    if ($media && $media->type === 'image' && $media->image) {
        $thumbnail = Helper::getFile(config('path.images') . $media->image);
    } elseif ($media && $media->type === 'video' && $media->video_poster) {
        $thumbnail = Helper::getFile(config('path.videos') . $media->video_poster);
    }

    $clickUrl = route('promoted-post.click', $promotion->id);
    $creatorName = $creator->hide_name == 'yes' ? $creator->username : $creator->name;
@endphp

<a
    href="{{ $clickUrl }}"
    data-impression-url="{{ route('promoted-post.impression', $promotion->id) }}"
    data-update-id="{{ $post->id }}"
    class="card rounded-large border shadow-sm mb-3 p-3 text-reset text-decoration-none promoted-post js-promoted-post"
>
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <img
                class="img-fluid rounded"
                src="{{ $thumbnail ?: Helper::getFile(config('path.avatar') . $creator->avatar) }}"
                alt=""
                style="width: 130px; height: 86px; object-fit: cover;"
            >
        </div>
        <div class="flex-grow-1 ml-3">
            <small class="d-block text-muted mb-1">
                <i class="bi bi-megaphone mr-1"></i> Patrocinado
            </small>
            <h5 class="mb-1" style="font-size: 14px;">{{ $creatorName }}</h5>
            <p class="mb-2">{{ Str::limit(strip_tags($post->description), 90) }}</p>
            <span class="btn btn-primary btn-sm">Ver publicação</span>
        </div>
    </div>
</a>
