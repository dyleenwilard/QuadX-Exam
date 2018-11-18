<?php

namespace App\Services;

use App\Contracts\ParseOrderInfoInterface;
use Carbon\Carbon;

class ParseOrderInfo implements ParseOrderInfoInterface
{

	/**
	 * @var array
	 */
	private $orders;

    /**
     * @var array
     */
    private $orderInfo;

	/**
	 * Constructor
	 */
	public function __construct(OrderRequest $orderRequest)
	{
		$this->orders = config('orders');
		$this->orderRequest = $orderRequest;
	}

	/**
	 * @param  [string] $orderInfoKey
	 * @param  [string] $orderInfoValue
	 * @return [string]
	 */
	public function formatOrderHistory($orderInfoKey, $orderInfoValue)
	{
		return Carbon::createFromTimestamp($orderInfoValue, 'Asia/Manila')->format('Y-m-d H:i:s.u').": ". $orderInfoKey;
	}


}