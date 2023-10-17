<?php

/*
*
* Run command: php ProductSalableQuantity.php
*
*/
use Magento\Framework\App\Bootstrap;

require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');
//Required for custom code ends

setProductQty($objectManager);

function setProductQty($objectManager)
{
    $productId = ["193206"];
    foreach ($productId as $itemId) {
        try {
            $stockModel = $objectManager->get('Magento\CatalogInventory\Model\Stock\ItemFactory')->create();
            $stockResource = $objectManager->get('Magento\CatalogInventory\Model\ResourceModel\Stock\Item');
            $stockResource->load($stockModel, $itemId,"product_id");
            $stockModel->setQty("90");
            $stockResource->save($stockModel);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}