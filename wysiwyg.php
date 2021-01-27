<?php

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $blockCollection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Block\Collection');
        $images = [];
        foreach ($blockCollection as $block) {
            echo 'Block Id: ' . $block->getId() . '<br/>';
            echo 'Block Identifier: ' . $block->getIdentifier() . '<br/>';
            $content = $block->getContent();

            preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
            if(!empty($matches)){
                echo '<pre>';
                var_dump($matches);
                echo '</pre>';
            }
            echo '<br/>';

        }
// 
        var_dump($images);
        


        $pageCollection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Page\Collection');
        $images = [];
        foreach ($pageCollection as $page) {
            echo 'Page Id: ' . $page->getId() . '<br/>';
            echo 'Page Identifier: ' . $page->getIdentifier() . '<br/>';
            $content = $page->getContent();

            preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
            if(!empty($matches)){
                echo '<pre>';
                var_dump($matches);
                echo '</pre>';
            }
            echo '<br/>';

        }

        var_dump($images);


        echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);