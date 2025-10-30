<?php

return [
    'admin' => [
        'email' => env('ADMIN_NOTIFICATION_EMAIL', 'admin@example.com'),
        'broadcast_channel' => env('ADMIN_NOTIFICATION_CHANNEL', 'admin.notifications'),
    ],
];
