<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $appState = $this->_objectManager->get('Magento\Framework\App\State');
        $appState->setAreaCode('frontend');
		$stockState = $this->_objectManager->get('\Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku');
		$productSku = 'WT09';
		$qty = $stockState->execute($productSku);

		echo 'Qty: ' . $qty[0]['qty'];

        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);