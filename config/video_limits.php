<?php

return [
    'non_pro_limit_enabled' => env('ENABLE_POST_VIDEO_NON_PRO_LIMIT', false),
    'non_pro_limit' => env('POST_VIDEO_NON_PRO_LIMIT', 10),
    'reset_period' => env('POST_VIDEO_LIMIT_RESET_PERIOD', 'never'),
];
