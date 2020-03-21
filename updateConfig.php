<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $config = $this->_objectManager->create('\Magento\Config\Model\ResourceModel\Config');
        $config->saveConfig('web/unsecure/base_url', 'http://magepow.com/',\Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,0);

        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);