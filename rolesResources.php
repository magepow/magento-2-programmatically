<?php

/**
 * @Author: nguyen
 * @Date:   2020-12-29 11:18:57
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2020-12-29 11:25:08
 */


//// Setup Base
$folder     = ''; //Folder Name
$file       = $folder ? "$folder/app/bootstrap.php" : "app/bootstrap.php";

if(!file_exists ($file)) $file = "app/bootstrap.php";
if(file_exists ($file)){
    require dirname(__FILE__) .'/' .$file;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
} else {die('Not found bootstrap.php');}

/* For get RoleType and UserType for create Role   */;

class getAclResourcesAdmin extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    /**
     * Root ACL Resource
     *
     * @var \Magento\Framework\Acl\RootResource
     */
    protected $_rootResource;

    /**
     * Rules collection factory
     *
     * @var \Magento\Authorization\Model\ResourceModel\Rules\CollectionFactory
     */
    protected $_rulesCollectionFactory;

    /**
     * Acl resource provider
     *
     * @var \Magento\Framework\Acl\AclResource\ProviderInterface
     */
    protected $_aclResourceProvider;

    /**
     * @var \Magento\Integration\Helper\Data
     */
    protected $_integrationData;

    public function launch()
    {
        $this->_rootResource 	= $this->_objectManager->create('\Magento\Framework\Acl\RootResource');
        $this->_rulesCollectionFactory 	= $this->_objectManager->create('\Magento\Authorization\Model\ResourceModel\Rules\CollectionFactory');
        $this->_aclResourceProvider = $this->_objectManager->create('\Magento\Framework\Acl\AclResource\ProviderInterface');
        $this->_integrationData = $this->_objectManager->create('\Magento\Integration\Helper\Data');
        echo '<pre>';
        $resources =  $this->getAclResources();
        var_dump($this->getValueAclResources($resources));
        // var_dump($this->getTree());
        echo '</pre>';

        echo 'done';
        return $this->_response;
    }

    public function getValueAclResources($resources, $dataArray=[])
    {
        foreach ($resources as $value) {
            if(!isset($value['id'])) continue;
            $dataArray[] = $value['id'];
            if(isset($value['children'])){
                /* Keep tree */
                // $dataArray[] = $this->getValueAclResources($value['children'], $dataArray);
                $dataArray = $this->getValueAclResources($value['children'], $dataArray);

            }
        }
        return $dataArray;
    }

     /**
     * Get Json Representation of Resource Tree
     *
     * @return array
     */
    public function getTree()
    {
        return $this->_integrationData->mapResources($this->getAclResources());
    }

    /**
     * Get lit of all ACL resources declared in the system.
     *
     * @return array
     */
    private function getAclResources()
    {
        $resources = $this->_aclResourceProvider->getAclResources();
        $configResource = array_filter(
            $resources,
            function ($node) {
                return isset($node['id'])
                    && $node['id'] == 'Magento_Backend::admin';
            }
        );
        $configResource = reset($configResource);
        return isset($configResource['children']) ? $configResource['children'] : [];
    }


}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('getAclResourcesAdmin');
$bootstrap->run($app);
