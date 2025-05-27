<?php

require dirname(__FILE__) . '../app/bootstrap.php';

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

use Magento\Backend\Model\Menu;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\Node\Collection;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;


class CategoryTree extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    /**
     * Top menu data tree
     *
     * @var Node
     */
    protected $_menu;

    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var TreeFactory
     */
    private $treeFactory;

   /**
     * Catalog category
     *
     * @var \Magento\Catalog\Helper\Category
     */
    protected $catalogCategory;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    private $layerResolver;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;


    public function launch()
    {
        $this->_state->setAreaCode('frontend');

        $adminCategoryTree = $this->_objectManager->get('\Magento\Catalog\Block\Adminhtml\Category\Tree');
        echo '<pre>';
        var_dump($adminCategoryTree->getTree());
        echo '</pre>';

        return $this->_response;
    }



    
}


/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('CategoryTree');
$bootstrap->run($app);
