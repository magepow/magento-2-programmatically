<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (https://www.magepow.com/) 
 * @license     https://www.magepow.com/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2018-03-20 21:14:06
 * @@Modify Date: 2018-05-31 10:17:23
 * @@Function:
 */
ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);
//// Setup Base
$folder     = ''; //Folder Name
$file       = $folder ? "$folder/app/bootstrap.php" : "app/bootstrap.php";

if(!file_exists ($file)) $file = "app/bootstrap.php";
if(file_exists ($file)){
    require dirname(__FILE__) .'/' .$file;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
} else {die('Not found bootstrap.php');}


use Magento\Catalog\Model\Product;
use Magento\Downloadable\Model\Link;
use Magento\Downloadable\Model\LinkFactory;
use Magento\Downloadable\Model\Product\Type as DownloadableType;
use Magento\Downloadable\Model\ResourceModel\Link as LinkResource;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;

class DownloadableUpdate extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    public function launch()
    {

        $this->_state->setAreaCode('adminhtml'); ;
        $downloadable = $this->getDownloadableProducts();
        $num = 0;
        foreach ($downloadable as $item) {
            try {

                $productName = $item->getName();
                $productSku = $item->getSku();
                $product = $this->_objectManager->get(Product::class)->loadByAttribute('sku', $productSku);

                if (!$product) {
                    throw new LocalizedException(__('Product not found'));
                }

                // Load the downloadable link information
                $linkFactory = $this->_objectManager->get(LinkFactory::class);
                $linkCollection = $product->getTypeInstance()->getLinks($product);

                // Iterate through the links and update
                foreach ($linkCollection as $link) {
                    // Here, we're just updating the link's URL

                    echo $link->getLinkUrl() . PHP_EOL;
                    // $link->setLinkUrl('https://domain.com/new-downloadable-link-url.zip'); // Replace with new link
                    // $link->setLinkTitle('New Downloadable Link Title');  // You can update the title too
                    // $link->save();

                    echo $link->getLinkFile() . PHP_EOL;
                    // $link->setLinkFile('new-downloadable-link-url.zip'); // Replace with new link
                    // $link->setLinkTitle('New Downloadable Link Title');  // You can update the title too
                    // $link->save();

                }

                $num++;
                // echo "Downloadable link updated successfully! Product Name($productName) & SKU($productSku)";
            } catch (\Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            
        }            

        echo "$num Downloadable products changed !<br/>";


        return $this->_response;
    }


    public function getDownloadableProducts()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory')->create();

        $collection->addAttributeToSelect(['name', 'sku']); // Select all attributes
        $collection->addAttributeToFilter('type_id', ['eq' => DownloadableType::TYPE_DOWNLOADABLE]); // Filter by downloadable product type
        // $collection->load(); // Load the collection

        return $collection;
    }


}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('DownloadableUpdate');
$bootstrap->run($app);
