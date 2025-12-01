<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $myClass = $this->_objectManager->create('DeleteSearchQuery');
        $myClass->deleteSearchQuery();
        return $this->_response;
    }

}


class DeleteSearchQuery{

    protected $_objectManager;
    protected $_registry;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(
        \Magento\Framework\Registry $registry,
        ) 
    {
        $this->_registry = $registry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function deleteSearchQuery() {

        $this->_registry->register("isSecureArea", true);

        $limit = 100000;
        // $limit = 10;
        $collection = $this->_objectManager->create('\Magento\Search\Model\Query');
        $searchQuery = $collection->getCollection()
                                ->addFieldToSelect("*")
                                // ->addFieldToFilter("query_text", ["like" => "%http%"])
                                // ->addFieldToFilter("query_text", ["like" => "%www%"])
                                // ->addFieldToFilter("query_text", ["like" => "%.com%"])
                                // ->addFieldToFilter("query_text", ["like" => "%.cn%"])
                                // ->addFieldToFilter("query_text", ["like" => "%btc%"])
                                // ->addFieldToFilter("query_text", ["like" => "%bitcoin%"])
                                // ->addFieldToFilter("query_text", ["like" => "%eth%"])
                                // ->addFieldToFilter("query_text", ["like" => "%ripple%"])
                                // ->addFieldToFilter("query_text", ["like" => "%solana%"])
                                // ->setOrder('query_text ', 'DESC')
                                // ->setOrder('query_text ', 'ASC')
                                ->setOrder('num_results', 'ASC')
                                ->setPageSize($limit)->setCurPage(1);

        $querysDeleted = 0;
        foreach ($searchQuery as $query) {
            echo $query->getQueryText();
            $query->delete();
            $querysDeleted++;
        }

        if ($querysDeleted) {
            echo __('A total of %1 record(s) were deleted.', $querysDeleted);
        }
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);