<?php

use Illuminate\Support\Facades\Route;
use BracketSpace\PayumLaravelPackage\Controller;

Route::as('payum.')->prefix('payment')->group(function () {
	Route::as('authorize')->any('authorize/{payumToken}', Controller\AuthorizeController::class);
	Route::as('capture')->any('capture/{payumToken}', Controller\CaptureController::class);
	Route::as('refund')->any('refund/{payumToken}', Controller\RefundController::class);
	Route::as('notifiy')->any('notify/{payumToken}', Controller\NotifyController::class);
	Route::as('notify_unsafe')->any('notify/unsafe/{gatewayName}', Controller\NotifyUnsafeController::class);
});
