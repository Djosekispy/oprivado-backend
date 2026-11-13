@extends('layouts.app')

@section('title') {{ __('general.promotions') }} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
        <div class="row">
            @include('includes.cards-settings')

            <div class="col-lg-8 py-5">
				
				<div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0 font-montserrat">Promoções</h2>
                </div>

                @if (session('promotion_success'))
                    <div class="alert alert-success">{{ session('promotion_success') }}</div>
                @endif

                @if (session('promotion_error'))
                    <div class="alert alert-danger">{{ session('promotion_error') }}</div>
                @endif

                @if ($campaigns->count() > 0)
                    <div class="border mb-3">
                        <div class="col-sm-4 none mb-2">
                            <div class="card shadow-sm h-100"><div class="card-body">
                                <small class="text-muted d-block">Impressões</small>
                                <strong class="h4 mb-0">{{ number_format($totals->impressions) }}</strong>
                            </div></div>
                        </div>
                        <div class="col-sm-4 none mb-2">
                            <div class="card shadow-sm h-100"><div class="card-body">
                                <small class="text-muted d-block">Cliques</small>
                                <strong class="h4 mb-0">{{ number_format($totals->clicks) }}</strong>
                            </div></div>
                        </div>
                        <div class="col-sm-4 mb-2">
                            <div class="card shadow-sm h-100"><div class="card-body">
                                <small class="text-muted d-block mb-1">Montante total gasto</small>
                                <strong class="h4 mb-0">{{ Helper::amountFormatDecimal($totals->total_amount) }}</strong>
                            </div></div>
                        </div>
                    </div>

                    @foreach ($campaigns as $campaign)
                        <div style="border: 1px solid #dbdbdb !important;" class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                                    <div class="mr-3 mb-2">
                                        @if ($campaign->post && $campaign->post->creator)
                                            Publicar: <a style=" color: #2b2b2b!important; " href="{{ url($campaign->post->creator->username . '/post/' . $campaign->post->id) }}" target="_blank">"{{ Str::limit(strip_tags($campaign->post->description), 100) }}"
                                            </a>
                                        @else
                                            Publicação removida
                                        @endif
                                        <small class="d-block mt-2 text-muted">{{ $campaign->days }} dias · {{ Helper::amountFormatDecimal($campaign->total_amount) }} · @if ($campaign->status === 'active')
                                        <span class="badge badge-light" style="color: #28a745;margin-left: 2px;">Ativa</span>
                                    @elseif ($campaign->status === 'removed')
                                        <span class="badge badge-light" style="color: red;margin-left: 2px;">Removida</span>
                                    @else
                                        <span style="color: red;margin-left: 2px;" class="badge badge-light">Expirada</span>
                                    @endif </small>
                                    </div>
                                    
                                </div>

                                <div class="row text-center">
                                    <div class="col-6 col-md-3 mb-2">
                                        <small class="d-block text-muted">Impressões</small>
                                        <strong>{{ number_format($campaign->impressions) }}</strong>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <small class="d-block text-muted">Cliques</small>
                                        <strong>{{ number_format($campaign->clicks) }}</strong>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <small class="d-block text-muted">CTR</small>
                                        <strong>{{ number_format($campaign->ctr, 2, ',', '.') }}%</strong>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <small class="d-block text-muted">Dias restantes</small>
                                        <strong>{{ $campaign->remaining_days }}</strong>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap border-top pt-2 mt-2">
                                    <small class="text-muted">
                                        {{ optional($campaign->starts_at)->format('d/m/Y H:i') ?: '—' }}
                                        –
                                        {{ optional($campaign->ends_at)->format('d/m/Y H:i') ?: '—' }}
                                    </small>
                                    @if ($campaign->status === 'active')
                                        {!! Form::open(['method' => 'POST', 'url' => route('post.promote.remove', $campaign->id), 'class' => 'd-inline']) !!}
                                            {!! Form::submit('Remover promoção', ['class' => 'btn btn-link text-danger p-0', 'onclick' => 'return confirm("Tem a certeza que deseja remover esta promoção?")']) !!}
                                        {!! Form::close() !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($campaigns->hasPages())
                        {{ $campaigns->links() }}
                    @endif
                @else
                    <div class="card shadow-sm p-5 text-center text-muted">
                        Ainda não há campanhas de promoção neste perfil.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
