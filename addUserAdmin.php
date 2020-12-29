<?php

/**
 * @Author: nguyen
 * @Date:   2020-12-29 11:18:57
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-12-29 13:11:30
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

class createAdmin extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    /**
     * User model factory
     *
     * @var \Magento\User\Model\UserFactory
     */    
    protected $_userFactory;

    public function launch()
    {
        $this->_userFactory = $this->_objectManager->get('\Magento\User\Model\UserFactory');

        $adminInfo = [
            'username'  => 'hello',
            'firstname' => 'Hello',
            'lastname'  => 'Mage',
            'email'     => 'hello@magepow.com',
            'password'  =>'hello@123',       
            'interface_locale' => 'en_US',
            'is_active' => 1
        ];

        $userModel = $this->_userFactory->create();
        $userModel->setData($adminInfo);
        $userModel->setRoleId(1);
        try{
           $userModel->save(); 
           echo 'done';
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
        return $this->_response;
    }


}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('createAdmin');
$bootstrap->run($app);
