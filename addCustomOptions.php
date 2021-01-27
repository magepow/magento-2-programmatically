<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $appState = $this->_objectManager->get('\Magento\Framework\App\State');
        $appState->setAreaCode('adminhtml');

        $productRepository  = $this->_objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');
        $optionFactory      = $this->_objectManager->get('\Magento\Catalog\Model\Product\OptionFactory');

        $productIds = [
            10,
            25
        ];

        foreach ($productIds as $productId) {
            try{            
                $_product = $productRepository->getById($productId);
                $optionsArray = [
                    [
                        'title' => 'Select option',
                        'type' => 'drop_down',
                        'is_require' => 1,
                        'sort_order' => 1,
                        'values' => [
                            [
                                'title' => 'Option 1',
                                'price' => 10,
                                'price_type' => 'fixed',
                                'sku' => 'Option 1 sku',
                                'sort_order' => 1,
                            ],
                            [
                                'title' => 'Option 2',
                                'price' => 10,
                                'price_type' => 'fixed',
                                'sku' => 'Option 2 sku',
                                'sort_order' => 2,
                            ],
                            [
                                'title' => 'Option 3',
                                'price' => 10,
                                'price_type' => 'fixed',
                                'sku' => 'Option 3 sku',
                                'sort_order' => 3,
                            ],
                        ],
                    ]
                ];
                 
                foreach ($optionsArray as $optionValue) {
                    $option = $optionFactory->create()->setProductId($_product->getId())
                                ->setStoreId($_product->getStoreId())
                                ->addData($optionValue);
                    $option->save();
                    $_product->addOption($option);
                    // must save product to add options in product
                    $productRepository->save($_product);
                }

            } catch (\Exception $e) {
                echo $e->getMessage();

            }

        }

        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);