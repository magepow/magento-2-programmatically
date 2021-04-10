<?php

ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);

$file = dirname(__FILE__) . '/hoi/app/bootstrap.php';
$file = str_replace('/public_html/', '/public_htmls/', $file);
require $file;
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class MagentoCLI extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $this->_state->setAreaCode('frontend'); 
        $k[0]='bin/magento';
        $k[1]='cache:flush'; // You can change command as you want like setup:static-content:deploy, cache:status etc.
        $_SERVER['argv']=$k;
        try {
            $handler = new \Magento\Framework\App\ErrorHandler();
            set_error_handler([$handler, 'handler']);
            $application = new Magento\Framework\Console\Cli('Magento CLI');
            $application->run();
            echo 'done Command';
        } catch (\Exception $e) {
            while ($e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
                echo "\n\n";
                $e = $e->getPrevious();
            }
        }
        //the method must end with this line
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('MagentoCLI');
$bootstrap->run($app);
