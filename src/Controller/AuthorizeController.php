<?php

namespace BracketSpace\PayumLaravelPackage\Controller;

use Payum\Core\Bridge\Symfony\ReplyToSymfonyResponseConverter as ReplyConverter;
use Payum\Core\Payum;
use Payum\Core\Reply\ReplyInterface;
use Payum\Core\Request\Authorize;
use Symfony\Component\HttpFoundation\Request;

class AuthorizeController
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
	 * Payment Authorize action constructor.
	 *
	 * @param  Payum  $payum The Payum instance.
	 */
	public function __construct(Payum $payum, ReplyConverter $converter)
	{
		$this->payum = $payum;
		$this->converter = $converter;
	}

	/**
	 * Handles Payment Authorize action.
	 *
	 * @param   string  $payumToken
	 * @return  mixed
	 */
	public function __invoke(string $payumToken)
	{
		/** @var Request $request */
		$request = \App::make('request');
		$request->attributes->set('payum_token', $payumToken);

		$token = $this->payum->getHttpRequestVerifier()->verify($request);
		$gateway = $this->payum->getGateway($token->getGatewayName());

		try {
			$gateway->execute(new Authorize($token));
		} catch (ReplyInterface $reply) {
			return $this->converter->convert($reply);
		}

		$this->payum->getHttpRequestVerifier()->invalidate($token);

		return \Redirect::to($token->getAfterUrl());
	}
}
