<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('things', function ($user) {
    return $user !== null; // только авторизованным
});
