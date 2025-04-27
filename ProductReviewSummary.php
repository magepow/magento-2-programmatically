<?php
ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);
//// Setup Base
$folder     = 'magento2'; //Folder Name
$file       = $folder ? dirname(__FILE__) . "/$folder/app/bootstrap.php" : "app/bootstrap.php";
$file = str_replace('.com/', '.coms/', $file);
$file = str_replace('/public_html/', '/magento2/', $file);
if(!file_exists ($file)) $file = "app/bootstrap.php";
if(file_exists ($file)){
    require $file;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
} else {die('Not found bootstrap.php');}

class reviewProduct extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $this->getReviewCollection();
        
        //the method must end with this line
        return $this->_response;
    }

    public function getReviewCollection()
    {
        $collections = $this->_objectManager->create('\Magento\Review\Model\ResourceModel\Review\Summary\Collection');
        $reviewSummary = $collections->addFieldToSelect('entity_pk_value')
                            ->setOrder('rating_summary', 'DESC')
                            // ->setOrder('reviews_count', 'DESC')
                            ->setPageSize(10) // only get 10 reviews 
                            ->setCurPage(1);
        // $reviewProduct = $reviewProduct->getSelect()->group('entity_pk_value');
        $reviewSummary->distinct(true);
        $productIds = [];
        foreach ($reviewSummary as $review){
            $productIds[] = $review->getData('entity_pk_value');
        }
        var_dump($productIds);
        ?>
        <table>
            <thead align="left" style="display: table-header-group">
                <tr>
                    <th>review_id</th>
                    <th>entity_pk_value(Product ID)</th>
                    <th>title</th>
                    <th>detail</th>
                    <th>nickname</th>
                    <th>customer_id</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($reviewSummary as $review) : ?>
                <?php 
                    echo '<pre>';
                    var_dump($review->toArray());
                    echo '</pre>';
                ?>
                
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php        
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('reviewProduct');
$bootstrap->run($app);
