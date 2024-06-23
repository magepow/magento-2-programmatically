<?php
/**
 * Public alias for the application entry point
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Magento\Framework\App\Bootstrap;

try {
    require __DIR__ . '/../app/bootstrap.php';
} catch (\Exception $e) {
    echo <<<HTML
<div style="font:12px/1.35em arial, helvetica, sans-serif;">
    <div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
        <h3 style="margin:0;font-size:1.7em;font-weight:normal;text-transform:none;text-align:left;color:#2f2f2f;">
        Autoload error</h3>
    </div>
    <p>{$e->getMessage()}</p>
</div>
HTML;
    http_response_code(500);
    exit(1);
}


$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
//Required for custom code ends

sortProductQty($objectManager);

function sortProductQty($objectManager)
{
    $stockFilter = $objectManager->create('\Magento\CatalogInventory\Helper\Stock');

    $collection = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory')->create()
                                ->addCategoriesFilter(['eq' => 26]);
    $collection->addAttributeToSelect('*');
    // $collection->setFlag('has_stock_status_filter', true);
    $collection = $collection->joinField('qty',
            'cataloginventory_stock_item',
            'qty',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        )->joinTable('cataloginventory_stock_item', 'product_id = entity_id', ['stock_status' => 'is_in_stock'])
        ->addAttributeToSelect('stock_status')
        // ->addAttributeToSort('entity_id', 'DESC')
        ->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
        ->addAttributeToSort('qty', 'DESC')
        ->load();


        echo '<pre>';
        var_dump($collection->getData());
        echo '</pre>';
        die;
}