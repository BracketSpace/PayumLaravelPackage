<?php

namespace BracketSpace\PayumLaravelPackage;

use Illuminate\Contracts\Container\Container;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\CoreGatewayFactory as BaseCoreGatewayFactory;
use Payum\Core\Gateway;
use Illuminate\Support\Str;

class CoreGatewayFactory extends BaseCoreGatewayFactory
{
	/**
	 * The container instance.
	 *
	 * @var Container
	 */
	private Container $container;

	/**
	 * @param Container $container
	 */
	public function setContainer(Container $container): void
	{
		$this->container = $container;
	}

	/**
	 * @param Gateway     $gateway
	 * @param ArrayObject $config
	 * @return void
	 */
	protected function buildActions(Gateway $gateway, ArrayObject $config)
	{
		foreach ($config as $name => $value) {
			if (Str::startsWith($name, 'payum.action') && is_string($value)) {
				$config[$name] = $this->container->make($value);
			}
		}

		parent::buildActions($gateway, $config);
	}

	/**
	 * @param Gateway     $gateway
	 * @param ArrayObject $config
	 * @return void
	 */
	protected function buildApis(Gateway $gateway, ArrayObject $config)
	{
		foreach ($config as $name => $value) {
			if (Str::startsWith($name, 'payum.api') && is_string($value)) {
				$config[$name] = $this->container->make($value);
			}
		}

		parent::buildApis($gateway, $config);
	}

	/**
	 * @param Gateway     $gateway
	 * @param ArrayObject $config
	 * @return void
	 */
	protected function buildExtensions(Gateway $gateway, ArrayObject $config)
	{
		foreach ($config as $name => $value) {
			if (Str::startsWith($name, 'payum.extension') && is_string($value)) {
				$config[$name] = $this->container->make($value);
			}
		}

		parent::buildExtensions($gateway, $config);
	}
}
