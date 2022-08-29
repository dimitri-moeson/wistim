<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 17:01
     */
    declare(strict_types=1);
    
    namespace User\Controller;
    
    use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
    use Laminas\Authentication\AuthenticationService;
    use Laminas\Authentication\Result;
    use Laminas\Crypt\Password\Bcrypt;
    use Laminas\Session\SessionManager;
    use Laminas\View\Model\ViewModel;
    use User\Form\Auth\LoginForm;
    use User\Translate\TranslateAction;

    class LoginController extends __GlobalUserController
    {
        public function indexAction()
        {
            $auth = new AuthenticationService();
    
            if($auth->hasIdentity())
                return $this->redirect()->toRoute("home");
            
            $loginForm = new LoginForm();
    
            $request = $this->getRequest();
    
            if($request->isPost()){
                $formData = $request->getPost()->toArray();
                $loginForm->setInputFilter($this->usersTable->getLoginFormFilter());
                $loginForm->setData($formData);
        
                if($loginForm->isValid()){
                    try{
                        
                        $authAdapter = new CredentialTreatmentAdapter($this->adapter);
                        $authAdapter->setTableName($this->usersTable->getTable())
                            ->setIdentityColumn("email")
                            ->setCredentialColumn("password")
                            ->getDbSelect()//->where(["active" => 1])
                        ;
                
                        $data = $loginForm->getData();
                        
                        $authAdapter->setIdentity($data["email"]);
                        
                        # email in Database
                        $info = $this->usersTable->fetchAccountByEmail($data["email"]);
                        
                        #password hashing
                        $hash = new Bcrypt();
                        
                        # compare form pswd with pswd in database
                        if($hash->verify($data["password"],$info->getPassword())){
                            $authAdapter->setCredential($info->getPassword());
                        }else {
                            $authAdapter->setCredential('');
                        }
                        
                        $authResult = $auth->authenticate($authAdapter);
                
                        switch ($authResult->getCode()){
                            
                            case Result::FAILURE_IDENTITY_NOT_FOUND :
                                $this->flashMessenger()->addErrorMessage(TranslateAction::getInstance()->_("Unknow email"));
                                return $this->redirect()->refresh();
                                break;
    
                            case Result::FAILURE_CREDENTIAL_INVALID :
                                $this->flashMessenger()->addErrorMessage(TranslateAction::getInstance()->_("Incorrect password"));
                                return $this->redirect()->refresh();
                                break;
    
                            case Result::SUCCESS :
                                
                                if($data["recall"] == 1){
                                    $ssm = new SessionManager();
                                    $ttl = 1814400; # 21 jours
                                    $ssm->rememberMe($ttl);
                                }
                                
                                $storage = $auth->getStorage();
                                $storage->write($authAdapter->getResultRowObject(null,['created','modified']));
                                
                                $this->flashMessenger()->addSuccessMessage(TranslateAction::getInstance()->_('Account successfully auth.'));
                                return $this->redirect()->toRoute("profile",[
                                    'id' => $info->getId(),
                                    'firstname' => mb_strtolower($info->getFirstname())
                                ]);
                                break;
                            
                            default :
                                $this->flashMessenger()->addErrorMessage(TranslateAction::getInstance()->_("Authentification failed").print_r($authResult->getMessages(),true ));
                                return $this->redirect()->refresh();
    
                                break;
                        }
                        
                
                    }catch (\RuntimeException $exception){
                
                        $this->flashMessenger()->addErrorMessage($exception->getMessage());
                        return $this->redirect()->refresh();
                    }
                }
            }
    
            return (new ViewModel([
                
                "trad" => $this->trad,
                "form" => $loginForm
                
            ]))->setTemplate("user/auth/login");
        }
    }