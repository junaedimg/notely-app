<?php

use Illuminate\Support\Carbon;

if (!function_exists('simulated_today')) {
    function simulated_today(): Carbon
    {
        return session('simulated_today')
            ? Carbon::parse(session('simulated_today'))
            : now();
    }
}
