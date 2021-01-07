<?php

/**
 * @Author: nguyen
 * @Date:   2020-12-29 11:18:57
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2021-01-07 14:24:10
 */

ini_set('display_startup_errors', 1);ini_set('display_errors', 1); error_reporting(-1);

//// Setup Base
$folder     = 'recentorder'; //Folder Name
$file       = $folder ? "$folder/app/bootstrap.php" : "app/bootstrap.php";

if(!file_exists ($file)) $file = "app/bootstrap.php";
if(file_exists ($file)){
    require dirname(__FILE__) .'/' .$file;
    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
} else {die('Not found bootstrap.php');}

/* For get RoleType and UserType for create Role   */;
use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\UserContextInterface;

class createDemoAdmin extends \Magento\Framework\App\Http
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

    /**
     * RoleFactory
     *
     * @var roleFactory
     */
    private $_roleFactory;

     /**
     * RulesFactory
     *
     * @var rulesFactory
     */
    private $_rulesFactory;


    /**
     * User model factory
     *
     * @var \Magento\User\Model\UserFactory
     */    
    protected $_userFactory;


    public function launch()
    {
        $this->_integrationData        = $this->_objectManager->create('\Magento\Integration\Helper\Data');
        $this->_rootResource           = $this->_objectManager->create('\Magento\Framework\Acl\RootResource');
        $this->_aclResourceProvider    = $this->_objectManager->create('\Magento\Framework\Acl\AclResource\ProviderInterface');
        $this->_rulesCollectionFactory = $this->_objectManager->create('\Magento\Authorization\Model\ResourceModel\Rules\CollectionFactory');

        $this->_roleFactory  = $this->_objectManager->get('\Magento\Authorization\Model\RoleFactory');
        $this->_rulesFactory = $this->_objectManager->get('\Magento\Authorization\Model\RulesFactory');
        $this->_userFactory  = $this->_objectManager->get('\Magento\User\Model\UserFactory');

        $role = $this->createRole('Demo Rule');
        
        $resources = $this->_aclResourceProvider->getAclResources();


        $denyResource = [
                        'Magento_Backend::myaccount',
                        'Magento_AdobeIms::login',
                        'Magento_Backup::rollback',
                        'Magento_User::acl',
                        'Magento_User::acl_roles',
                        'Magento_User::locks',
                        'Magento_Backup::backup',
                        'Magento_AdobeIms::actions',
                        'Magento_User::acl_users',
                        'Magento_AdobeIms::adobe_ims',
                        'Magento_Backend::all',
                        'Magento_AdobeIms::logout',
                        'Magento_AdobeIms::adobe_ims',
                        // custom module
                        'Nans_AutoLogin::main',
                        'Nans_AutoLogin::config'
                    ];

        $resourcesTree =  $this->_aclResourceProvider->getAclResources();
        $resources     = $this->getValueAclResources($resourcesTree);
        foreach ($resources as $key => $value) {
            if(in_array($value, $denyResource)) unset($resources[$key]);
        }

        /* Array of resources ids which we want to allow this role*/
        $this->_rulesFactory->create()->setRoleId($role->getId())->setResources($resources)->saveRel();

        $adminInfo = [
            'username'  => 'demo',
            'firstname' => 'Demo',
            'lastname'  => 'Mage',
            'email'     => 'demo@example.com',
            'password'  =>'admin123',       
            'interface_locale' => 'en_US',
            'is_active' => 1
        ];

        $user = $this->createUser($adminInfo, $role->getId());
        var_dump($user->getData());
        echo 'Run finished';

        return $this->_response;
    }

    public function createRole($name, $uniqueName=true)
    {
        $role = $this->_roleFactory->create();
        if($uniqueName){
            $collection = $role->getCollection()->addFieldToFilter('role_name', $name);
            if($collection->getSize()){
                return $collection->getFirstItem();
            }
        }
        $role->setName($name) //Set Role Name Which you want to create 
                ->setPid(0) //set parent role id of your role
                ->setRoleType(RoleGroup::ROLE_TYPE) 
                ->setUserType(UserContextInterface::USER_TYPE_ADMIN);
        $role->save();
        return $role;
    }

    public function createUser($userInfo, $roleId=1, $update=true)
    {
        $user = $this->_userFactory->create();
        if(!isset($userInfo['username'])) return __('The username is require.');

        $user->loadByUsername($userInfo['username']);
        if( $user->getId() ){
            if($update) $user->addData($userInfo)->save();
        } else {
            $user->setData($userInfo);
            $user->setRoleId($roleId);
            try{
               $user->save(); 
            } catch (\Exception $ex) {
                $ex->getMessage();
            }            
        }
        return $user;
    }

    public function getUserAclResources($userId)
    {

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
$app = $bootstrap->createApplication('createDemoAdmin');
$bootstrap->run($app);
