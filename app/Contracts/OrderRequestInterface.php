<?php

namespace App\Contracts;

interface OrderRequestInterface
{

	/**
	 * @param  string $order
	 * @return array
	 */
	public function fetchOrderRequest(string $order);

}