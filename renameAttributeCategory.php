<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {

        $setup = $this->_objectManager->create('\Magento\Framework\Setup\ModuleDataSetupInterface');

        $eavSetup = $this->_objectManager->get('\Magento\Catalog\Setup\CategorySetupFactory');

        $catalogSetup = $eavSetup->create(['setup' => $setup]);        
        $catalogSetup->updateAttribute('catalog_category', 'xml_sitemap_exclude', array('attribute_code' => 'sitemap_exclude'));

        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);