<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $setup = $this->_objectManager->create('\Magento\Framework\Setup\ModuleDataSetupInterface');
        $categorySetupFactory = $this->_objectManager->create('\Magento\Catalog\Setup\CategorySetupFactory');
        $categorySetup = $categorySetupFactory->create(['setup' => $setup]);

        $categorySetup->removeAttribute( \Magento\Catalog\Model\Product::ENTITY, 'extrafee');

        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);