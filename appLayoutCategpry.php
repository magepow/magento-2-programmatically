<?php

// Update layout category magento 2

ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {

        $appState = $this->_objectManager->get('\Magento\Framework\App\State');

        $appState->setAreaCode('frontend');

        $categoryCollection = $this->_objectManager->get('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');

        $categories = $categoryCollection->create();

        $categories->addAttributeToSelect('*');

        $categories->load();

        if (count($categories) > 0):
            foreach($categories as $category):
                $catId = $category->getId();
                $category = $this->_objectManager->create('Magento\Catalog\Model\CategoryFactory')->create()->setStoreId(0)->load($catId);
                $category->setData('custom_use_parent_settings', '0')->setData('custom_design', '');
                $category->save();
                echo 'Update ' . $category->getName() . '<br/>';
            endforeach;
         else: echo "No Results";
        endif;

        echo 'Done update layout page';
        
        //the method must end with this line
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);
