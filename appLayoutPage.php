<?php

// Update layout in new version magento 2.3.4 or last

ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);
use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {

        $pageId = 19; // Id of page
        $model = $this->_objectManager->create('Magento\Cms\Model\Page');
        $model->load($pageId); // Id of page
        $pageData = [
            // 'title' => "title page",
            // 'page_layout' => "page_layout type",
            // 'identifier' => "identifier",
            // 'content_heading' => "content_heading text",
            // 'content' => "Content Text",
            'layout_update_xml' => '<!-- Slide showcase-->
<referenceContainer name="slide.showcase">
            <block class="Magento\Cms\Block\Block" name="slide">
                <arguments>
                    <argument name="block_id" xsi:type="string">static-home-slide</argument>
                </arguments>
            </block>
</referenceContainer>
<!-- alo sectionsbottom-->
<referenceContainer name="alo.sectionsbottom">
 <block class="Magento\Cms\Block\Block" name="static_sections_bottom">
                    <arguments>
                        <argument name="block_id" xsi:type="string">static_sections_bottom</argument>
                    </arguments>
                </block>
</referenceContainer>',
            'is_active' => 1
        ];
        $model->addData( $pageData )->save();

        echo 'Done update layout page';

        
        //the method must end with this line
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);
