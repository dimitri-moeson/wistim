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
    use User\Translate\TranslateAction;

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
                $factory->createInput(InputFormat::email_validator($this->adapter,"user", true))
            );
    
            # filter and validate password input field
            $inputFilter->add(
                $factory->createInput(InputFormat::pswd_validator())
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
                        //["name" => I18n\Validator\IsInt::class ],
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
                $factory->createInput(InputFormat::text_validator("firstname","Firstname"))
            );
    
            # filter and validate lastname input field
            $inputFilter->add(
                $factory->createInput(InputFormat::text_validator("lastname","Lastname"))
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
                $factory->createInput(InputFormat::email_validator($this->adapter,"user", false ))
            );
    
            # filter and validate password input field
            $inputFilter->add(
                $factory->createInput(InputFormat::pswd_validator())
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
                                    Validator\StringLength::TOO_SHORT => TranslateAction::getInstance()->_(  'confirm Password must have at least 8 characters.'),
                                    Validator\StringLength::TOO_LONG => TranslateAction::getInstance()->_(  'confirm Password must have at most 25 characters.'),
                                ]
                            ]
                        ],
                        [
                            "name" => Validator\Identical::class,
                            "options" =>[
                                "token" => "password" ,
                                "messages" =>[
                                    Validator\Identical::NOT_SAME => TranslateAction::getInstance()->_(   'confirm Password do not match.'),
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