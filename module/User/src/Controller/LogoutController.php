<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 19:54
     */
    declare(strict_types=1);
    
    namespace User\Controller;
    
    class LogoutController  extends __GlobalUserController
    {
        public function indexAction()
        {
            if($this->auth->hasIdentity()){
            
                $this->auth->clearIdentity();
            }
            
            return $this->redirect()->toRoute("sign-in");
        }
    }