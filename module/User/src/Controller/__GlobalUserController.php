<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 28/08/2022
     * Time: 11:55
     */
    
    namespace User\Controller;
    
    
    use Laminas\Authentication\AuthenticationService;
    use Laminas\Db\Adapter\Adapter;
    use Laminas\Mvc\Controller\AbstractActionController;
    use User\Translate\TranslateAction;
    use User\Model\Table\UsersTable;

    class __GlobalUserController extends AbstractActionController
    {
        protected $usersTable;
        protected $adapter;
    
        public function __construct(Adapter $adapter = null , UsersTable $usersTable = null )
        {
            $this->adapter = $adapter;
            $this->usersTable = $usersTable;
        
            $this->trad = TranslateAction::getInstance();
            $this->auth = new AuthenticationService();
        }
        
        
    
    }