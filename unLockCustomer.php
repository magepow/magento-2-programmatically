<?php

/**
 * @Author: Alex Dong
 * @Date:   2021-03-10 10:09:13
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2021-03-10 10:23:29
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
