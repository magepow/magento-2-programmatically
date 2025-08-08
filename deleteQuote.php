<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $myClass = $this->_objectManager->create('DeleteQuote');
        $myClass->deleteQuotes();
        return $this->_response;
    }

}


class DeleteQuote{

    protected $_objectManager;
    protected $_registry;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(
        \Magento\Framework\Registry $registry
    ) 
    {
        $this->_registry = $registry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function deleteQuotes() {

        $this->_registry->register("isSecureArea", true);

        $limit = 50000;
        $collection = $this->_objectManager->create('\Magento\Quote\Model\Quote');
        $quotes = $collection->getCollection()
                                // ->addFieldToFilter("customer_email", ["like" => "%@qq.com"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%\.com%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%点com%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%\.top%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%点top%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%\.blog%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%http%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%www%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%link%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%Bitcоin%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%USDT%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%USDC%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%USDD%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%Notify%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%Attach%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%ACCOUNT%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%BALANCE%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%TRONLINK%"])
                                ->addFieldToFilter("customer_firstname", ["like" => "%TRIAL%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%Customer%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%client%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%friend%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%Hello%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%your%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%Message%"])
                                // ->addFieldToFilter("customer_firstname", ["like" => "%private%"])
                                ->setPageSize($limit)->setCurPage(1);

        $quotesDeleted = 0;
        $quotesAddressDeleted = 0;
        foreach ($quotes as $quote) {
            // echo $quote->getData('customer_email');
            // continue;
            // if ($quote->getId() && !$quote->getIsActive() && !$quote->getReservedOrderId()) {
            if ($quote->getId() && !$quote->getReservedOrderId()) {
                $addressCollection = $quote->getAddressesCollection();
                foreach ($addressCollection as $address) {
                    // You can choose to delete either the shipping or billing address
                    // Delete all addresses associated with the quote
                    $address->delete();
                    $quotesAddressDeleted++;
                }
                $quote->delete();
                $quotesDeleted++;
            }
        }

        if ($quotesDeleted) {
            echo __('A total of %1 record(s) Quotes were deleted.', $quotesDeleted);
        }
        if ($quotesAddressDeleted) {
            echo __('A total of %1 record(s) Quotes Address were deleted.', $quotesAddressDeleted);
        }
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);