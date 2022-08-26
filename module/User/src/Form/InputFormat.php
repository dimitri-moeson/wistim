<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 18:07
     */
    namespace User\Form ;
    
    use Laminas\Filter;
    use Laminas\Validator;
    
    class InputFormat
    {
        public function csrf_validator()
        {
            return [
    
                "name" => "csrf",
                "required" => true,
                "filters" => [
                    ["name" => Filter\StripTags::class ],
                    ["name" => Filter\StringTrim::class ],
                ],
                "validator" => [
                    ["name" => Validator\NotEmpty::class ],
                    [
                        "name" => Validator\Csrf::class,
                        "options" =>[
                            "messages" =>[
                                Validator\Csrf::NOT_SAME => 'Oops ! Refill the form.',
                            ]
                        ]
                    ],
                ],
            ];
        }
        
        public function email_validator($exist = false)
        {
            return [
    
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
                        "name" =>  $exist ? Validator\Db\RecordExists::class  : Validator\Db\NoRecordExists::class,
                        "options" =>[
                            "adapter"=>$this->adapter,
                            "table"=> $this->table,
                            "field" => "email"
                        ],
                    ],
                ],
            ];
        }
    }