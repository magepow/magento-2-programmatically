<?php
ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);
use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $this->showTableTheme();

        $collections = $this->_objectManager->create('Magento\Theme\Model\Theme');
        $themes = $collections->getCollection()->addFieldToSelect('*');
        foreach ($themes as $theme) {
            $id = $theme->getData('theme_id');
            $this->setType($id);
        }

        // $themesCollections = $this->_objectManager->create('Magento\Theme\Model\Theme\Collection');
        // $themesCollections->addConstraint(Collection::CONSTRAINT_AREA, Area::AREA_FRONTEND);
        // $themes = [];
        // $themesCollections = $this->_objectManager->create('Magento\Framework\View\Design\Theme\ListInterface');
        
        //the method must end with this line
        return $this->_response;
    }

    public function showTableTheme()
    {
        $collections = $this->_objectManager->create('Magento\Theme\Model\Theme');
        $themes = $collections->getCollection()->addFieldToSelect('*');

        ?>
        <table>
            <thead align="left" style="display: table-header-group">
                <tr>
                    <th>theme_id </th>
                    <th>parent_id </th>
                    <th>theme_path </th>
                    <th>theme_title </th>
                    <th>preview_image </th>
                    <th>is_featured </th>
                    <th>area </th>
                    <th>type </th>
                    <th>code </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($themes as $theme) : ?>
                <?php 
                    // var_dump($theme->toArray());
                 ?>
                <tr class="item_row">
                    <td> <?php echo $theme->getData('theme_id'); ?>     </td>
                    <td> <?php echo $theme->getData('parent_id'); ?>    </td>
                    <td> <?php echo $theme->getData('theme_path'); ?>   </td>
                    <td> <?php echo $theme->getData('theme_title'); ?>  </td>
                    <td> <?php echo $theme->getData('preview_image'); ?></td>
                    <td> <?php echo $theme->getData('is_featured'); ?>  </td>
                    <td> <?php echo $theme->getData('area'); ?>         </td>
                    <td> <?php echo $theme->getData('type'); ?>         </td>
                    <td> <?php echo $theme->getData('code'); ?>         </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php        
    }

    public function setType($id)
    {
            $theme = $this->_objectManager->create('Magento\Theme\Model\Theme');
            try {
                $theme->load($id, 'theme_id');
                $theme->setType(0);
                $theme->save();
                echo 'done';
            } catch (\Exception $e) {
                    $this->messageManager->addError(__('Can\'t create child theme error "%1"', $e->getMessage()));
            }
    }


}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);
