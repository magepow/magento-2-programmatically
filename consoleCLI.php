<?php

require dirname(__FILE__) . '/orfarm/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

use Magento\Framework\Console\Cli;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $myClass = $this->_objectManager->create('RunCLI');
        $myClass->execute();
        return $this->_response;
    }

}


class RunCLI
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute()
    {
        $application = new Cli('Magento CLI');
        $input = new ArrayInput([
            'command' => 'cache:flush',
        ]);
        /* use NullOutput if don't want show output */
        $output = new NullOutput();

        /* use NullOutput if want show output in command line */
        $output = new ConsoleOutput();
        
        try {
            $application->run($input, $output);
        } catch (\Exception $exception) {
            $this->logger->critical('Cache Flush failed.', ['exception' => $exception]);
        }
    }
}


/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);