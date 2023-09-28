<?php

require dirname(__FILE__) . '/davici/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $myClass = $this->_objectManager->create('DeleteProduct');
        $myClass->deleteProducts();
        return $this->_response;
    }

}


class DeleteProduct{

    protected $_objectManager;
    protected $_registry;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) 
    {
        $this->_registry = $registry;
        $this->productRepository = $productRepository;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function deleteProducts() {

        $this->_registry->register("isSecureArea", true);

        $productIds = ['400', '401'];

        $number = 0;
        foreach ($productIds as $id) {
            $this->productRepository->deleteById($id);
            $number++;
        }

        if ($number) {
            echo __('A total of %1 record(s) were deleted.', $number);
        }
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);