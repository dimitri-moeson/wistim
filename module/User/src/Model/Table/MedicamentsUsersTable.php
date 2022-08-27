<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 27/08/2022
     * Time: 17:13
     */
    
    namespace User\Model\Table;
    
    
    use Laminas\Db\Adapter\Adapter;
    use Laminas\Db\TableGateway\AbstractTableGateway;

    class MedicamentsUsersTable extends AbstractTableGateway
    {
        /**
         * @var \Laminas\Db\Adapter\Adapter  used to connect to the database
         */
        protected $adapter;
    
        /**
         * @var string
         */
        protected $table = 'medicaments_users';
    
        /**
         * MedicamentsTable constructor.
         *
         * @param \Laminas\Db\Adapter\Adapter $adapter
         */
        public function __construct(Adapter $adapter)
        {
            $this->adapter = $adapter;
        
            $this->initialize();
        }
    
        public function fetchMedicamentByIds($Userid,$MedicamentId)
        {
            $sqlQuery = $this->getSql()->select()->where(["user_id" => $Userid, "medicament_id" => $MedicamentId ]);
        
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
        
            $handler = $sqlStmt->execute()->current();
        
            return $handler;
        }
    
        public function saveLink(array $data)
        {
            $sqlQuery = $this->getSql()->insert()->values($data);
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
            return $sqlStmt->execute();
            
        }
    
        public function deleteLink($Userid, $MedicamentId )
        {
            $sqlQuery = $this->getSql()->delete()->where(["user_id" => $Userid, "medicament_id" => $MedicamentId ]);
        
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
        
            return $sqlStmt->execute();
        
        }
    }