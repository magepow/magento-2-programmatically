<?php
ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);
use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class changeConfig extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
        $objectManager = $bootstrap->getObjectManager();
        $appState = $objectManager->get('Magento\Framework\App\State');
        $appState->setAreaCode('adminhtml');
        $field = $objectManager->create('Magento\Config\Model\Config\Structure')->getElementByConfigPath('customer/create_account/auto_group_assign');
        echo $field->getData()['source_model'];
        return $this->_response;
    }


}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('changeConfig');
$bootstrap->run($app);
