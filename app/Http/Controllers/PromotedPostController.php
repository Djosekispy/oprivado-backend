<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\AdClickImpression;
use App\Models\PromotedPost;
use App\Models\Updates;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PromotedPostController extends Controller
{
    public const DAILY_PRICE = 2000.00;

public function randomComponent(): Response
{
    $promotion = PromotedPost::query()
        ->where('status', 'active')
        ->where('starts_at', '<=', now())
        ->where('ends_at', '>', now())
        ->whereHas('post', fn (Builder $q) => $q->where('status', 'active'))
		->whereHas('post', function (Builder $q) {
			$q->where('status', 'active')
			  ->whereHas('creator', fn (Builder $c) => $c->where('status', 'active'));
		})
		->with('post.creator')
        ->with('post')
        ->inRandomOrder()
        ->first();

    if (! $promotion || ! $promotion->post) {
        return response('', 204);
    }

    return response()->view('includes.updates', [
        'updates' => collect([$promotion->post]),
    ]);
}

    public function trackImpression(PromotedPost $promotion): Response
    {
        if (
            $promotion->status === 'active'
            && $promotion->starts_at
            && $promotion->starts_at->isPast()
            && $promotion->ends_at
            && $promotion->ends_at->isFuture()
        ) {
            $promotion->recordImpression();
        }

        return response('', 204);
    }

    public function index(): View
    {
        $this->expireCampaigns();

        $campaigns = PromotedPost::where('user_id', auth()->id())
            ->with(['post.creator'])
            ->orderByDesc('created_at')
            ->paginate(15);
        $totals = PromotedPost::where('user_id', auth()->id())
            ->selectRaw('COALESCE(SUM(impressions), 0) as impressions, COALESCE(SUM(clicks), 0) as clicks, COALESCE(SUM(total_amount), 0) as total_amount')
            ->first();

        return view('users.my_promotions', [
            'campaigns' => $campaigns,
            'totals' => $totals,
            'dailyPrice' => self::DAILY_PRICE,
        ]);
    }

    public function store(Request $request, Updates $post): RedirectResponse
    {
        $this->validate($request, [
            'days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        if ((int) $post->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $days = (int) $request->days;
        $totalAmount = $days * self::DAILY_PRICE;
        $failure = DB::transaction(function () use ($post, $days, $totalAmount) {
            $user = User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();
            $lockedPost = Updates::whereKey($post->id)->lockForUpdate()->firstOrFail();

            if ((int) $lockedPost->user_id !== (int) $user->id) {
                abort(403);
            }

            if ($lockedPost->status !== 'active') {
                return 'post_not_active';
            }

            $activePromotionExists = PromotedPost::where('update_id', $lockedPost->id)
                ->where('status', 'active')
                ->where('ends_at', '>', now())
                ->lockForUpdate()
                ->exists();

            if ($activePromotionExists) {
                return 'already_active';
            }

            if ($user->wallet < $totalAmount) {
                return 'insufficient_funds';
            }

            $user->decrement('wallet', $totalAmount);

            PromotedPost::create([
                'user_id' => $user->id,
                'update_id' => $lockedPost->id,
                'days' => $days,
                'daily_price' => self::DAILY_PRICE,
                'total_amount' => $totalAmount,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays($days),
            ]);

            return null;
        });

        if ($failure === 'already_active') {
            return back()->with('promotion_error', 'Esta publicação já tem uma promoção ativa.');
        }

        if ($failure === 'insufficient_funds') {
            return back()->with('promotion_error', 'Saldo insuficiente na wallet para promover esta publicação.');
        }

        if ($failure === 'post_not_active') {
            return back()->with('promotion_error', 'Só é possível promover publicações activas.');
        }

        return back()->with('promotion_success', 'Publicação promovida com sucesso.');
    }

    public function destroy(PromotedPost $promotion): RedirectResponse
    {
        if ((int) $promotion->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $promotion->update([
            'status' => 'removed',
            'removed_at' => now(),
            'ends_at' => now(),
        ]);

        return back()->with('promotion_success', 'Promoção removida com sucesso.');
    }

    public function adminIndex(Request $request): View
    {
        $this->authorizeAdvertisingManagement();
        $this->expireCampaigns();

        $request->validate([
            'status' => ['nullable', 'in:all,active,removed,expired'],
            'q' => ['nullable', 'string', 'max:255'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $status = $request->get('status', 'all');
        $search = trim((string) $request->get('q', ''));
        $from = $request->get('from');
        $to = $request->get('to');

        $campaigns = PromotedPost::with(['user', 'post'])
            ->when(in_array($status, ['active', 'removed', 'expired'], true), function (Builder $query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->whereHas('user', function (Builder $userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('username', 'like', '%' . $search . '%');
                    })->orWhereHas('post', function (Builder $postQuery) use ($search) {
                        $postQuery->where('description', 'like', '%' . $search . '%');
                    });
                });
            })
            ->when($from, function (Builder $query) use ($from) {
                $query->whereDate('starts_at', '>=', $from);
            })
            ->when($to, function (Builder $query) use ($to) {
                $query->whereDate('starts_at', '<=', $to);
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->appends($request->query());

        return view('admin.promoted-posts', compact('campaigns', 'status', 'search', 'from', 'to'));
    }

    public function adminRemove(PromotedPost $promotion): RedirectResponse
    {
        $this->authorizeAdvertisingManagement();

        if ($promotion->status === 'active') {
            $promotion->update([
                'status' => 'removed',
                'removed_at' => now(),
                'ends_at' => now(),
            ]);
        }

        return back()->with('success_message', 'Promoção removida com sucesso.');
    }

    public function trackClick(PromotedPost $promotion): RedirectResponse
    {
        $post = $promotion->post;
        abort_unless($post && $post->creator, 404);

        if (
            $promotion->status === 'active'
            && $promotion->starts_at
            && $promotion->starts_at->isPast()
            && $promotion->ends_at
            && $promotion->ends_at->isFuture()
        ) {
            $promotion->recordClick();
        }

        return redirect(url($post->creator->username . '/post/' . $post->id));
    }

    private function expireCampaigns(): void
    {
        PromotedPost::where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', now())
            ->update(['status' => 'expired']);
    }

    private function authorizeAdvertisingManagement(): void
    {
        abort_unless(auth()->check() && auth()->user()->hasPermission('advertising'), 403);
    }
}
