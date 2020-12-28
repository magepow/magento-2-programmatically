<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2018-03-20 21:14:06
 * @@Modify Date: 2018-05-31 10:17:23
 * @@Function:
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
use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\UserContextInterface;

class createRole extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

     /**
     * userFactory
     *
     * @var userFactory
     */
    private $userFactory;

    /**
     * RoleFactory
     *
     * @var roleFactory
     */
    private $roleFactory;

     /**
     * RulesFactory
     *
     * @var rulesFactory
     */
    private $rulesFactory;


    public function launch()
    {
        $this->userFactory 	= $this->_objectManager->get('\Magento\User\Model\UserFactory');
        $this->roleFactory 	= $this->_objectManager->get('\Magento\Authorization\Model\RoleFactory');
        $this->rulesFactory = $this->_objectManager->get('\Magento\Authorization\Model\RulesFactory');

        /**
        * Create Warehouse role 
        */
        $role = $this->roleFactory->create();
        $role->setName('Demo Rule') //Set Role Name Which you want to create 
                ->setPid(0) //set parent role id of your role
                ->setRoleType(RoleGroup::ROLE_TYPE) 
                ->setUserType(UserContextInterface::USER_TYPE_ADMIN);
        $role->save();
        /* Now we set that which resources we allow to this role */
        $resource=['Magento_Backend::admin',
					'Magento_Sales::sales',
					'Magento_Sales::create',
					'Magento_Sales::actions_view', //you will use resource id which you want tp allow
					'Magento_Sales::cancel'
				  ];
        /* Array of resource ids which we want to allow this role*/
        $this->rulesFactory->create()->setRoleId($role->getId())->setResources($resource)->saveRel();

        $user = $this->userFactory->create()->loadByUsername('demo');
        $user->setRoleId($role->getId());
        $user->save();

        echo 'done';
        return $this->_response;
    }

}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('createRole');
$bootstrap->run($app);
