<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $appState = $this->_objectManager->get('Magento\Framework\App\State');
        $appState->setAreaCode('adminhtml');
        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('cron_schedule');

        $sql = "DELETE FROM " . $tableName . " WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)";

        try{
            $result = $connection->query($sql);
        }catch(Exception $ex){
            print_r($ex);
        }

        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);