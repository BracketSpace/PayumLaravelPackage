<?php

namespace BracketSpace\PayumLaravelPackage\Security;

use Payum\Core\Security\AbstractTokenFactory;

class TokenFactory extends AbstractTokenFactory
{
	/**
	 * {@inheritDoc}
	 *
	 * @param string $path
	 * @param array<string, mixed> $parameters
	 */
	protected function generateUrl($path, array $parameters = [])
	{
		return \URL::route($path, $parameters);
	}
}
