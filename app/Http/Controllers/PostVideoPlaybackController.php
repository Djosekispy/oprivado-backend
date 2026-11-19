<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use UnexpectedValueException;

class PostVideoPlaybackController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'media_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'allowed' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $media = Media::query()
            ->whereKey($request->input('media_id'))
            ->where('status', 'active')
            ->where('type', 'video')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('video')->where('video', '<>', '');
                })->orWhere(function ($query) {
                    $query->whereNotNull('video_embed')->where('video_embed', '<>', '');
                });
            })
            ->whereHas('updates', function ($query) {
                $query->where('status', 'active');
            })
            ->first();

        if (!$media) {
            return response()->json([
                'allowed' => false,
                'message' => 'A publicação de vídeo não está disponível.',
            ], 404);
        }

        $limit = filter_var(config('video_limits.non_pro_limit'), FILTER_VALIDATE_INT);
        if ($limit === false || $limit < 0) {
            throw new UnexpectedValueException('POST_VIDEO_NON_PRO_LIMIT must be a non-negative integer.');
        }

        if (!config('video_limits.non_pro_limit_enabled')) {
            return response()->json([
                'allowed' => true,
                'consumed' => null,
                'limit' => $limit,
                'reset_period' => config('video_limits.reset_period'),
            ]);
        }

        $resetPeriod = config('video_limits.reset_period');
        $periodKey = $this->periodKey($resetPeriod);

        $result = DB::transaction(function () use ($media, $limit, $periodKey) {
            $user = User::query()
                ->whereKey(auth()->id())
                ->lockForUpdate()
                ->firstOrFail();

            if ($user->verified_id === 'yes' || $user->isPro()) {
                return [
                    'allowed' => true,
                    'consumed' => null,
                    'limit' => $limit,
                ];
            }

            $consumedVideos = DB::table('post_video_views')
                ->where('user_id', $user->id)
                ->where('period_key', $periodKey);

            $alreadyPlayed = (clone $consumedVideos)
                ->where('media_id', $media->id)
                ->exists();

            $consumed = (clone $consumedVideos)->count();

            if ($alreadyPlayed) {
                return [
                    'allowed' => true,
                    'consumed' => $consumed,
                    'limit' => $limit,
                ];
            }

            if ($consumed >= $limit) {
                return [
                    'allowed' => false,
                    'consumed' => $consumed,
                    'limit' => $limit,
                ];
            }

            DB::table('post_video_views')->insert([
                'user_id' => $user->id,
                'media_id' => $media->id,
                'period_key' => $periodKey,
                'first_played_at' => now(),
            ]);

            return [
                'allowed' => true,
                'consumed' => $consumed + 1,
                'limit' => $limit,
            ];
        });

        return response()->json($result + ['reset_period' => $resetPeriod]);
    }

    private function periodKey($resetPeriod): string
    {
        switch ($resetPeriod) {
            case 'never':
                return 'lifetime';
            case 'daily':
                return now()->toDateString();
            case 'monthly':
                return now()->format('Y-m');
            default:
                throw new UnexpectedValueException(
                    'POST_VIDEO_LIMIT_RESET_PERIOD must be never, daily, or monthly.'
                );
        }
    }
}
