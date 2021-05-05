<?php

ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);

$file = dirname(__FILE__) . '/safira/app/bootstrap.php';
$file = str_replace('/public_html/', '/public_htmls/', $file);
require $file;
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

use Magento\Backend\Model\Menu;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\Node\Collection;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\DataObject;


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

    protected $_recursionLevel = 0;


    public function launch()
    {
        $this->_state->setAreaCode('frontend');

        $this->nodeFactory = $this->_objectManager->get('\Magento\Framework\Data\Tree\NodeFactory');
        $this->treeFactory = $this->_objectManager->get('\Magento\Framework\Data\TreeFactory');

        $this->storeManager = $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        $this->catalogCategory = $this->_objectManager->create('\Magento\Catalog\Helper\Category');
        $this->layerResolver = $this->_objectManager->create('\Magento\Catalog\Model\Layer\Resolver');

        $rootId    = $this->storeManager->getStore()->getRootCategoryId();
        $storeId = $this->storeManager->getStore()->getId();
        echo "Root Id = $rootId and Store Id = $storeId";
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->getCategoryTree($storeId, $rootId);
        $currentCategory = $this->getCurrentCategory();
        $mapping = [$rootId => $this->getMenu()];  // use nodes stack to avoid recursion
        foreach ($collection as $category) {
            $categoryParentId = $category->getParentId();
            if (!isset($mapping[$categoryParentId])) {
                $parentIds = $category->getParentIds();
                foreach ($parentIds as $parentId) {
                    if (isset($mapping[$parentId])) {
                        $categoryParentId = $parentId;
                    }
                }
            }

            /** @var Node $parentCategoryNode */
            $parentCategoryNode = $mapping[$categoryParentId];

            $categoryNode = new Node(
                $this->getCategoryAsArray(
                    $category,
                    $currentCategory,
                    $category->getParentId() == $categoryParentId
                ),
                'id',
                $parentCategoryNode->getTree(),
                $parentCategoryNode
            );
            $parentCategoryNode->addChild($categoryNode);

            $mapping[$category->getId()] = $categoryNode; //add node in stack
        }
        // echo '<pre>';
        //     var_dump($mapping);
        // echo '</pre>';
        $menu = isset($mapping[$rootId]) ? $mapping[$rootId]->getChildren() : [];
        $html = $this->getTreeCategories($menu, 'root-class');
        echo $html;
        //the method must end with this line
        return $this->_response;
    }

    public function  getTreeCategories($categories, $itemPositionClassPrefix) // include Magic_Label and Maximal Depth
    {
        $html = '';
        $counter = 1;
        foreach($categories as $category) {
            $level = $category->getLevel();
            $catChild  = $category->getChildren();
            $childHtml = ( $this->_recursionLevel == 0 || ($level -1 < $this->_recursionLevel) ) ? $this->getTreeCategories($catChild, $itemPositionClassPrefix) : '';
            $childClass  = $childHtml ? ' hasChild parent ' : ' ';
            $childClass .= $itemPositionClassPrefix . '-' .$counter;
            $childClass .= ' category-item ';
            $html .= '<li class="level' . ($level -2) . $childClass . '"><a href="' . $category->getUrl() . '"><span>' . $category->getName() . "</span></a>\n";
            $html .= $childHtml;
            $html .= '</li>';
            $counter++;
        }
        if($html) $html = '<ul class="level' .($level -3). ' submenu">' . $html . '</ul>';
        return  $html;
    }
    /**
     * Get menu object.
     *
     * Creates Tree root node object.
     * The creation logic was moved from class constructor into separate method.
     *
     * @return Node
     * @since 100.1.0
     */
    public function getMenu()
    {
        if (!$this->_menu) {
            $this->_menu = $this->nodeFactory->create(
                [
                    'data' => [],
                    'idField' => 'root',
                    'tree' => $this->treeFactory->create()
                ]
            );
        }
        return $this->_menu;
    }

    protected function getCategoryTree($storeId, $rootId)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Category\Collection');
        $collection->setStoreId($storeId);
        $collection->addAttributeToSelect('name');
        $collection->addFieldToFilter('path', ['like' => '1/' . $rootId . '/%']); //load only from store root
        $collection->addAttributeToFilter('include_in_menu', 1);
        $collection->addIsActiveFilter();
        $collection->addNavigationMaxDepthFilter();
        $collection->addUrlRewriteToResult();
        $collection->addOrder('level', 'ASC');
        $collection->addOrder('position', 'ASC');
        $collection->addOrder('parent_id', 'ASC');
        $collection->addOrder('entity_id', 'ASC');
        return $collection;
    }

    /**
     * Convert category to array
     *
     * @param \Magento\Catalog\Model\Category $category
     * @param \Magento\Catalog\Model\Category $currentCategory
     * @param bool $isParentActive
     * @return array
     */
    private function getCategoryAsArray($category, $currentCategory, $isParentActive)
    {
        $categoryId = $category->getId();
        return [
            'name' => $category->getName(),
            'id' => 'category-node-' . $categoryId,
            'url' => $this->catalogCategory->getCategoryUrl($category),
            'has_active' => in_array((string)$categoryId, explode('/', (string)$currentCategory->getPath()), true),
            'is_active' => $categoryId == $currentCategory->getId(),
            'is_category' => true,
            'is_parent_active' => $isParentActive
        ];
    }

    /**
     * Get current Category from catalog layer
     *
     * @return \Magento\Catalog\Model\Category
     */
    private function getCurrentCategory()
    {
        $catalogLayer = $this->layerResolver->get();

        if (!$catalogLayer) {
            return null;
        }

        return $catalogLayer->getCurrentCategory();
    }

}


/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('CategoryTree');
$bootstrap->run($app);
