<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {

        // $this->_state->setAreaCode('frontend'); 
        $this->_state->setAreaCode('adminhtml'); 
        // $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
        // $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $productRepository = $this->_objectManager->create('Magento\Catalog\Model\ProductRepository');

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

	    $shortDescriptionAttributeCode = "short_description";
	    $descriptionAttributeCode      = "description";

	    $shortDescriptionAttributeValue = "YOUR NEW VALUE";
	    $descriptionAttributeValue      = "YOUR NEW VALUE";


	    $product->addAttributeUpdate($shortDescriptionAttributeCode, $shortDescriptionAttributeValue, 0);
	    $product->addAttributeUpdate($descriptionAttributeCode, $descriptionAttributeValue, 0);

        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);