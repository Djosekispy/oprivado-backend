<?php

namespace App\Http\Controllers;

use App\Models\PromotedPost;
use App\Models\Updates;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotedPostController extends Controller
{
    public const DAILY_PRICE = 2000.00;

    public function index(): View
    {
        PromotedPost::where('status', 'active')
            ->where('ends_at', '<=', now())
            ->update(['status' => 'expired']);

        $campaigns = PromotedPost::where('user_id', auth()->id())
            ->with('post')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('users.my_promotions', [
            'campaigns' => $campaigns,
            'dailyPrice' => self::DAILY_PRICE,
        ]);
    }

    public function store(Request $request, Updates $post): RedirectResponse
    {
        $this->validate($request, [
            'days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        PromotedPost::where('status', 'active')
            ->where('ends_at', '<=', now())
            ->update(['status' => 'expired']);

        if (PromotedPost::where('update_id', $post->id)->where('status', 'active')->exists()) {
            return back()->with('promotion_error', 'Esta publicação já tem uma promoção ativa.');
        }

        $days = (int) $request->days;
        $totalAmount = $days * self::DAILY_PRICE;

        if (auth()->user()->wallet < $totalAmount) {
            return back()->with('promotion_error', 'Saldo insuficiente na wallet para promover esta publicação.');
        }

        $promotion = PromotedPost::create([
            'user_id' => auth()->id(),
            'update_id' => $post->id,
            'days' => $days,
            'daily_price' => self::DAILY_PRICE,
            'total_amount' => $totalAmount,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($days),
        ]);

        auth()->user()->decrement('wallet', $totalAmount);

        return back()->with('promotion_success', 'Publicação promovida com sucesso.');
    }

    public function destroy(PromotedPost $promotion): RedirectResponse
    {
        if ($promotion->user_id !== auth()->id()) {
            abort(403);
        }

        $promotion->update([
            'status' => 'removed',
            'removed_at' => now(),
            'ends_at' => now(),
        ]);

        return back()->with('promotion_success', 'Promoção removida com sucesso.');
    }
}
