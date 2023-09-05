<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $myClass = $this->_objectManager->create('DeleteCustomer');
        $myClass->deleteCustomers();
        return $this->_response;
    }

}


class DeleteCustomer{

    protected $_objectManager;
    protected $_registry;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(
        \Magento\Framework\Registry $registry,
        CollectionFactory $collectionFactory,
        CustomerRepositoryInterface $customerRepository
        ) 
    {
        $this->_registry = $registry;
        $this->customerRepository = $customerRepository;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function deleteCustomers() {

        $this->_registry->register("isSecureArea", true);

        $limit = 10000;
        $collection = $this->_objectManager->create('\Magento\Customer\Model\Customer');
        $customers = $collection->getCollection()
                                ->addAttributeToFilter("email", ["like" => "%@qq.com"])
                                ->setPageSize($limit)->setCurPage(1);

        $customersDeleted = 0;
        foreach ($customers as $customer) {
            $this->customerRepository->deleteById($customer->getId());
            $customersDeleted++;
        }

        if ($customersDeleted) {
            echo __('A total of %1 record(s) were deleted.', $customersDeleted);
        }
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);