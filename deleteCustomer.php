<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $this->_state->setAreaCode('adminhtml'); ;
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
                                ->addAttributeToSort('entity_id', 'DESC')
                                ->addAttributeToFilter([
                                    ['attribute' => 'email', 'like' => '%@qq.com'],
                                    ['attribute' => 'email', 'like' => '%@sina.com'],
                                    ['attribute' => 'email', 'like' => '%@sohu.com'],
                                    ['attribute' => 'email', 'like' => '%@naver.com'],
                                    // ['attribute' => 'email', 'like' => '%@.cn'],
                                    ['attribute' => 'email', 'like' => '%@163.com'],
                                    ['attribute' => 'email', 'like' => '%@139.com'],
                                    ['attribute' => 'email', 'like' => '%@126.com'],
                                    ['attribute' => 'email', 'like' => '%.ru'],
                                    ['attribute' => 'email', 'like' => '%sample%'],
                                    ['attribute' => 'email', 'like' => '%example%'],
                                    ['attribute' => 'firstname', 'like' => '%www.%'],
                                    ['attribute' => 'firstname', 'like' => '%http%'],
                                    ['attribute' => 'firstname', 'like' => '%sex%'],
                                    ['attribute' => 'lastname', 'like' => '%www.%'],
                                    ['attribute' => 'lastname', 'like' => '%http%'],
                                    ['attribute' => 'lastname', 'like' => '%sex%']
                                ])->setPageSize($limit)->setCurPage(1);

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
