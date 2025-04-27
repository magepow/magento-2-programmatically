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
        $collections = $this->_objectManager->create('\Magento\Review\Model\ResourceModel\Review\Collection');
        $reviewProduct = $collections->addFieldToSelect('*')
                            ->setPageSize(10) // only get 10 reviews 
                            ->setCurPage(1)
                            ->setOrder('review_id', 'ASC');

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
            <?php foreach ($reviewProduct as $review) : ?>
                <?php 
                    echo '<pre>';
                    // var_dump($review->toArray());
                    echo '</pre>';
                ?>
                <tr class="item_row">
                    <td> <?php echo $review->getData('review_id'); ?>     </td>
                    <td> <?php echo $review->getData('entity_pk_value'); ?>    </td>
                    <td> <?php echo $review->getData('title'); ?>   </td>
                    <td> <?php echo $review->getData('detail'); ?>  </td>
                    <td> <?php echo $review->getData('nickname'); ?></td>
                    <td> <?php echo $review->getData('customer_id'); ?>  </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php        
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('reviewProduct');
$bootstrap->run($app);
