<?php

namespace App\Contracts;

interface ParseOrderInfoInterface
{

	/**
	 * @param  string $orderInfoKey
	 * @param  string $orderInfoValue
	 * @return string
	 */
	public function formatOrderHistory(string $orderInfoKey, string $orderInfoValue);

}