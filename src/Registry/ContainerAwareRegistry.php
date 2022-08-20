<?php

namespace BracketSpace\PayumLaravelPackage\Registry;

use Illuminate\Container\Container;
use Payum\Core\Registry\AbstractRegistry;

class ContainerAwareRegistry extends AbstractRegistry
{
	/**
	 * The container instance.
	 *
	 * @var Container
	 */
	protected Container $container;

	/**
	 * @param Container $container
	 */
	public function setContainer(Container $container): void
	{
		$this->container = $container;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getService($id)
	{
		/** @var object */
		return $this->container->make($id);
	}
}
