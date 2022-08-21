<?php

use Illuminate\Support\Facades\Route;
use BracketSpace\PayumLaravelPackage\Controller;

Route::as('payum.')->prefix('payum')->group(function () {
	Route::as('authorize')->any('authorize/{payum_token}', Controller\AuthorizeController::class);
	Route::as('capture')->any('capture/{payum_token}', Controller\CaptureController::class);
	Route::as('refund')->any('refund/{payum_token}', Controller\RefundController::class);
	Route::as('notifiy')->any('notify/{payum_token}', Controller\NotifyController::class);
	Route::as('notify_unsafe')->any('notify/unsafe/{gateway_name}', Controller\NotifyUnsafeController::class);
});
