<?php

namespace App\Console\Commands;

use App\Services\OrderRequest;
use App\Services\ParseOrderInfo;
use Illuminate\Console\Command;

class ListCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list:collections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List Breakdown of Collection and Sales';

    /**
     * @var  array
     */
    private $orders;

    /**
     * @var array
     */
    private $orderInfo;

    /**
     * @var OrderRequest
     */
    private $orderRequest;

    /**
     * @var ParseOrderInfo
     */
    private $parseOrderInfo;

    /**
     * @var float
     */
    private $totalCollections;

    /**
     * @var float
     */
    private $totalSales;    

    /**
     * Create a new command instance.
     *
     * @param  OrderRequest  $orderRequest
     * @param  ParseOrderInfo  $parseOrderInfo
     * @return void
     */
    public function __construct(
        OrderRequest $orderRequest,
        ParseOrderInfo $parseOrderInfo
    ) 
    {
        parent::__construct();
        $this->orders = config('orders');
        $this->orderRequest = $orderRequest;
        $this->parseOrderInfo = $parseOrderInfo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->orders as $order) {
            $this->orderInfo = $this->orderRequest->fetchOrderRequest($order);
            
            $this->info($this->orderInfo['tracking_number']." ({$this->orderInfo['status']})");
            $this->info('  history:');

            $this->sortOrderHistoryByDateTimeAscending($this->orderInfo);
            foreach($this->orderInfo['tat'] as $orderInfoKey => $orderInfoValue) {
                $this->info('    '.$this->parseOrderInfo->formatOrderHistory($orderInfoKey, $orderInfoValue));
            }

            $this->info('  breakdown:');
            $this->info('    subtotal: '. $this->orderInfo['subtotal']);
            $this->info('    shipping: '. $this->orderInfo['shipping']);
            $this->info('    tax: '. $this->orderInfo['tax']);
            $this->info('    insurance: '. $this->orderInfo['insurance']);
            $this->info('    discount: '. $this->orderInfo['discount']);
            $this->info('    total: '. $this->orderInfo['total']);

            $this->info('  fees:');
            $this->info('    shipping_fee: '. $this->orderInfo['shipping_fee']);
            $this->info('    insurance_fee: '. $this->orderInfo['insurance_fee']);
            $this->info('    transaction_fee: '. $this->orderInfo['transaction_fee']."\n");

            $this->addTotalCollections();
            $this->addTotalSales();

        }

        $this->info("total collection: ". $this->totalCollections);
        $this->info("total sales: ". $this->totalSales);
    }

    /**
     * @return array
     */
    private function sortOrderHistoryByDateTimeAscending()
    {
        uasort($this->orderInfo['tat'], function($a, $b) {
            return $a - $b;
        });
    }

    private function addTotalCollections()
    {
        return (float)$this->totalCollections += $this->orderInfo['total'];
    }


    private function addTotalSales()
    {
        return (float)$this->totalSales += $this->orderInfo['shipping_fee'] + $this->orderInfo['insurance_fee'] + $this->orderInfo['transaction_fee'];
    }

}