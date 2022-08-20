<?php

namespace BracketSpace\PayumLaravelPackage\Controller;

use Payum\Core\Payum;
use Payum\Core\Reply\ReplyInterface;
use Payum\Core\Request\Notify;
use Symfony\Component\HttpFoundation\Request;

class NotifyUnsafeController
{
	/**
	 * The Payum instance.
	 *
	 * @var Payum
	 */
	private Payum $payum;

	/**
	 * Payment Unsafe Notify action constructor.
	 *
	 * @param  Payum  $payum The Payum instance.
	 */
	public function __construct(Payum $payum)
	{
		$this->payum = $payum;
	}

	/**
	 * Handles Payment Unsafe Notify action.
	 *
	 * @param   Request  $request
	 * @return  mixed
	 */
	public function __invoke(Request $request)
	{
		$gateway = $this->payum->getGateway($request->get('gateway_name'));

		$gateway->execute(new Notify(null));

		return \Response::make(null, 204);
	}
}
