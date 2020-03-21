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
        
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'test_attribute',
            [
            'group' => 'General',
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Test Attribute',
            'input' => 'boolean',
            'class' => '',
            'source' => '',
            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
            ]
            );

        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);