<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
		$installSchema	= $this->_objectManager->create('\Magepow\ModuleName\Setup\InstallSchema');
		$context 		= $this->_objectManager->create('\Magento\Setup\Model\ModuleContext', ['version' => '1.0.0']);
		$setup 			= $this->_objectManager->create('\Magento\Setup\Module\Setup');
		$installSchema->install($setup, $context);
		
		echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);