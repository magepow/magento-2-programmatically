<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $setup = $this->_objectManager->create('\Magento\Framework\Setup\ModuleDataSetupInterface');
        $eavSetupFactory = $this->_objectManager->create('\Magento\Eav\Setup\EavSetupFactory');
        $eavSetup = $eavSetupFactory->create(['setup' => $setup]);
        
        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'test_attribute'
        );

        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);