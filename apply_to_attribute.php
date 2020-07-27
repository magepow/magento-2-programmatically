<?php
require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $eavAttribute = $resource->getTableName('eav_attribute'); //gives table name with prefix

        //Select Data from table
        $sql = "SELECT attribute_id FROM " . $eavAttribute . " WHERE attribute_code='manufacturer'";

        $manufacturerID = $connection->fetchOne($sql); // gives associated array, table fields as key in array.
        echo 'manufacturerID is: ' . $manufacturerID ;

        if($manufacturerID){
            $catalogEavAttribute = $resource->getTableName('catalog_eav_attribute'); //gives table name with prefix

            "UPDATE " . $catalogEavAttribute . " SET apply_to ='simple,virtual,bundle,downloadable,configurable' WHERE attribute_id ='" . $manufacturerID . "'";
            $connection->query($sql);
        }

        echo 'Update Done';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);