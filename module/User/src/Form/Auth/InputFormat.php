<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 18:07
     */
    namespace User\Form\Auth ;
    
    use Laminas\Db\Adapter\Adapter;
    use Laminas\Filter;
    use Laminas\Validator;
    use Laminas\Form\Element;
    use User\Translate\TranslateAction;

    class InputFormat
    {
        public static function  hidden_input($name, $required = true){
            
            return [
                "type" => Element\Hidden::class ,
                "name" => $name,
                "attributes" => [
                    "required" => $required ,
                ]
            ];
        }
    
        public static function text_input($name,$label)
        {
            return [
                "type" => Element\Text::class ,
                "name" => $name,
                "options" => [
                    "label" => TranslateAction::getInstance()->_($label)
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "pattern" => '^[a-zA-z0-9]+$',
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" =>  TranslateAction::getInstance()->_("$label must consist of alphanumeric characters only"),
                    "placeholder" =>  TranslateAction::getInstance()->_("Enter $label")
                ]
            ];
        }
    
        public static function integer_input($name,$label)
        {
            return [
                "type" => Element\Number::class ,
                "name" => $name,
                "options" => [
                    "label" =>  TranslateAction::getInstance()->_($label)
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "pattern" => '^[a-zA-z0-9]+$',
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => TranslateAction::getInstance()->_("$label must consist of alphanumeric characters only"),
                    "placeholder" => TranslateAction::getInstance()->_("Enter $label")
                ]
            ];
        }
        
        public static function email_input()
        {
            return [
                "type" => Element\Email::class ,
                "name" => "email",
                "options" => [
                    "label" => TranslateAction::getInstance()->_("Email address"),
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 128,
                    "pattern" => '^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$',
                    "autocomplete" => false ,
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" =>  TranslateAction::getInstance()->_("Provide a valid and working email"),
                    "placeholder" =>  TranslateAction::getInstance()->_("Enter Email")
                ]
            ];
        }
    
        public static function pswd_input($name)
        {
            return [
                "type" => Element\Password::class ,
                "name" => $name,
                "options" => [
                    "label" => TranslateAction::getInstance()->_("Password"),
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "autocomplete" => false ,
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => TranslateAction::getInstance()->_("Password must have between 8 and 25 characters"),
                    "placeholder" => TranslateAction::getInstance()->_("Enter Password")
                ]
            ];
        }
    
        public static function select($name,$label,$values)
        {
            return [
                "type" => Element\Select::class ,
                "name" => $name,
                "options" => [
                    "label" =>  TranslateAction::getInstance()->_("Select ".$label) ,
                    "create_empty_option" => true,
                    "empty_option" => TranslateAction::getInstance()->_("Select ..."),
                    "value_options" => $values
                ],
                "attributes" => [
                    "required" => true ,
                    "class" => "custom-select"
                ]
            ];
        }
    
        public static function submit_input($name, $label)
        {
            return [
                "type" => Element\Submit::class,
                "name" => $name,
                "attributes" => [
                    "value" =>  TranslateAction::getInstance()->_("".$label),
                    "class" => "btn btn-primary"
                ],
            ];
        }
       
        public static function csrf_input()
        {
            return [
                "type" => Element\Csrf::class ,
                "name" => "csrf",
                "options" => [
                    "csrf_options" => [
                        "timeout" => 300,
                    ]
                ],
            ] ;
        }
        
        public static function csrf_validator()
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
                                Validator\Csrf::NOT_SAME => TranslateAction::getInstance()->_( 'Oops ! Refill the form.'),
                            ]
                        ]
                    ],
                ],
            ];
        }
        
        public static function email_validator(Adapter $adapter,$table, $exist = false)
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
                                Validator\StringLength::TOO_SHORT => TranslateAction::getInstance()->_('Email must have at least 8 characters.'),
                                Validator\StringLength::TOO_LONG => TranslateAction::getInstance()->_('Email must have at most 128 characters.')
                            ]
                        ]
                    ],
                    [
                        "name" =>  $exist ? Validator\Db\RecordExists::class  : Validator\Db\NoRecordExists::class,
                        "options" =>[
                            "adapter"=>$adapter,
                            "table"=> $table,
                            "field" => "email"
                        ],
                    ],
                ],
            ];
        }
    }