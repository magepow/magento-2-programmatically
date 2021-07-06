<?php

ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);

$file = dirname(__FILE__) . '/safira/app/bootstrap.php';
$file = str_replace('/public_html/', '/public_htmls/', $file);
require $file;
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outside extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $this->_state->setAreaCode('frontend');
        $category          = $this->_objectManager->create('Magento\Catalog\Model\Category');
        $_coreRegistry     =  $this->_objectManager->get('Magento\Framework\Registry');
        $catalogConfig     = $this->_objectManager->create('\Magento\Catalog\Model\Config');
        $productVisibility = $this->_objectManager->create('\Magento\Catalog\Model\Product\Visibility');
        $curentCategory    = $_coreRegistry->registry('current_category');
        $categoryId = 256;
        if ($curentCategory){
            $categoryId = $curentCategory->getId();   
        }else {
            $product = $_coreRegistry->registry('current_product');
            if($product){
                // get collection of categories this product is associated with
                $categories = $product->getCategoryCollection()->setPage(1, 1)->load();
                // if the product is associated with any category
                if ($categories->count()) {
                    // show products from this category
                    $categoryId = current($categories->getIterator())->getId();
                }
            }         
        }
        if ($categoryId) {
            $category->load($categoryId);
            # Get _productCollection use layerCategory
            $_productCollection = $category->getProductCollection()
                                    ->addAttributeToSelect($catalogConfig->getProductAttributes())
                                    ->addMinimalPrice()
                                    ->addFinalPrice()
                                    ->addTaxPercents()
                                    ->addUrlRewrite($categoryId)
                                    ->setVisibility($productVisibility->getVisibleInCatalogIds())
                                    ->addStoreFilter()
                                    ->setPageSize(10)
                                    ->setCurPage(1);

            echo __('This category have %1 products!', $_productCollection->count());
        } else {
            echo __('This category not exist!');
        }
        //the method must end with this line
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outside');
$bootstrap->run($app);
