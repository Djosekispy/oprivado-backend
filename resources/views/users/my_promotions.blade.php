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
                    <span class="badge badge-warning px-3 py-2">2.000 Kz por dia</span>
                </div>

                @if (session('promotion_success'))
                    <div class="alert alert-success">{{ session('promotion_success') }}</div>
                @endif

                @if (session('promotion_error'))
                    <div class="alert alert-danger">{{ session('promotion_error') }}</div>
                @endif

                @if ($campaigns->count() > 0)
                    <div class="card shadow-sm mb-3">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Publicação</th>
                                        <th>Duração</th>
                                        <th>Estado</th>
                                        <th>Restantes</th>
                                        <th>Total</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaigns as $campaign)
                                        <tr>
                                            <td>
                                                @if ($campaign->post)
                                                    <a href="{{ url($campaign->post->user->username . '/post/' . $campaign->post->id) }}" target="_blank">
                                                        {{ Str::limit(strip_tags($campaign->post->description), 40) }}
                                                    </a>
                                                @else
                                                    Publicação removida
                                                @endif
                                            </td>
                                            <td>{{ $campaign->days }} dias</td>
                                            <td>
                                                @if ($campaign->status == 'active')
                                                    <span class="badge badge-success">Ativa</span>
                                                @elseif ($campaign->status == 'removed')
                                                    <span class="badge badge-secondary">Removida</span>
                                                @else
                                                    <span class="badge badge-light">{{ ucfirst($campaign->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($campaign->status == 'active')
                                                    {{ max(0, $campaign->remaining_days) }} dias
                                                    <small class="d-block text-muted">{{ Helper::formatDate($campaign->ends_at) }}</small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ Helper::amountFormatDecimal($campaign->total_amount) }}</td>
                                            <td>
                                                @if ($campaign->status == 'active')
                                                    {!! Form::open(['method' => 'POST', 'url' => route('post.promote.remove', $campaign->id), 'class' => 'd-inline']) !!}
                                                        {!! Form::button('Remover', ['class' => 'btn btn-link text-danger p-0', 'onclick' => 'return confirm("Tem a certeza que deseja remover esta promoção?")']) !!}
                                                    {!! Form::close() !!}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

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
