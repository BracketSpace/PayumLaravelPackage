<?php

namespace BracketSpace\PayumLaravelPackage\Action;

use Payum\Core\Bridge\Symfony\Action\GetHttpRequestAction as SymfonyGetHttpRequestAction;

class GetHttpRequestAction extends SymfonyGetHttpRequestAction
{
	/**
	 * {@inheritDoc}
	 *
	 * @return void
	 */
	public function execute($request)
	{
		$this->setHttpRequest(app()->make('request'));

		parent::execute($request);
	}
}
