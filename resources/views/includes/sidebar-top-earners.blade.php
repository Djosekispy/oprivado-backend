<div class="largo">
    <h6 class="mb-3 mt-4 text-muted font-weight-light filter-explorer">
        <i class="verified mr-2 feather icon-trending-up"></i>
        Top criadores do mês
        <a href="https://oprivado.ao/top" class="float-right text-decoration-none mr-2">
            <i class="feather icon-arrow-right"></i>
        </a>
    </h6>

    <div class="card border-0 mb-4 rounded-4 bg-white">
        <div class="card-body p-0">
            @php
                $topEarners = Cache::remember('top_earners_home_this_month_with_growth', now()->addMinutes(60), function () {
                    $startOfCurrentMonth = \Carbon\Carbon::now()->startOfMonth()->toDateTimeString();
                    $startOfPreviousMonth = \Carbon\Carbon::now()->subMonthNoOverflow()->startOfMonth()->toDateTimeString();
                    $endOfPreviousMonth = \Carbon\Carbon::now()->subMonthNoOverflow()->endOfMonth()->toDateTimeString();

                    return \App\Models\User::query()
                        ->where('status', 'active')
                        ->where('role', '!=', 'admin')
                        ->where('verified_id', 'yes')
                        ->select([
                            'id',
                            'username',
                            'name',
                            'avatar',
                            'hide_name',
                            'verified_id',

                            // Ganhos do mês atual (do dia 1 até agora)
                            \DB::raw('(SELECT COALESCE(SUM(earning_net_user), 0)
                                       FROM transactions
                                       WHERE subscribed = users.id
                                         AND approved = 1
                                         AND created_at >= "' . $startOfCurrentMonth . '"
                                      ) as current_earnings'),

                            // Ganhos do mês anterior completo
                            \DB::raw('(SELECT COALESCE(SUM(earning_net_user), 0)
                                       FROM transactions
                                       WHERE subscribed = users.id
                                         AND approved = 1
                                         AND created_at >= "' . $startOfPreviousMonth . '"
                                         AND created_at <= "' . $endOfPreviousMonth . '"
                                      ) as previous_earnings'),
                        ])
                        ->havingRaw('current_earnings > 0')
                        ->orderByDesc('current_earnings')
                        ->limit(3)
                        ->get();
                });

                // Calcula a taxa de crescimento
                foreach ($topEarners as $user) {
                    $current  = (float) ($user->current_earnings ?? 0);
                    $previous = (float) ($user->previous_earnings ?? 0);

                    if ($previous > 0) {
                        $growth = (($current - $previous) / $previous) * 100;
                    } elseif ($current > 0) {
                        $growth = 100; // subiu de zero
                    } else {
                        $growth = 0;
                    }

                    $user->growth_rate = $growth;
                    $user->growth_sign = $growth > 0 ? '+' : ($growth < 0 ? '' : '');
                    $user->growth_class = $growth > 0 ? 'success' : ($growth < 0 ? 'danger' : 'secondary');
                }
            @endphp

            @if ($topEarners->isNotEmpty())
                @foreach ($topEarners as $index => $user)
                    <a href="{{ url($user->username) }}" class="text-decoration-none mb-2 text-reset d-block">
                        <div class="cora d-flex align-items-center p-3 px-lg-4 gap-4">
                            <div class="d-flex align-items-center mr-2">
                                <span class="badge redo verified d-flex align-items-center justify-content-center fw-bold shadow fs-6"
                                      style="font-size:40px; font-weight: 700; min-width:60px; height:60px; line-height:60px;">
                                    {{ $index + 1 }}
                                </span>
                                <img src="{{ Helper::getFile(config('path.avatar').$user->avatar) }}"
                                     width="64" height="64"
                                     class="rounded-circle shadow object-fit-cover ms-3"
                                     alt="{{ $user->name ?? $user->username }}">
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center mb-0">
                                    <h6 style=" font-size: 14px;font-weight: 500;" class="mb-0 fw-bold text-truncate fs-15">
                                        {{ $user->hide_name == 'yes' ? '@'.$user->username : $user->name }}
                                    </h6>
                                    @if ($user->verified_id == 'yes')
                                        <i style=" font-size: 13px; " class="bi bi-patch-check-fill text-primary ml-1 fs-6" title="Verificado"></i>
                                    @endif
                                </div>

                                <small class="text-muted d-block text-truncate mb-0">
                                    {{ '@'.$user->username }}
                                </small>
                              
                                <div class="mt-0">
                                    <span class="badge bg-{{ $user->growth_class }}-subtle text-{{ $user->growth_class }} fw-semibold">
                                        @if ($user->growth_rate > 0)
                                            <i class="bi bi-arrow-up-short fs-5"></i>
                                        @elseif ($user->growth_rate < 0)
                                            <i class="bi bi-arrow-down-short fs-5"></i>
                                        @else
                                            <i class="bi bi-dash fs-5"></i>
                                        @endif
                                        <span>{{ $user->growth_sign }}{{ number_format(abs($user->growth_rate), 0) }}%</span>
                                    </span>
                                </div>
                            </div>

                            <i class="bi bi-chevron-right text-muted ms-3 fs-5 opacity-75"></i>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-trophy fs-1 mb-3 d-block opacity-50"></i>
                    <p class="mb-1 fw-medium">Ainda sem pódio este mês</p>
                    <small class="d-block">Os primeiros criadores aparecerão aqui</small>
                </div>
            @endif
        </div>
    </div>
</div>