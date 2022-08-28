<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 27/08/2022
     * Time: 11:52
     */
    
    namespace User\Model\Table;
    
    use Laminas\Db\Adapter\Adapter;
    use Laminas\Db\ResultSet\HydratingResultSet;
    use Laminas\Db\TableGateway\AbstractTableGateway;
    use Laminas\Hydrator\ClassMethodsHydrator;
    use User\Form\Auth\InputFormat;
    use User\Model\Entity\MedicamentEntity;

    use Laminas\InputFilter;
    use Laminas\Filter;
    use Laminas\Validator;
    use Laminas\I18n;
    
    class MedicamentsTable extends AbstractTableGateway
    {
        /**
         * @var \Laminas\Db\Adapter\Adapter  used to connect to the database
         */
        protected $adapter;
    
        /**
         * @var string
         */
        protected $table = 'medicaments';
    
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
    
        /**
         * @param $userId
         */
        public function fetchAllMedicamentForUser($userId)
        {
            $sqlQuery = $this->getSql()->select()
                ->join("medicaments_users","medicaments_users.medicament_id = ".$this->table.'.medicament_id')//,['medicament_id','medicament_id'])
                ->where(["medicaments_users.user_id" => $userId]);
            
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
    
            $handler = $sqlStmt->execute();
            
            $classMethod = new ClassMethodsHydrator();
            $entity = new  MedicamentEntity();
            
            $resultSet = new HydratingResultSet($classMethod,$entity);
            $resultSet->initialize($handler);
    
            return $resultSet;
        }
    
        public function fetchMedicamentById($id)
        {
            $sqlQuery = $this->getSql()->select()->where(["medicament_id" => $id]);
            
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
        
            $handler = $sqlStmt->execute()->current();
        
            return $handler;
        }
    
        public function fetchLast()
        {
            $sqlQuery = $this->getSql()->select()->order("medicament_id DESC");
        
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
        
            $handler = $sqlStmt->execute()->current();
        
            if(!$handler) return null ;
        
            $classMethod = new ClassMethodsHydrator();
            $entity = new  MedicamentEntity();
            $classMethod->hydrate($handler,$entity);
        
            return $entity;
        
        }
        
        public function getCreateFormFilter()
        {
            $inputFilter = new InputFilter\InputFilter();
            $factory = new InputFilter\Factory();
    
            # filter and validate name input field
            $inputFilter->add(
                $factory->createInput(InputFormat::text_validator("name","Name"))
            );
            
            # filter and validate photo input field
            $inputFilter->add(
                $factory->createInput([
                    
                    "name" => "photo",
                    "required" => false,
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        ["name" => Validator\File\IsImage::class ],
                        [
                            "name" => Validator\File\MimeType::class,
                            "options" => [
                                "mimeType" => "image/png, image/jpeg, image/jpg, image/gif"
                            ]
                        ],
                        [
                            "name" => Validator\File\Size::class,
                            "options" => [
                                "min" => "3kB",
                                "max" => "15MB"
                            ]
                        ],
                    ],
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                        [
                            "name" => Filter\File\RenameUpload::class,
                            "options" => [
                                "target" => 'public/images/temporary',
                                "use_upload_name" => false,
                                "use_upload_extension" => true,
                                "overwrite" => false,
                                "randomize" => true,
                            ],
                        ],
                    ],
                ])
            );
    
            # filter and validate classe_id input field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "classe_id",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                        ["name" => Filter\ToInt::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        ["name" => I18n\Validator\IsInt::class ],
                        [
                            "name" => Validator\Db\RecordExists::class,
                            "options" =>[
                                "adapter"=>$this->adapter,
                                "table"=> "classes",
                                "field" => "id"
                            ],
                        ],
                    ],
                ])
            );
    
            # filter and validate csrf hidden field
            $inputFilter->add(
                $factory->createInput(InputFormat::csrf_validator())
            );
            
            
            return $inputFilter ;
        }
    
        public function saveMedic(array $data )
        {
            $values = [
                "name" => ucfirst( $data["name"]) ,
                "classe_id" => $data["classe_id"],
                "administration" => $data["administration"],
                "dosage" => $data["dosage"],
                "dci" => $data["dci"],
                "unite" => $data["unite"],
            ];
            
            if(isset($data['photo'])) $values["photo"] = $data["photo"];
            
            if($data["medicament_id"] =="") {
                $sqlQuery = $this->getSql()->insert()->values($values);
            } else {
                $sqlQuery = $this->getSql()->update()->set($values)->where(["medicament_id" =>  $data['medicament_id'] ]);
            }
            
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
        
            $sqlStmt->execute();
            
            if($data["medicament_id"] =="") return true ;
            
            return false ;
        }
    
        public function deleteMedic($id)
        {
            $sqlQuery = $this->getSql()->delete()->where(["medicament_id" => $id]);
        
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
        
            return $sqlStmt->execute();
        }
        
    }