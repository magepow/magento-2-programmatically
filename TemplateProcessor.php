<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {

        $this->_state->setAreaCode('frontend'); 
        // $this->_state->setAreaCode('adminhtml'); 
        // $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
        // $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $productRepository = $this->_objectManager->create('Magento\Catalog\Model\ProductRepository');
		$filterProvider = $this->_objectManager->create('Magento\Cms\Model\Template\FilterProvider');
		$storeManager = $this->_objectManager->create('Magento\Store\Model\StoreManagerInterface');
	    // YOU WANT TO LOAD BY ID?
	    $id = "YOUR ID HERE";
	    // YOU WANT TO LOAD BY SKU?
	    $sku = "YOUR SKU HERE";

	    if($id) {
	        $product = $productRepository->getById($id);
	    }
	    if($sku) {
	        $product = $productRepository->get($sku);
	    }

	    $descriptionAttributeCode      = "description";
	    $storeId = $storeManager->getStore()->getId();
	    $description = $product->setStoreId($storeId)->getData($descriptionAttributeCode);
	    // echo $description;
	    $html = $filterProvider->getBlockFilter()->setStoreId($storeId)->filter($description);
	    echo $html;
        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);