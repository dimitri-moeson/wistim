<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 27/08/2022
     * Time: 15:08
     */
    
    namespace User\Model\Table;
    
    
    use Laminas\Db\Adapter\Adapter;
    use Laminas\Db\ResultSet\HydratingResultSet;
    use Laminas\Db\TableGateway\AbstractTableGateway;
    use Laminas\Hydrator\ClassMethodsHydrator;
    use User\Model\Entity\ClasseEntity;

    class ClassesTable extends AbstractTableGateway
    {
        /**
         * @var \Laminas\Db\Adapter\Adapter  used to connect to the database
         */
        protected $adapter;
    
        /**
         * @var string
         */
        protected $table = 'classes';
    
        /**
         * ClassesTable constructor.
         *
         * @param \Laminas\Db\Adapter\Adapter $adapter
         */
        public function __construct(Adapter $adapter)
        {
            $this->adapter = $adapter;
        
            $this->initialize();
        }
        
        public function fetchAllClasses(){
    
            $sqlQuery = $this->getSql()->select();
            
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
    
            $handler = $sqlStmt->execute();
    
            $row = [];
            
            foreach( $handler as $item ){
                
                $row[ $item['id'] ] = $item['name'];
            }
    
            return $row ;
            
        }
    }