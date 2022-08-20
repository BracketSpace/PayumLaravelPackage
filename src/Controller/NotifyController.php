<?php

namespace BracketSpace\PayumLaravelPackage\Controller;

use Payum\Core\Bridge\Symfony\ReplyToSymfonyResponseConverter as ReplyConverter;
use Payum\Core\Payum;
use Payum\Core\Reply\ReplyInterface;
use Payum\Core\Request\Notify;
use Symfony\Component\HttpFoundation\Request;

class NotifyController
{
	/**
	 * The Payum instance.
	 *
	 * @var Payum
	 */
	private Payum $payum;

	/**
	 * The Reply Converter instance.
	 *
	 * @var ReplyConverter
	 */
	private ReplyConverter $converter;

	/**
	 * Payment Notify action constructor.
	 *
	 * @param  Payum  $payum The Payum instance.
	 */
	public function __construct(Payum $payum, ReplyConverter $converter)
	{
		$this->payum = $payum;
		$this->converter = $converter;
	}

	/**
	 * Handles Payment Notify action.
	 *
	 * @param   string  $payumToken
	 * @return  mixed
	 */
	public function __invoke(string $payumToken)
	{
		/** @var Request $request */
		$request = app('request');
		$request->attributes->set('payum_token', $payumToken);

		$token = $this->payum->getHttpRequestVerifier()->verify($request);
		$gateway = $this->payum->getGateway($token->getGatewayName());

		try {
			$gateway->execute(new Notify($token));
		} catch (ReplyInterface $reply) {
			return $this->converter->convert($reply);
		}

		return \Response::make(null, 204);
	}
}
