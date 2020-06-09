<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2018-03-20 21:14:06
 * @@Modify Date: 2018-05-31 10:17:23
 * @@Function:
 */
ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);
//// Setup Base
$folder     = ''; //Folder Name
$file       = $folder ? "$folder/app/bootstrap.php" : "app/bootstrap.php";

if(!file_exists ($file)) $file = "app/bootstrap.php";
if(file_exists ($file)){
    require dirname(__FILE__) .'/' .$file;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
} else {die('Not found bootstrap.php');}

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    public function launch()
    {
        


        $this->_state->setAreaCode('adminhtml'); 
        // $_storeManager = $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        // $_storeManager->setCurrentStore(0);
        $orderRepository = $this->_objectManager->create('Magento\Sales\Api\OrderRepositoryInterface');
        $searchCriteriaBuilder = $this->_objectManager->create('Magento\Framework\Api\SearchCriteriaBuilder');

        $incrementId = 2000000222;
        $customerId = 999;

        $searchCriteria = $searchCriteriaBuilder->addFilter('increment_id', $incrementId, 'eq')->create();
        $order = $orderRepository->getList($searchCriteria)->getFirstItem();
        if ($order->getId() && !$order->getCustomerId()) 
        {
            $order->setCustomerId($customerId);
            $order->setCustomerIsGuest(0);
            $orderRepository->save($order);


            echo "Update order done!<br/>";
        }
        echo 'done';
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);
