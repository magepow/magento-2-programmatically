<?php

/**
 * @Author: nguyen
 * @Date:   2022-03-30 15:49:55
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2022-03-30 17:43:33
 */

// https://magento.stackexchange.com/questions/341300/how-to-get-all-scheduled-changes-for-a-product-in-magento-commerce

// https://magento.stackexchange.com/questions/315637/magento-2-enterprise-schedule-update-programatically
// https://magento.stackexchange.com/questions/314163/magically-special-from-date-is-set-whenever-product-is-updated
// https://magento.stackexchange.com/questions/148851/m2-enterprise-how-to-add-special-price-for-product-from-date-to-date-programmat
// 
// 
ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);
//// Setup Base

$folder     = 'magentoe2'; //Folder Name
$file       = $folder ? dirname(__FILE__) . "/$folder/app/bootstrap.php" : "app/bootstrap.php";
$file = str_replace('.com/', '.coms/', $file);
$file = str_replace('/public_html/', '/public_htmls/', $file);

if(!file_exists ($file)) $file = "app/bootstrap.php";
if(file_exists ($file)){
    require $file;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
} else {die('Not found bootstrap.php');}

class Schedule extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    protected $todayEndOfDayDate;
    protected $todayStartOfDayDate;
    protected $setTime = '2024-10-16 00:00:00';

    protected $searchResultFactory;

    public function launch()
    {
        
        $setTime = $this->setTime;
        $dt = new DateTime();
        // $dt = new DateTime('2017-01-01');
        $this->todayStartOfDayDate = $dt->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $this->todayEndOfDayDate = $dt->setTime(23, 59, 59)->format('Y-m-d H:i:s');


        // $this->_state->setAreaCode('adminhtml');

        // $_storeManager = $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        // $_storeManager->setCurrentStore(0);

        $this->searchResultFactory = $this->_objectManager->create('\Magento\Staging\Model\Entity\Upcoming\SearchResultFactory');

        $productId = 56;

        $schedules = [];
        try {
            $params = [
                'entityRequestName' => 'id',
                'entityTable' => 'catalog_product_entity',
                'entityColumn' => 'entity_id'
            ];
            $this->_request->setParams( ['id' => $productId]);
            $schedules = $this->searchResultFactory->create($params);
            foreach ($schedules as $schedule) {
                echo 'Schedules "' . $schedule->getName() . '" start from ' . $schedule->getStartTime() . ' to ' . $schedule->getEndTime() . '<br/>';
            }
        }   catch (\Exception $e){
            echo $e->getMessage();
        }


        echo "schedules done  !<br/>";

        return $this->_response;
    }

    public function getSchedule($productId)
    {
        $schedules = [];
        try {
            $params = [
                'entityRequestName' => 'id',
                'entityTable' => 'catalog_product_entity',
                'entityColumn' => 'entity_id'
            ];
            $this->request->setParams( ['id' => $productId]);
            $schedules = $this->searchResultFactory->create($params);
            foreach ($schedules as $schedule) {
                echo $schedule->getName();
                echo $schedule->getStartTime();
                // Get Other values
            }
        }   catch (\Exception $e){
            echo $e->getMessage();
        }

        return  $schedules;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Schedule');
$bootstrap->run($app);
