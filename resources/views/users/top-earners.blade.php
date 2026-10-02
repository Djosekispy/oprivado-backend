@extends('layouts.app')
@section('title', $title ?? 'Top 10')

@section('content')
    <section class="section section-sm">
        <div class="container pt-4 pt-lg-5">
            <h2 class="mb-0 font-montserrat">
                {{ $title }}
            </h2>

            @if (isset($error_message))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $error_message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
			
            <div class="mb-4 text-left mt-4">
                <form action="{{ route('top.earners') }}" method="GET" class="d-flex flex-wrap gap-2 justify-content-lg-start">
                    @foreach ([
                        'week_current' => 'Esta semana',
                        'month_current' => 'Este mês',
                        'month_previous' => 'Mês passado',
                        'year_current' => 'Este ano',
                        'year_previous' => 'Ano passado',
                        'all_time' => 'Todos os tempos'
                    ] as $key => $label)
                        <button type="submit" name="period" value="{{ $key }}"
								style="margin-left: 4px;margin-bottom: 4px;"
                            class="btn {{ $period === $key ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3">
                            {{ $label }}
                        </button>
                    @endforeach
                </form>
            </div>

            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                @if ($topUsers->isNotEmpty())
                                    @php $position = 1; @endphp
                                    @foreach ($topUsers as $user)
                                        <tr class="cursor-pointer" 
                                            onclick="window.open('{{ url($user->username) }}', '_blank')"
                                            title="Ver perfil de {{ $user->hide_name == 'yes' ? '@'.$user->username : $user->name }}">
                                            <td style=" padding-left: 6px; ">
                                                <div class="d-flex align-items-center text-reset text-decoration-none">
													<span class="verified mr-4" style="font-size:40px;font-weight: 700;">{{ $position }} </span> 
                                                    <img src="{{ Helper::getFile(config('path.avatar').$user->avatar) }}"
                                                         width="64" height="64" class="rounded-circle me-3 mr-2 shadow-sm" alt="">
                                                    <div style="margin-left: 4px;">
                                                        <div style="font-weight: 500;" class="fw-bold text-truncate">
                                                            {{ $user->hide_name == 'yes' ? '@'.$user->username : $user->name }}
                                                            @if ($user->verified_id == 'yes')
                                                                <i class="bi bi-patch-check-fill text-primary ms-1"></i>
                                                            @endif
                                                        </div>
                                                        <small class="text-muted"> {{ '@'.$user->username }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="display:none;"><strong>{{ number_format($user->followers_count ?? 0) }}</strong> assinantes</td>
<td>

    @if(isset($user->growth_rate) && $user->previous_earnings !== null && $user->growth_rate != 0)
    @php
        $arrow = $user->growth_rate > 0 ? '↑' : '↓';

        $colorClass = $user->growth_rate > 0
            ? 'text-success'
            : 'text-danger';
    @endphp

    <span style="font-size: 13px;" class="ms-2 {{ $colorClass }} fw-bold">
        {{ $arrow }} {{ number_format(abs($user->growth_rate), 0) }}%
    </span>
@endif
</td>
                                            <td style="display:none;">
                                                <strong>
                                                    {{ Helper::amountFormatDecimal($user->total_earnings ?? 0) }}</strong> ganhos
                                            </td>
                                        </tr>
                                        @php $position++; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-trophy fs-1 mb-3 d-block opacity-50"></i>
                                            <h5 class="fw-light">Nenhum ganhador encontrado neste período.</h5>
                                            <small>Tente outro filtro ou volte mais tarde.</small>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .table th, .table td { 
            vertical-align: middle; 
        }
        tr.cursor-pointer {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        tr.cursor-pointer:hover {
            background-color: rgba(255, 107, 53, 0.08) !important;
        }
       
        .table-hover tbody tr:last-child {
            border-bottom: none;
        }
		.cora {
    background: #fff !important;
    border-radius: 4px !important;
    border: 1px solid #dee2e6;
}
    </style>
@endsection