<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {

        $setup = $this->_objectManager->create('\Magento\Framework\Setup\ModuleDataSetupInterface');

        $eavSetup = $this->_objectManager->get('Magento\Eav\Setup\EavSetupFactory');

        $catalogSetup = $eavSetup->create(['setup' => $setup]);        
        // $catalogSetup->updateAttribute('catalog_category', 'demo_url', array('attribute_code' => 'demo_frontend'));
        $catalogSetup->updateAttribute('catalog_product', 'demo_url', array('attribute_code' => 'demo_frontend'));

        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);