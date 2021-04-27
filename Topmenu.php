<?php

ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);

$file = dirname(__FILE__) . '/app/bootstrap.php';
$file = str_replace('/pub/', '/', $file);
require $file;
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);


class MagentoCLI extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    public function launch()
    {
        $this->_state->setAreaCode('frontend');

        $time_start = microtime(true); 

  
        $menu = $this->_objectManager->create('\Magento\Theme\Block\Html\Topmenu');
        $menu->setTemplate('html/topmenu.phtml');
        $html = $menu->toHtml();
        echo $html;
        
        $time_end = microtime(true);
        
        //dividing with 60 will give the execution time in minutes otherwise seconds
        $execution_time = ($time_end - $time_start)/60;

        echo 'Execution time ' . $execution_time;
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('MagentoCLI');
$bootstrap->run($app);
