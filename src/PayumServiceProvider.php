<?php

namespace BracketSpace\PayumLaravelPackage;

use BracketSpace\PayumLaravelPackage\Action\GetHttpRequestAction;
use BracketSpace\PayumLaravelPackage\Action\ObtainCreditCardAction;
use BracketSpace\PayumLaravelPackage\Security\TokenFactory;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Payum\Core\Bridge\Symfony\ReplyToSymfonyResponseConverter;
use Payum\Core\Bridge\Symfony\Security\HttpRequestVerifier;
use Payum\Core\Payum;
use Payum\Core\PayumBuilder;
use Payum\Core\Registry\StorageRegistryInterface;
use Payum\Core\Storage\StorageInterface;

class PayumServiceProvider extends ServiceProvider implements DeferrableProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerBuilder();
		$this->registerActions();
		$this->registerPayum();
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadRoutesFrom(__DIR__ . '/../routes/payum.php');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array<string>
	 */
	public function provides()
	{
		return [
			Payum::class,
			PayumBuilder::class,
			ReplyToSymfonyResponseConverter::class,
		];
	}

	/**
	 * Registers Payum Builder.
	 *
	 * @return  void
	 */
	protected function registerBuilder(): void
	{
		$this->app->bind(PayumBuilder::class, function ($app) {
			$builder = new PayumBuilder();

			$builder
				->addDefaultStorages() // TODO: replace with eloquent
				->setTokenFactory(
					fn(
						StorageInterface $tokenStorage,
						StorageRegistryInterface $registry
					) => new TokenFactory($tokenStorage, $registry)
				)
				->setHttpRequestVerifier(
					fn(StorageInterface $tokenStorage) => new HttpRequestVerifier($tokenStorage)
				)
				->setCoreGatewayFactory(function (array $defaultConfig) {
					$factory = new CoreGatewayFactory($defaultConfig);
					$factory->setContainer($this->app);

					return $factory;
				})
				->setCoreGatewayFactoryConfig([
					'payum.action.get_http_request' => 'payum.action.get_http_request',
					'payum.action.obtain_credit_card' => 'payum.action.obtain_credit_card',
				])
				->setGenericTokenFactoryPaths([ // @phpstan-ignore-line
					'capture' => 'payum_capture_do',
					'notify' => 'payum_notify_do',
					'authorize' => 'payum_authorize_do',
					'refund' => 'payum_refund_do',
				])
			;

			return $builder;
		});
	}

	/**
	 * Registers Payum actions.
	 *
	 * @return  void
	 */
	private function registerActions(): void
	{
		$this->app->singleton('payum.action.get_http_request', function ($app) {
			return new GetHttpRequestAction();
		});

		$this->app->singleton('payum.action.obtain_credit_card', function ($app) {
			return new ObtainCreditCardAction();
		});
	}

	/**
	 * Registers Payum instance.
	 *
	 * @return  void
	 */
	private function registerPayum(): void
	{
		$this->app->singleton(Payum::class, function ($app) {
			return $app->make(PayumBuilder::class)->getPayum();
		});
	}
}
