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
    $productCollection = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory')->create();
    $productCollection->addAttributeToSelect(['name, sku']);
    $qty = 100;
    foreach ($productCollection as $product) {
        try {
            $productId  = $product->getId();
            $stockModel = $objectManager->get('Magento\CatalogInventory\Model\Stock\ItemFactory')->create();
            $stockResource = $objectManager->get('Magento\CatalogInventory\Model\ResourceModel\Stock\Item');
            $stockResource->load($stockModel, $productId,"product_id");
            $stockModel->setQty($qty);
            $stockResource->save($stockModel);
            echo __("Successfully updated Qty for product SKU %1", $product->getSku()) . PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}