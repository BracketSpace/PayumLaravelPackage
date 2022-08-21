<?php

namespace BracketSpace\PayumLaravelPackage\Security;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
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
		return Route::has($path)
			? URL::route($path, $parameters)
			: URL::to($path, $parameters);
	}
}
