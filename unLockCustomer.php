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

class updateOrder extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    public function launch()
    {

        $this->_state->setAreaCode('adminhtml'); 
        // $_storeManager = $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        // $_storeManager->setCurrentStore(0);
        $customerId = '2272';
        $authentication = $this->_objectManager->create('Magento\Customer\Model\AuthenticationInterface');
        $customerRepository  = $this->_objectManager->create('Magento\Customer\Api\CustomerRepositoryInterface');
        // go to admin url domain.com/admin/customer/locks/unlock/customer_id/1
        if ($customerId) {
            $authentication->unlock($customerId);
            $customer = $customerRepository->getById($customerId);
            $customer->setConfirmation(null);
            $customerRepository->save($customer);
            echo 'done';
        }
        
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('updateOrder');
$bootstrap->run($app);
