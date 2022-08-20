<?php

namespace BracketSpace\PayumLaravelPackage\Cast;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PaymentDetailsCast implements CastsAttributes
{
	/**
	 * Cast the given value.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @param  string  $key
	 * @param  mixed  $value
	 * @param  array<mixed>  $attributes
	 * @return object|null
	 */
	public function get($model, $key, $value, $attributes)
	{
		if (!is_string($value)) {
			return null;
		}

		try {
			return (object)json_decode($value, true, 512, JSON_THROW_ON_ERROR);
		} catch (\Throwable $e) {
			return null;
		}
	}

	/**
	 * Prepare the given value for storage.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @param  string  $key
	 * @param  array<mixed>  $value
	 * @param  array<mixed>  $attributes
	 * @return string
	 */
	public function set($model, $key, $value, $attributes)
	{
		$details = json_encode($value, JSON_PRESERVE_ZERO_FRACTION);

		return is_string($details) ? $details : '';
	}
}
