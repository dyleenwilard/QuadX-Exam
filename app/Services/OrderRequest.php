<?php

namespace App\Services;

use App\Contracts\OrderRequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OrderRequest implements OrderRequestInterface
{

	/**
	 * @var string
	 */
	private $baseUrl;

	/**
	 * @var GuzzleHttp\Client
	 */
	private $client;

	/**
	 * @var array
	 */
	private $headers;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->client = new Client();
	}

	/**
	 * @return Response
	 */
	public function fetchOrderRequest(string $order)
	{
		$this->setBaseUrl();
		$this->setHeaders();

		$promise = $this->client->getAsync($this->baseUrl.$order, [$this->headers])
		->then(
			function ($response) {
				return json_decode($response->getBody()->getContents(), true);
			}, 
			function ($exception) {
				return $exception->getMessage();
			}
		);	

		return $promise->wait();
	}

	/**
	 * @return  void
	 */	
	private function setBaseUrl()
	{
		$this->baseUrl = env('QUADX_BASE_URL');
	}

	/**
	 * @return  void 
	 */
	private function setHeaders()
	{
		$this->headers = [
			'X-Time-Zone'	=> 'Asia/Manila'
		];
	}

}