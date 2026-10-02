@extends('admin.layout')
@section('content')

<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
    <i class="bi-chevron-right me-1 fs-6"></i>
    <span class="text-muted">{{ __('admin.top_users') }} ({{ $data->total() }})</span>
</h5>

@if (isset($error_message))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $error_message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="content">
    <div class="row">
        <div class="col-12">

            @if (session('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check2 me-1"></i> {{ session('success_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card shadow-custom border-0">
                <div class="card-body p-3 p-lg-4">

                    @if ($data->count() > 0 || $search)
                    <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3 flex-wrap">

                        <!-- Filtro de período -->
                        <form action="{{ url('panel/admin/top-users') }}" method="get" class="d-flex flex-wrap gap-2">
                            @foreach ([
                                'all_time'       => 'Todos os tempos',
                                'week_current'   => 'Esta semana',
                                'month_current'  => 'Este mês',
                                'year_current'   => 'Este ano',
                                'last_30_days'   => 'Últ. 30 dias',
                                'last_90_days'   => 'Últ. 90 dias',
                                'month_previous' => 'Mês ant.',
                                'year_previous'  => 'Ano ant.',
                            ] as $key => $label)
                                <button type="submit" name="period" value="{{ $key }}"
                                    class="btn {{ $period === $key ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3">
                                    {{ $label }}
                                </button>
                            @endforeach
                            <!-- Manter os outros parâmetros -->
                            <input type="hidden" name="sort" value="{{ $sort }}">
                            <input type="hidden" name="q" value="{{ $search }}">
                        </form>

                        <!-- Ordenação + Busca -->
                        <div class="d-flex flex-column flex-md-row gap-3 w-100 w-lg-auto">
                            <form action="{{ url('panel/admin/top-users') }}" method="get" class="w-100 w-lg-auto">
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option @if ($sort == 'followers') selected @endif value="followers">Mais Seguidores</option>
                                    <option @if ($sort == 'earnings') selected @endif value="earnings">Maiores Ganhos</option>
                                    <option @if ($sort == 'posts') selected @endif value="posts">Mais Postagens</option>
                                    <option @if ($sort == 'newest') selected @endif value="newest">Mais Recentes</option>
                                </select>
                                <input type="hidden" name="period" value="{{ $period }}">
                                <input type="hidden" name="q" value="{{ $search }}">
                            </form>

                            <form class="position-relative w-100 w-lg-50" role="search" action="{{ url('panel/admin/top-users') }}" method="get">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y text-muted ps-3"></i>
                                <input type="text" name="q" class="form-control ps-5"
                                       value="{{ $search }}" placeholder="Buscar por nome ou @usuário">
                                <input type="hidden" name="period" value="{{ $period }}">
                                <input type="hidden" name="sort" value="{{ $sort }}">
                            </form>
                        </div>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Posição</th>
                                    <th>Criador</th>
                                    <th>Ganhos {{ $periodTitle }}</th> <!-- ← alterado -->
									 <th>Seguidores</th>
                                    <th>Postagens</th>
                                    <th class="d-none d-md-table-cell">Data Cadastro</th>
                                    <th class="d-none d-md-table-cell">Último Acesso</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->count() > 0)
                                    @php $position = $data->firstItem(); @endphp
                                    @foreach ($data as $user)
                                    <tr>
                                        <td class="fw-bold">
                                            {{ $position }}
                                            @if ($position <= 3)
                                                <i class="bi bi-trophy-fill text-warning ms-1"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- conteúdo igual -->
                                            <a href="{{ url($user->username) }}" target="_blank" class="d-flex align-items-center text-reset text-decoration-none">
                                                <img src="{{ Helper::getFile(config('path.avatar').$user->avatar) }}"
                                                     width="44" height="44" class="rounded-circle me-3 flex-shrink-0" alt="">
                                                <div class="overflow-hidden">
                                                    <div class="fw-bold text-truncate">
                                                        {{ $user->hide_name == 'yes' ? '@'.$user->username : $user->name }}
                                                        @if ($user->verified_id == 'yes')
                                                            <i style="color:#ff8035!important;" class="bi bi-patch-check-fill text-primary ms-1 small" title="Verificado"></i>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted d-block text-truncate">{{ '@'.$user->username }}</small>
                                                </div>
                                            </a>
                                        </td>
                                        
                                        <td><strong>{{ Helper::amountFormatDecimal($user->period_earnings ?? 0) }}</strong></td>
										<td><strong>{{ number_format($user->followers_count ?? 0) }}</strong></td>
                                        <td>{{ number_format($user->posts_count ?? 0) }}</td>
                                        <td class="d-none d-md-table-cell">{{ Helper::formatDate($user->date) }}</td>
                                        <td class="d-none d-md-table-cell">{{ $user->last_seen ? Helper::formatDate($user->last_seen) : '—' }}</td>
                                        <td>
                                            <span class="badge bg-success rounded-pill px-3 py-2">Ativo</span>
                                        </td>
                                        <td>
                                            <a href="{{ url($user->username) }}" target="_blank"
                                               class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center">
                                                <i class="bi bi-eye me-1"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                    @php $position++; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="bi bi-search fs-1 mb-3 d-block text-secondary"></i>
                                            <h5 class="fw-light mb-2">Nenhum resultado encontrado</h5>
                                            @if ($search || $period !== 'all_time')
                                            <a href="{{ url('panel/admin/top-users') }}" class="btn btn-sm btn-outline-secondary mt-2">
                                                <i class="bi-arrow-left me-1"></i> Limpar filtros
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($data->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $data->appends(['q' => $search, 'sort' => $sort, 'period' => $period])->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
</div>

@endsection