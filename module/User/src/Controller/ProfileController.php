<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 19:16
     */
    declare(strict_types=1);
    
    namespace User\Controller;
    
    use Laminas\Authentication\AuthenticationService;
    use Laminas\Db\Adapter\Adapter;
    use Laminas\View\Model\ViewModel;
    use PHPThumb\GD;
    use User\Form\MedicamentForm;
    use User\Model\Table\ClassesTable;
    use User\Model\Table\MedicamentsTable;
    use User\Model\Table\MedicamentsUsersTable;
    use User\Model\Table\UsersTable;
    use User\Translate\TranslateAction;

    class ProfileController extends __GlobalUserController
    {
    
        private $medicamentsTable;
        
        private $medicamentsUsersTable ;
        
        private $classesTable;
    
        public function __construct(Adapter $adapter, UsersTable $usersTable, MedicamentsUsersTable $medicamentsUsersTable , MedicamentsTable $medicamentsTable, ClassesTable $classesTable)
        {
           parent::__construct($adapter,$usersTable);
            $this->medicamentsTable = $medicamentsTable ;
            $this->classesTable = $classesTable ;
            $this->medicamentsUsersTable = $medicamentsUsersTable;
        }
    
        public function indexAction()
        {
            $auth = new AuthenticationService();
    
            if(!$auth->hasIdentity())
                return $this->redirect()->toRoute("home");
            
            if($auth->hasIdentity()){
                
                return new ViewModel([
    
                    "trad" => $this->trad,
                    "medicaments" => $this->medicamentsTable->fetchAllMedicamentForUser($auth->getIdentity()->id)
                
                ]);
            }
        }
    
        public function addAction(){
    
            $auth = new AuthenticationService();
    
            if(!$auth->hasIdentity())
                return $this->redirect()->toRoute("home");
            
            $createForm = new MedicamentForm($this->classesTable);
    
            $formData = [];
            $formData["user_id"] = $auth->getIdentity()->id;
    
            $createForm->setData($formData);
            
            return new ViewModel([
                "trad" => $this->trad,
                'form' => $createForm
            ]);
        }
        
        public function editAction(){
    
            $auth = new AuthenticationService();
    
            if(!$auth->hasIdentity())
                return $this->redirect()->toRoute("home");
    
            
            $formData = $this->medicamentsTable->fetchMedicamentById($this->params('id'));
            $formData["user_id"] = $auth->getIdentity()->id;
    
            $createForm = new MedicamentForm($this->classesTable);
            $createForm->setData($formData);
            
            return new ViewModel([
                "trad" => $this->trad,
                'form' => $createForm,
                'medicament' => $formData
            ]);
        }
    
        public function saveAction()
        {
            $auth = new AuthenticationService();
    
            if(!$auth->hasIdentity())
                return $this->redirect()->toRoute("home");
    
            $createForm = new MedicamentForm($this->classesTable);
    
            $request = $this->getRequest();
    
            if($request->isPost()){
                $formData = $request->getPost()->toArray();
                
                $createForm->setInputFilter($this->medicamentsTable->getCreateFormFilter());
                $createForm->setData($formData);
        
                if($createForm->isValid()){
                    
                    try{
                
                        $data = $createForm->getData();
                    
                        if(file_exists($_FILES['photo']['tmp_name'])){
                            
                            $name = basename($_FILES['photo']['name']);
                            
                            $gd = new GD($_FILES['photo']['tmp_name']);
                            
                            $gd->adaptiveResize(100,100);
                            $gd->save('public/images/'.$name);
    
                            $data["photo"] = $name ;
                        }
    
                        $lastId = $this->medicamentsTable->saveMedic($data);
    
                        if($lastId!== false && $formData['medicament_id']=="") {
                            $this->medicamentsUsersTable->saveLink([
                                "medicament_id" => $this->medicamentsTable->fetchLast()->getMedicamentId(),
                                "user_id" => $auth->getIdentity()->id
                            ]);
                        }
                        
                        $this->flashMessenger()->addSuccessMessage(TranslateAction::getInstance()->_('Medicament successfully created.'));
                        
                    }catch (\RuntimeException $exception){
                
                        $this->flashMessenger()->addErrorMessage($exception->getMessage());
                    }
                }
                else {
                    $this->flashMessenger()->addErrorMessage(print_r($createForm->getMessages(),true));
    
                }
            }
            
            return $this->redirect()->toRoute("profile");
    
        }
        
        public function deleteAction(){
    
            $auth = new AuthenticationService();
    
            if(!$auth->hasIdentity())
                return $this->redirect()->toRoute("home");
    
            $formData = $this->medicamentsTable->fetchMedicamentById($this->params('id'));
            
            if($formData !== false )
            {
                $link = $this->medicamentsUsersTable->fetchMedicamentByIds($auth->getIdentity()->id,$this->params('id'));
    
                if($link !== false )
                {
                    $this->medicamentsUsersTable->deleteLink($auth->getIdentity()->id,$this->params('id'));
                    $this->medicamentsTable->deleteMedic($this->params('id'));
    
                    $this->flashMessenger()->addSuccessMessage(TranslateAction::getInstance()->_('Medicament successfully removed.'));
    
                }
            }
            
            return $this->redirect()->toRoute("profile");
    
        }

    }