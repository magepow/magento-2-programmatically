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

        $attributeId = $eavSetup->getAttributeId('catalog_product', 'manufacturer');
        $options = [
                'values' => [
                '1' => 'brand 1',
                '2' => 'brand 2',
                '3' => 'brand 3',
            ],
            'attribute_id' => $attributeId ,
        ];

        $eavSetup->addAttributeOption($options);

        echo 'Done';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('changeConfig');
$bootstrap->run($app);