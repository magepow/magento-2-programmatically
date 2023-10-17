<?php

/*
*
* This script help synchronize Quantity with Salable Quantity
* Run command: php ProductSynchronizeMSI.php
*
*/

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $state = $this->_objectManager->get('Magento\Framework\App\State');
        $state->setAreaCode('adminhtml');
        $myClass = $this->_objectManager->create('ProductSalableQuantity');
        $myClass->updateSalableQuantity();
        return $this->_response;
    }

}


class ProductSalableQuantity
{


    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\InventoryCatalogApi\Model\SourceItemsProcessorInterface
     */
    protected $sourceItemsProcessor;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\InventoryCatalogApi\Model\SourceItemsProcessorInterface $sourceItemsProcessor
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->sourceItemsProcessor = $sourceItemsProcessor;
    }

    public function updateSalableQuantity() {

        $sourceData = [
            ['source_code'=>'default', 'status'=>1, 'quantity'=>50],
        ];
        $productsSKU = ['SRC081MASU'];

        $collection = $this->productCollectionFactory->create()
                            ->joinField('qty',
                                'cataloginventory_stock_item',
                                'qty',
                                'product_id=entity_id',
                                '{{table}}.stock_id=1',
                                'left'
                            )->joinTable('cataloginventory_stock_item', 'product_id = entity_id', ['stock_status' => 'is_in_stock'])
                            ->addAttributeToSelect('*')
                            ->addAttributeToSelect('sku');
        if($productsSKU !== '*' && is_array($productsSKU)){
            $collection->addAttributeToFilter('sku', ['in' => $productsSKU]);
        }
        try {
            $total = 0;
            foreach ($collection as $product) {
                /* clone array */
                $data = array_merge($sourceData, []);
                foreach($data as $key => $source){
                    $qty = (int) $product->getQty();
                    $dataUpdate = [
                        'quantity' => $qty,
                        'status' => $qty ?  1: 0,
                    ];
                    $data[$key] = array_merge($source, $dataUpdate);
                }
                $sku = $product->getSku();
                $this->sourceItemsProcessor->execute(
                    $sku,
                    $data
                );
                $total++;
            }

            echo __("A total %1 product(s) synchronize salable quantity successfully.", $total) . PHP_EOL;

        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);