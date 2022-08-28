<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 13:20
     */
    declare(strict_types=1);
    
    namespace User\Model\Table;
    
    use Laminas\Crypt\Password\Bcrypt;
    use Laminas\Db\Adapter\Adapter;
    use Laminas\Db\TableGateway\AbstractTableGateway;
    use Laminas\Hydrator\ClassMethodsHydrator;
    use Laminas\InputFilter;
    use Laminas\Filter;
    use Laminas\Validator;
    use Laminas\I18n;
    use User\Form\Auth\InputFormat;
    use User\Model\Entity\UserEntity;

    class UsersTable extends AbstractTableGateway
    {
        /**
         * @var \Laminas\Db\Adapter\Adapter  used to connect to the database
         */
        protected $adapter;
    
        /**
         * @var string
         */
        protected $table = 'users';
    
        /**
         * UsersTable constructor.
         *
         * @param \Laminas\Db\Adapter\Adapter $adapter
         */
        public function __construct(Adapter $adapter)
        {
            $this->adapter = $adapter;
            
            $this->initialize();
        }
    
    
        public function fetchAccountByEmail(string $email)
        {
            $sqlQuery = $this->getSql()->select()->where(["email" => $email]);
            
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
            
            $handler = $sqlStmt->execute()->current();
            
            if(!$handler) return null ;
            
            $classMethod = new ClassMethodsHydrator();
            $entity = new  UserEntity();
            $classMethod->hydrate($handler,$entity);
            
            return $entity;
            
        }
        /**
         * @return \Laminas\InputFilter\InputFilter
         */
        public function getLoginFormFilter()
        {
            $inputFilter = new InputFilter\InputFilter();
            $factory = new InputFilter\Factory();
    
            # filter and validate email input field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "email",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                        ["name" => Filter\StringToLower::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        ["name" => Validator\EmailAddress::class ],
                        [
                            "name" => Validator\StringLength::class,
                            "options" =>[
                                "min" => 8 ,
                                "max" => 128,
                                "messages" =>[
                                    Validator\StringLength::TOO_SHORT => 'Email must have at least 8 characters.',
                                    Validator\StringLength::TOO_LONG => 'Email must have at most 128 characters.'
                                ]
                            ]
                        ],
                        [
                            "name" => Validator\Db\RecordExists::class,
                            "options" =>[
                                "adapter"=>$this->adapter,
                                "table"=> $this->table,
                                "field" => "email"
                            ],
                        ],
                    ],
                ])
            );
    
            # filter and validate password input field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "password",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        [
                            "name" => Validator\StringLength::class,
                            "options" =>[
                                "min" => 8 ,
                                "max" => 25,
                                "messages" =>[
                                    Validator\StringLength::TOO_SHORT => 'Password must have at least 8 characters.',
                                    Validator\StringLength::TOO_LONG => 'Password must have at most 25 characters.'
                                ]
                            ]
                        ],
                    ],
                ])
            );
    
            # filter and validate recall checkbox field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "recall",
                    "required" => false,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                        ["name" => Filter\ToInt::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        ["name" => I18n\Validator\IsInt::class ],
                        [
                            "name" => Validator\InArray::class,
                            "options" =>[
                                "haystack" => [0,1] ,
                            ]
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
        
        /**
         * sanitizes form data
         */
        public function getCreateFormFilter()
        {
            $inputFilter = new InputFilter\InputFilter();
            $factory = new InputFilter\Factory();
            
            # filter and validate firstname input field
            $inputFilter->add(
                $factory->createInput([
                    
                    "name" => "firstname",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                        ["name" => I18n\FIlter\Alnum::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        [
                            "name" => Validator\StringLength::class,
                            "options" =>[
                            "min" => 8 ,
                            "max" => 25,
                            "messages" =>[
                                    Validator\StringLength::TOO_SHORT => 'Firstname must have at least 8 characters.',
                                    Validator\StringLength::TOO_LONG => 'Firstname must have at most 25 characters.'
                               ]
                            ]
                        ],
                        [
                            "name" => I18n\Validator\Alnum::class,
                            "options" => [
                                "messages" =>[
                                        I18n\Validator\Alnum::NOT_ALNUM => "Firstname must consist of alphanumeric character only"
                                ]
                            ]
                        ],
                        [
                            "name" => Validator\Db\NoRecordExists::class,
                            "options" =>[
                                "adapter"=>$this->adapter,
                                "table"=> $this->table,
                                "field" => "firstname"
                            ],
                        ],
                    ],
                ])
            );
    
            # filter and validate lastname input field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "lastname",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                        ["name" => I18n\FIlter\Alnum::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        [
                            "name" => Validator\StringLength::class,
                            "options" =>[
                                "min" => 8 ,
                                "max" => 25,
                                "messages" =>[
                                    Validator\StringLength::TOO_SHORT => 'Lastname must have at least 8 characters.',
                                    Validator\StringLength::TOO_LONG => 'Lastname must have at most 25 characters.'
                                ]
                            ]
                        ],
                        [
                            "name" => I18n\Validator\Alnum::class,
                            "options" => [
                                "messages" =>[
                                    I18n\Validator\Alnum::NOT_ALNUM => "Lastname must consist of alphanumeric character only"
                                ]
                            ]
                        ],
                        [
                            "name" => Validator\Db\NoRecordExists::class,
                            "options" =>[
                                "adapter"=>$this->adapter,
                                "table"=> $this->table,
                                "field" => "lastname"
                            ],
                        ],
                    ],
                ])
            );
    
            # filter and validate birthday select field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "birthday",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        [
                            "name" => Validator\Date::class,
                            "options" =>[
                                "format" => "Y-m-d" ,
                            ]
                        ],
                    ],
                ])
            );
            
            # filter and validate email input field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "email",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                        ["name" => Filter\StringToLower::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        ["name" => Validator\EmailAddress::class ],
                        [
                            "name" => Validator\StringLength::class,
                            "options" =>[
                                "min" => 8 ,
                                "max" => 128,
                                "messages" =>[
                                    Validator\StringLength::TOO_SHORT => 'Email must have at least 8 characters.',
                                    Validator\StringLength::TOO_LONG => 'Email must have at most 128 characters.'
                                ]
                            ]
                        ],
                        [
                            "name" => Validator\Db\NoRecordExists::class,
                            "options" =>[
                                "adapter"=>$this->adapter,
                                "table"=> $this->table,
                                "field" => "email"
                            ],
                        ],
                    ],
                ])
            );
    
            # filter and validate password input field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "password",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        [
                            "name" => Validator\StringLength::class,
                            "options" =>[
                                "min" => 8 ,
                                "max" => 25,
                                "messages" =>[
                                    Validator\StringLength::TOO_SHORT => 'Password must have at least 8 characters.',
                                    Validator\StringLength::TOO_LONG => 'Password must have at most 25 characters.'
                                ]
                            ]
                        ],
                    ],
                ])
            );
    
            # filter and validate confirm password input field
            $inputFilter->add(
                $factory->createInput([
            
                    "name" => "conf_password",
                    "required" => true,
                    "filters" => [
                        ["name" => Filter\StripTags::class ],
                        ["name" => Filter\StringTrim::class ],
                    ],
                    "validator" => [
                        ["name" => Validator\NotEmpty::class ],
                        [
                            "name" => Validator\StringLength::class,
                            "options" =>[
                                "min" => 8 ,
                                "max" => 25,
                                "messages" =>[
                                    Validator\StringLength::TOO_SHORT => 'confirm Password must have at least 8 characters.',
                                    Validator\StringLength::TOO_LONG => 'confirm Password must have at most 25 characters.'
                                ]
                            ]
                        ],
                        [
                            "name" => Validator\Identical::class,
                            "options" =>[
                                "token" => "password" ,
                                "messages" =>[
                                    Validator\Identical::NOT_SAME => 'confirm Password do not match.',
                                ]
                            ]
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
    
        public function saveAccount(array $data)
        {
          
            $timeNow = date("Y-m-d H:i:s");
            $values = [
                "firstname" => ucfirst( $data["firstname"]) ,
                "lastname" => ucfirst( $data["lastname"]) ,
                "birthday" => $data["birthday"],
                "email"  => mb_strtolower( $data["email"]) ,
                "password" => (new Bcrypt())->create($data["password"]),
                "created" => $timeNow,
                "modified" => $timeNow,
            ];
            
            $sqlQuery = $this->getSql()->insert()->values($values);
            $sqlStmt = $this->getSql()->prepareStatementForSqlObject($sqlQuery);
            
            return $sqlStmt->execute();
        }
    }