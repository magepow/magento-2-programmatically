<?php

ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);
//// Setup Base
$folder     = ''; //Folder Name
$file       = $folder ? "$folder/app/bootstrap.php" : "app/bootstrap.php";

if(!file_exists ($file)) $file = "app/bootstrap.php";
if(file_exists ($file)){
    require dirname(__FILE__) .'/' .$file;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
} else {die('Not found bootstrap.php');}

class upToDate extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    protected $todayEndOfDayDate;
    protected $todayStartOfDayDate;
    protected $setTime = '2021-06-20 00:00:00';

    public function launch()
    {
        
        $setTime = $this->setTime;
        $dt = new DateTime();
        // $dt = new DateTime('2017-01-01');
        $this->todayStartOfDayDate = $dt->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $this->todayEndOfDayDate = $dt->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        $discount=0.9;
        $attributeSet = 'Service';
        $attributeSetId = 9;
        // echo $this->todayStartOfDayDate;
        // echo '<br/>';
        // echo $this->todayEndOfDayDate;
        // echo '<br/>';
        // die;

        $this->_state->setAreaCode('adminhtml'); 
        // $_storeManager = $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        // $_storeManager->setCurrentStore(0);
        $model = $this->_objectManager->get('Magento\Catalog\Model\Product\Action');
        // $productRepository = $this->_objectManager->create('Magento\Catalog\Model\ProductRepository');
        // $attributeSetRepository = $this->_objectManager->get('Magento\Eav\Api\AttributeSetRepositoryInterface');
        // $attributeSetCollection = $this->_objectManager->get('\Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory');
        $newProducts = $this->getNewProducts();
        $num = 0;
        if( count($newProducts)){
        foreach ($newProducts as $product) {
            // $product->setStoreId(0)->setData('news_to_date', $setTime)->save();
            // $model->updateAttributes([$product->getId()], ['news_to_date' => $setTime], 0);
            $num++;
        }            
        }

        echo "$num New products changed !<br/>";



        $saleProducts = $this->getSaleProducts();
        $num = 0;
        foreach ($saleProducts as $product) {
            // echo $product->getName();
            if( $product->getAttributeSetId() != $attributeSetId) continue;
            echo $product->getName();
            // $product->setStoreId(0)->setData('special_to_date', $setTime)->save();
            $price      = (int) $product->getData('price');
            $specialPrice  =  (int) ($price*$discount);
            echo $specialPrice;
            $model->updateAttributes([$product->getId()], ['special_to_date' => $setTime], 0);   
            $model->updateAttributes([$product->getId()], ['special_price' => $specialPrice], 0);
            $num++;
        }


        echo "$num Sale products changed !<br/>";

        return $this->_response;
    }

    public function getNewProducts() {

        // $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        // $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->todayEndOfDayDate;
        $todayStartOfDayDate = $this->todayStartOfDayDate;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $manager */
        $manager = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collection = $manager->create();

        $collection->addStoreFilter()
        ->addAttributeToFilter(
            'news_from_date',
            [
                'or' => [
                    0 => ['date' => true, 'to' => $todayEndOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )
        // ->addAttributeToFilter(
        //     'news_to_date',
        //     [
        //         'or' => [
        //             0 => ['date' => true, 'from' => $todayStartOfDayDate],
        //             1 => ['is' => new \Zend_Db_Expr('null')],
        //         ]
        //     ],
        //     'left'
        // )
        ->addAttributeToFilter(
            [
                ['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
                ['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
            ]
        )->addAttributeToSort('news_from_date', 'desc');

        return $collection;
    }

    public function getSaleProducts(){

        // $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        // $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->todayEndOfDayDate;
        $todayStartOfDayDate = $this->todayStartOfDayDate;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $manager */
        $manager = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collection = $manager->create();

        $collection->addStoreFilter()
        ->addAttributeToSelect('*')
        // ->addAttributeToFilter(
        //     'special_from_date',
        //     [
        //         'or' => [
        //             0 => ['date' => true, 'to' => $todayEndOfDayDate],
        //             1 => ['is' => new \Zend_Db_Expr('null')],
        //         ]
        //     ],
        //     'left'
        // )
        // // ->addAttributeToFilter(
        // //     'special_to_date',
        // //     [
        // //         'or' => [
        // //             0 => ['date' => true, 'from' => $todayStartOfDayDate],
        // //             1 => ['is' => new \Zend_Db_Expr('null')],
        // //         ]
        // //     ],
        // //     'left'
        // // )
        // ->addAttributeToFilter(
        //     [
        //         ['attribute' => 'special_from_date', 'is' => new \Zend_Db_Expr('not null')],
        //         ['attribute' => 'special_to_date', 'is' => new \Zend_Db_Expr('not null')],
        //     ]
        // )
        ->addAttributeToSort('special_to_date', 'desc');

        return $collection;

    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('upToDate');
$bootstrap->run($app);
