@if (!isset($inPostDetail) && !isset($isCreated))
    @if ($advertising)
        @foreach ($advertising as $ad)
        <div class="card rounded-large border shadow-sm mb-3 position-relative p-3 advertising">

            <div class="d-flex">
                <div class="flex-shrink-0">
                    <img style=" object-fit: cover; " class="img-fluid rounded img-thanks-share" height="130" width="130"
                        src="{{ Helper::getFile(config('path.ads').$ad->image) }}">
                </div>
                <div class="flex-grow-1 ml-3">
					 <small class="d-block w-100 text-muted mb-1"><i class="feather icon-refresh-cw mr-1"></i> Recomendado</small>
                    <h5 style="font-size:14px;" class="mb-1">{{ $ad->title }}</h5>
                   <p style="margin-bottom:12px;">{{ $ad->description }}</p>
					<a href="{{ url('click/ad', $ad->id) }}" class="btn btn-primary btn-sm mt-0">
                  Subscrever
                </a>
                </div>
            </div>

            <a href="{{ url('click/ad', $ad->id) }}" class="stretched-link"></a>
        </div>

        @php $ad->impressions(); @endphp
        @endforeach

    @endif
@endif