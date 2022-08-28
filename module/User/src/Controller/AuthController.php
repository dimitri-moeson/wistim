<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 25/08/2022
     * Time: 13:00
     */
    
    declare(strict_types=1);
    
    namespace User\Controller;
    
    use Laminas\View\Model\ViewModel;
    use User\Form\Auth\CreateForm;
    use User\Translate\TranslateAction;

    class AuthController extends __GlobalUserController
    {
        /**
         * only visitors with no session access this page
         *
         * @return \Laminas\View\Model\ViewModel
         */
        public function createAction()
        {
            
            if($this->auth->hasIdentity())
                return $this->redirect()->toRoute("home");
            
            $createForm = new CreateForm();
            
            $request = $this->getRequest();
            
            if($request->isPost()){
                $formData = $request->getPost()->toArray();
                $createForm->setInputFilter($this->usersTable->getCreateFormFilter());
                $createForm->setData($formData);
                
                if($createForm->isValid()){
                    try{
                        
                        $data = $createForm->getData();
                        $this->usersTable->saveAccount($data);
                        
                        $this->flashMessenger()->addSuccessMessage(TranslateAction::getInstance()->_('Account successfully created.'));
    
                        return $this->redirect()->toRoute("sign-in");
                        
                    }catch (\RuntimeException $exception){
    
                        $this->flashMessenger()->addErrorMessage($exception->getMessage());
                        return $this->redirect()->refresh();
                    }
                }
            }
            
            return new ViewModel([
                'form' => $createForm,
                "trad" => $this->trad,
            ]);
        }
    }