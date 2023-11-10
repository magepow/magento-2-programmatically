<?php

/* Update order field `product_options` in table `sales_order_item`*/

use Magento\Framework\Exception\NoSuchEntityException;

$file = '../app/bootstrap.php';
require $file;
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class UpdateOrder extends \Magento\Framework\App\Http implements \Magento\Framework\AppInterface
{

    protected $orderRepository;
    protected $orderStatusRepository;
    protected $orderCollectionFactory;
    protected $logger;
    protected $total;

    public function launch()
    {
        $this->_state->setAreaCode('adminhtml');
        $this->orderCollectionFactory = $this->_objectManager->get('\Magento\Sales\Model\ResourceModel\Order\CollectionFactory');
        $this->orderRepository = $this->_objectManager->create('\Magento\Sales\Api\OrderRepositoryInterface');
        $this->orderStatusRepository = $this->_objectManager->create('\Magento\Sales\Api\OrderStatusHistoryRepositoryInterface');
        $this->logger = $this->_objectManager->create('\Psr\Log\LoggerInterface');

        $collection = $this->getOrderCollection();
        foreach ($collection as $order) {
            $this->updateOrder($order->getId());
        }

        echo __("Total update order update: %1 \n", $this->total);

        return $this->_response;
    }

    public function getOrderCollection()
    {
       
       $collection = $this->orderCollectionFactory->create()->addAttributeToSelect('*');
     
        return $collection;
    }

    public function updateOrder($orderId)
    {
        $order = null;
        try {
            $order = $this->orderRepository->get($orderId);
        } catch(NoSuchEntityException $exception) {
            $this->logger->error($exception->getMessage());
        }

        if ($order){
            $items = $order->getAllitems();
            foreach ($items as $item) {
                $options = $item->getProductOptions();
                if(!$options){
                    // var_dump($options);
                    // var_dump($item->getData('product_type'));
                    $options = [
                        "info_buyRequest" => [
                            "uenc"=> "aHR0cHM6Ly9tZWRpY2FsLXRvb2xzLmNvbS9zaG9wL3ZldGVyaW5hcnktc3V0dXJlLWtpdC5odG1s",
                            "id"  => $item->getData('product_id'),
                            "product" => $item->getData('product_id'),
                            "selected_configurable_option" => '',
                            "related_product" => '',
                            "qty" => 1
                            // "qty" => $item->getData('qty_ordered')
                        ]
                    ];

                    $item->setProductOptions($options);
                     // $item->save();

                }
            }

            try {
                $order->save();
                $this->total++;
                // echo __("Done update order Id: %1 \n", $order->getId());
            } catch(\Exception $exception){
                $this->logger->critical($exception->getMessage());
            }
        }
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('UpdateOrder');
$bootstrap->run($app);