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

        $block = $this->_objectManager->create('\Magento\Cms\Model\Block');   
		$filterProvider = $this->_objectManager->create('Magento\Cms\Model\Template\FilterProvider');
		$storeManager = $this->_objectManager->create('Magento\Store\Model\StoreManagerInterface');

	    $blockId = 1; // Id of block you want get content
	    $storeId = $storeManager->getStore()->getId();
	    $block->setStoreId($storeId)->load($blockId);    
	    $content = $block->getContent();
	    $html = $filterProvider->getBlockFilter()->setStoreId($storeId)->filter($content);
	    echo $html;
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);