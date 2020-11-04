<?php

use Magento\Framework\App\Bootstrap;

require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();


try{
    $_cacheTypeList = $objectManager->create('Magento\Framework\App\Cache\TypeListInterface');
    $_cacheFrontendPool = $objectManager->create('Magento\Framework\App\Cache\Frontend\Pool');
    $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
    foreach ($types as $type) {
        $_cacheTypeList->cleanType($type);
    }
    foreach ($_cacheFrontendPool as $cacheFrontend) {
        $cacheFrontend->getBackend()->clean();
    }
}catch(Exception $e){
    echo $msg = 'Error : '.$e->getMessage();die();
}