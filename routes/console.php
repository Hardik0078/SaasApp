<?php

use App\Services\BillingService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('subscriptions:expire', function (BillingService $billingService) {
    $billingService->expireOverdueSubscriptions();

    $this->info('Expired subscriptions processed.');
})->purpose('Expire outdated subscriptions and emit notifications.');
