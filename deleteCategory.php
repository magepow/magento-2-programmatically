<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $ids = [10, 15, 20];
        $categoryFactory = $this->_objectManager->get('Magento\Catalog\Model\CategoryFactory');
        $_MyClass = $this->_objectManager->create('DeleteCategory');
         $_MyClass->deleteCategories($ids);
        return $this->_response;
    }

    public function catchException(\Magento\Framework\App\Bootstrap $bootstrap, \Exception $exception)
    {
        return false;
    }

}


class DeleteCategory{

    protected $_objectManager;
    protected $_registry;

    public function __construct(
        \Magento\Framework\Registry $registry
        ) 
    {
        $this->_registry = $registry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function deleteCategories($ids = array()) {
        $categoryFactory = $this->_objectManager->get('Magento\Catalog\Model\CategoryFactory');
        $categories = $categoryFactory->create();
        $collection = $categories->getCollection()->addAttributeToSelect('name');
        $this->_registry->register("isSecureArea", true);
        foreach($collection as $category) {
            if(is_array($ids) && in_array($category->getId(), $ids))
            {
                $category->delete();
                echo 'category ' . $category->getName() . ' deleted';
            } else if($category->getId() == $ids) {
                $category->delete();
                echo 'category ' . $category->getName() . ' deleted';
            }
        }
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);