<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TopUsersController extends Controller
{
    public function index(Request $request)
{
    try {
        $sort   = $request->input('sort', 'followers');
        $search = $request->input('q', '');
        $period = $request->input('period', 'all_time');

        $validPeriods = [
            'all_time', 'week_current', 'month_current', 'year_current',
            'last_30_days', 'last_90_days', 'last_365_days',
            'month_previous', 'year_previous'
        ];

        if (!in_array($period, $validPeriods)) {
            $period = 'all_time';
        }

        $query = User::query()
            ->where('status', 'active')
            ->where('role', '!=', 'admin')
            ->where('verified_id', 'yes')
            ->select([
                'users.*',
                DB::raw('(
                    SELECT COUNT(*)
                    FROM subscriptions
                    WHERE creator_id = users.id
                      AND (ends_at >= NOW() OR stripe_status = "active" OR free = "yes")
                ) as followers_count'),

                DB::raw('(
                    SELECT COALESCE(SUM(earning_net_user), 0)
                    FROM transactions
                    WHERE subscribed = users.id
                      AND approved = 1
                      ' . $this->getEarningsDateCondition($period) . '
                ) as period_earnings'),   

                DB::raw('(
                    SELECT COUNT(*)
                    FROM updates
                    WHERE user_id = users.id
                      AND status = "active"
                ) as posts_count'),
            ]);

        // Busca
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }

        // Ordenação
        switch ($sort) {
            case 'followers':
                $query->orderByDesc('followers_count');
                break;
            case 'earnings':
                $query->orderByDesc('period_earnings');   
                break;
            case 'posts':
                $query->orderByDesc('posts_count');
                break;
            case 'newest':
                $query->orderByDesc('date');
                break;
            default:
                $query->orderByDesc('followers_count');
                break;
        }

        $data = $query->paginate(20);

        // Título amigável para o período
        $periodTitles = [
            'all_time'       => 'Todos os tempos',
            'week_current'   => 'Esta semana',
            'month_current'  => 'Este mês',
            'year_current'   => 'Este ano',
            'last_30_days'   => 'Últimos 30 dias',
            'last_90_days'   => 'Últimos 90 dias',
            'last_365_days'  => 'Últimos 365 dias',
            'month_previous' => 'Mês anterior',
            'year_previous'  => 'Ano anterior',
        ];

        $periodTitle = $periodTitles[$period] ?? 'Todos os tempos';

        return view('admin.top-users', compact(
            'data',
            'sort',
            'search',
            'period',
            'periodTitle'
        ));

    } catch (Exception $e) {
        return view('admin.top-users', [
            'data' => collect([]),
            'sort' => $sort,
            'search' => $search,
            'period' => 'all_time',
            'periodTitle' => 'Todos os tempos',
        ])->with('error_message', 'Erro ao carregar os dados. Tente novamente.');
    }
}

    public function topEarners(Request $request)
    {
        try {
            $period = $request->input('period', 'month_current');
            $validPeriods = [
                'week_current', 'month_current', 'month_previous',
                'year_current', 'year_previous',
                'last_30_days', 'last_90_days', 'last_365_days',
                'all_time'
            ];

            if (!in_array($period, $validPeriods)) {
                $period = 'month_current';
            }

            $titleMap = [
                'week_current'     => 'Top 10 desta semana',
                'month_current'    => 'Top 10 deste mês',
                'month_previous'   => 'Top 10 do mês passado',
                'year_current'     => 'Top 10 deste ano',
                'year_previous'    => 'Top 10 do ano passado',
                'last_30_days'     => 'Top 10 Ganhadores – Últimos 30 Dias',
                'last_90_days'     => 'Top 10 Ganhadores – Últimos 90 Dias',
                'last_365_days'    => 'Top 10 Ganhadores – Últimos 365 Dias',
                'all_time'         => 'Top 10 de todos os tempos',
            ];

            $title = $titleMap[$period] ?? 'Top 10 (Elite)';

            $query = User::query()
                ->where('status', 'active')
                ->where('role', '!=', 'admin')
                ->where('verified_id', 'yes')
                ->select([
                    'users.id',
                    'users.username',
                    'users.name',
                    'users.avatar',
                    'users.hide_name',
                    'users.verified_id',
                    'users.date',
                    'users.last_seen',
                    DB::raw('
                        (SELECT COUNT(*)
                         FROM subscriptions
                         WHERE creator_id = users.id
                           AND (ends_at >= NOW() OR stripe_status = "active" OR free = "yes")
                        ) as followers_count
                    '),
                    DB::raw('
                        (SELECT COALESCE(SUM(earning_net_user), 0)
                         FROM transactions
                         WHERE subscribed = users.id
                           AND approved = 1
                           ' . $this->getEarningsDateCondition($period) . '
                        ) as total_earnings
                    '),
                    DB::raw('
                        (SELECT COALESCE(SUM(earning_net_user), 0)
                         FROM transactions
                         WHERE subscribed = users.id
                           AND approved = 1
                           ' . $this->getEarningsDateCondition($this->getPreviousPeriod($period)) . '
                        ) as previous_earnings
                    '),
                    DB::raw('
                        (SELECT COUNT(*)
                         FROM updates
                         WHERE user_id = users.id
                           AND status = "active"
                        ) as posts_count
                    '),
                ]);

            $query->havingRaw('COALESCE(total_earnings, 0) > 0');
            $query->orderByDesc('total_earnings');

            $topUsers = $query->limit(10)->get();

            // Calcular taxa de crescimento no PHP
            foreach ($topUsers as $user) {
                $current  = (float) $user->total_earnings;
                $previous = (float) $user->previous_earnings;

                if ($previous > 0) {
                    $growth = (($current - $previous) / $previous) * 100;
                } elseif ($current > 0) {
                    $growth = 100; 
                } else {
                    $growth = 0;
                }

                $user->growth_rate = $growth;
                $user->growth_class = $growth > 0 ? 'success' : ($growth < 0 ? 'danger' : 'secondary');
                $user->growth_sign  = $growth > 0 ? '+' : ($growth < 0 ? '' : '');
            }

            return view('users.top-earners', compact(
                'topUsers',
                'period',
                'title'
            ));
        } catch (Exception $e) {
            return view('users.top-earners', [
                'topUsers' => collect([]),
                'period'   => $period ?? 'month_current',
                'title'    => 'Top 10 Ganhadores',
                'error_message' => 'Não foi possível carregar o ranking no momento.',
            ]);
        }
    }

    /**
     * Retorna o nome do período anterior correspondente
     */
    private function getPreviousPeriod(string $currentPeriod): string
    {
        $map = [
            'week_current'   => 'week_previous',
            'month_current'  => 'month_previous',
            'last_30_days'   => 'previous_30_days',
            'last_90_days'   => 'previous_90_days',
            'last_365_days'  => 'previous_365_days',
            // para períodos sem anterior claro, retornamos o mesmo → growth_rate = 0 ou não mostra
            'month_previous' => 'month_previous',
            'year_current'   => 'year_previous',
            'year_previous'  => 'year_previous',
            'all_time'       => 'all_time',
        ];

        return $map[$currentPeriod] ?? $currentPeriod;
    }

    /**
     * Condição de data para a subquery de earnings
     */
    private function getEarningsDateCondition(string $period): string
    {
        $now = Carbon::now();

        return match ($period) {
            'week_current' => 'AND created_at >= "' . $now->startOfWeek()->toDateTimeString() . '"',

            'week_previous' => 'AND created_at >= "' . $now->subWeek()->startOfWeek()->toDateTimeString() . '"
                                AND created_at < "'  . $now->startOfWeek()->toDateTimeString() . '"',

            'month_current' => 'AND created_at >= "' . $now->startOfMonth()->toDateTimeString() . '"',

            'month_previous' => 'AND created_at >= "' . $now->subMonthNoOverflow()->startOfMonth()->toDateTimeString() . '"
                                 AND created_at < "'  . $now->addMonthNoOverflow()->startOfMonth()->toDateTimeString() . '"',

            'year_current' => 'AND created_at >= "' . $now->startOfYear()->toDateTimeString() . '"',

            'year_previous' => 'AND created_at >= "' . $now->subYearNoOverflow()->startOfYear()->toDateTimeString() . '"
                                AND created_at < "'  . $now->addYearNoOverflow()->startOfYear()->toDateTimeString() . '"',

            'last_30_days' => 'AND created_at >= "' . $now->subDays(30)->toDateTimeString() . '"',

            'previous_30_days' => 'AND created_at >= "' . $now->subDays(60)->toDateTimeString() . '"
                                   AND created_at < "'  . $now->subDays(30)->toDateTimeString() . '"',

            'last_90_days' => 'AND created_at >= "' . $now->subDays(90)->toDateTimeString() . '"',

            'previous_90_days' => 'AND created_at >= "' . $now->subDays(180)->toDateTimeString() . '"
                                   AND created_at < "'  . $now->subDays(90)->toDateTimeString() . '"',

            'last_365_days' => 'AND created_at >= "' . $now->subDays(365)->toDateTimeString() . '"',

            'previous_365_days' => 'AND created_at >= "' . $now->subDays(730)->toDateTimeString() . '"
                                    AND created_at < "'  . $now->subDays(365)->toDateTimeString() . '"',

            'all_time' => '',

            default => '',
        };
    }
	
	public function topLive(Request $request)
{
    try {

        $period = $request->get('period', 'month_current');
    
        $validPeriods = [
            'week_current',
            'month_current',
            'year_current',
            'all_time'
        ];

        if (!in_array($period, $validPeriods)) {
            $period = 'month_current';
        }

        $title = 'Top 10 Criadores em Live';

        $topUsers = Cache::remember("top_live_{$period}", 60, function () use ($period) {
            $query = User::query()
                ->where('users.status', 'active')
                ->where('users.role', '!=', 'admin')
                ->where('users.verified_id', 'yes')

                ->join('live_streamings', 'live_streamings.user_id', '=', 'users.id')

                ->select([
                    'users.id',
                    'users.username',
                    'users.name',
                    'users.avatar',
                    'users.hide_name',
                    'users.verified_id',

                    DB::raw('COUNT(live_streamings.id) as total_lives'),

                    DB::raw('COALESCE(SUM(
                        TIMESTAMPDIFF(
                            MINUTE,
                            live_streamings.created_at,
                            COALESCE(live_streamings.ends_at, NOW())
                        )
                    ),0) as total_live_minutes'),
                ])

                ->groupBy(
                    'users.id',
                    'users.username',
                    'users.name',
                    'users.avatar',
                    'users.hide_name',
                    'users.verified_id'
                );

            // FILTRO DE PERÍODO
            if ($period === 'week_current') {
                $query->where('live_streamings.created_at', '>=', now()->startOfWeek());
            }

            if ($period === 'month_current') {
                $query->where('live_streamings.created_at', '>=', now()->startOfMonth());
            }

            if ($period === 'year_current') {
                $query->where('live_streamings.created_at', '>=', now()->startOfYear());
            }

            $query->orderByDesc('total_live_minutes')
                  ->orderByDesc('total_lives');

            return $query->limit(10)->get();
        });

        return view('users.top-lives', compact(
            'topUsers',
            'period',
            'title'
        ));

    } catch (Exception $e) {

        Log::error('TopLive error: ' . $e->getMessage());

        return view('users.top-lives', [
            'topUsers' => collect([]),
            'period' => $period ?? 'month_current',
            'title' => 'Top 10 Lives',
            'error_message' => 'Erro ao carregar ranking de lives.'
        ]);
    }
}

	public function topFeatured(Request $request)
{
    try {
        $title = 'Em Destaque';

        $topUsers = User::query()
            ->where('users.status', 'active')
            ->where('users.featured', 'yes')
			 ->where('users.role','!=', 'admin')
            ->whereNotNull('users.featured_date')
            ->select([
                'users.id',
                'users.username',
                'users.name',
                'users.avatar',
                'users.hide_name',
                'users.verified_id',
                'users.featured_date',
                'users.cover',           
            ])
          ->inRandomOrder()
   		 ->get();

        return view('users.top-featured', compact(
            'topUsers',
            'title'
        ));

    } catch (Exception $e) {
        Log::error('TopFeatured error: ' . $e->getMessage());
       
        return view('users.top-featured', [
            'topUsers' => collect([]),
            'title' => 'Perfis em Destaque',
            'error_message' => 'Erro ao carregar perfis em destaque.'
        ]);
    }
}
	
}