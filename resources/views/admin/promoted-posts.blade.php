@extends('admin.layout')

@section('content')
<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
    <i class="bi-chevron-right me-1 fs-6"></i>
    <span class="text-muted">Promoções de publicações</span>
</h5>

<div class="content">
    @if (session('success_message'))
        <div class="alert alert-success">{{ session('success_message') }}</div>
    @endif

    <div class="card shadow-custom border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.promoted-posts') }}" class="row align-items-end">
                <div class="col-md-4 mb-2">
                    <label for="promotionSearch">Criador ou publicação</label>
                    <input id="promotionSearch" type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Pesquisar">
                </div>
                <div class="col-md-2 mb-2">
                    <label for="promotionStatus">Estado</label>
                    <select id="promotionStatus" name="status" class="form-control">
                        <option value="all" @if ($status === 'all') selected @endif>Todos</option>
                        <option value="active" @if ($status === 'active') selected @endif>Ativas</option>
                        <option value="expired" @if ($status === 'expired') selected @endif>Expiradas</option>
                        <option value="removed" @if ($status === 'removed') selected @endif>Removidas</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="promotionFrom">Início a partir de</label>
                    <input id="promotionFrom" type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label for="promotionTo">Início até</label>
                    <input id="promotionTo" type="date" name="to" value="{{ $to }}" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('admin.promoted-posts') }}" class="btn btn-light">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-custom border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Criador / publicação</th>
                            <th>Impressões</th>
                            <th>Cliques</th>
                            <th>CTR</th>
                            <th>Período</th>
                            <th>Valor pago</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($campaigns as $campaign)
                            <tr>
                                <td>
                                    @if ($campaign->user)
                                        <strong>{{ $campaign->user->name }}</strong>
                                        <small class="d-block text-muted">{{ '@' . $campaign->user->username }}</small>
                                    @endif
                                    @if ($campaign->post)
                                        <small class="d-block">{{ Str::limit(strip_tags($campaign->post->description), 70) }}</small>
                                    @else
                                        <small class="d-block text-muted">Publicação removida</small>
                                    @endif
                                </td>
                                <td>{{ number_format($campaign->impressions) }}</td>
                                <td>{{ number_format($campaign->clicks) }}</td>
                                <td>{{ number_format($campaign->ctr, 2, ',', '.') }}%</td>
                                <td>
                                    <small class="d-block">Início: {{ optional($campaign->starts_at)->format('d/m/Y H:i') ?: '—' }}</small>
                                    <small class="d-block">Fim: {{ optional($campaign->ends_at)->format('d/m/Y H:i') ?: '—' }}</small>
                                </td>
                                <td>{{ Helper::amountFormatDecimal($campaign->total_amount) }}</td>
                                <td>
                                    @if ($campaign->status === 'active')
                                        <span class="badge bg-success">Ativa</span>
                                    @elseif ($campaign->status === 'removed')
                                        <span class="badge bg-secondary">Removida</span>
                                    @else
                                        <span class="badge bg-light text-dark">Expirada</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($campaign->status === 'active')
                                        {!! Form::open(['method' => 'POST', 'url' => route('admin.promoted-posts.remove', $campaign->id)]) !!}
                                            {!! Form::submit('Remover', ['class' => 'btn btn-sm btn-outline-danger', 'onclick' => 'return confirm("Tem a certeza que deseja remover esta promoção?")']) !!}
                                        {!! Form::close() !!}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted p-4">Nenhuma promoção encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($campaigns->hasPages())
        <div class="mt-3">{{ $campaigns->links() }}</div>
    @endif
</div>
@endsection
