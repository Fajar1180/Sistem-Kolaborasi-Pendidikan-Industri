<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// S2-02 — tiap hari, pengajuan yang tanggal mulai programnya tiba menjadi `berjalan` (§7.5).
Schedule::command('program:mulai-berjalan')->dailyAt('01:00');
