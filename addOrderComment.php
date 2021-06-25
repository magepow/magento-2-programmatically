<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

use Magento\Framework\Exception\NoSuchEntityException;

$file = dirname(__FILE__) . '/onestepcheckout/app/bootstrap.php';
$file = str_replace('com/', 'coms/', $file);
require $file;
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class savComment extends \Magento\Framework\App\Http implements \Magento\Framework\AppInterface
{

    protected $orderRepository;
    protected $orderStatusRepository;
    protected $logger;

    public function launch()
    {

        $this->_state->setAreaCode('adminhtml');
        $this->orderRepository = $this->_objectManager->create('\Magento\Sales\Api\OrderRepositoryInterface');
        $this->orderStatusRepository = $this->_objectManager->create('\Magento\Sales\Api\OrderStatusHistoryRepositoryInterface');
        $this->logger = $this->_objectManager->create('\Psr\Log\LoggerInterface');

        $order = null;
        $orderId = 10;
        try {
            $order = $this->orderRepository->get($orderId);
        } catch(NoSuchEntityException $exception) {
            $this->logger->error($exception->getMessage());
        }

        $orderHistory = null;

        if ($order){
            $comment = $order->addStatusHistoryComment('Comment for the order');
            try {
                $orderHistory = $this->orderStatusRepository->save($comment);
            } catch(\Exception $exception){
                $this->logger->critical($exception->getMessage());
            }
        }

        echo 'Done save order comment!';
        //the method must end with this line
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('savComment');
$bootstrap->run($app);
