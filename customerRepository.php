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
ini_set('max_execution_time', 900000000);

$folder     = 'orfarm'; //Folder Name 
$file       = $folder ? dirname(__FILE__) . "/$folder/app/bootstrap.php" : "app/bootstrap.php";
$file = str_replace('.com/', '.coms/', $file);
$file = str_replace('/public_html/', '/public_htmls/', $file);

if(!file_exists ($file)) $file = "app/bootstrap.php";
if(file_exists ($file)){
    require $file;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
} else {die('Not found bootstrap.php');}


use Magento\Customer\Model\CustomerRegistry;

class AccountManagement extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
    * @param CustomerRegistry
     */

    private $customerRegistry;

    public function launch()
    {

        $this->customerRegistry = $this->_objectManager->get(
            \Magento\Customer\Model\CustomerRegistry::class
        );

        $this->customerRepository = $this->_objectManager->get(
            \Magento\Customer\Api\CustomerRepositoryInterface::class,
            ['customerRegistry' => $this->customerRegistry]
        );

        echo 'Create customerRepository with customerRegistry';


        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('AccountManagement');
$bootstrap->run($app);
