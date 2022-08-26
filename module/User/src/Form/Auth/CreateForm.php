<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 11:18
     */
    declare(strict_types=1);
    
    namespace User\Form\Auth;
    
    use Laminas\Form\Form;
    use Laminas\Form\Element;

    class CreateForm extends Form
    {
        public function __construct()
        {
            parent::__construct("new_account");
            
            $this->setAttribute("method","post");
            
            # firstname input field
            $this->add([
                "type" => Element\Text::class ,
                "name" => "firstname",
                "options" => [
                    "label" => "Firstname"
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "pattern" => '^[a-zA-z0-9]+$',
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => "Firstname must consist of alphanumeric characters only",
                    "placeholder" => "Enter  your Firstname"
                ]
            ]);
            
            # lastname input field
            $this->add([
                "type" => Element\Text::class ,
                "name" => "lastname",
                "options" => [
                    "label" => "Lastname"
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "pattern" => '^[a-zA-z0-9]+$',
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => "lastname must consist of alphanumeric characters only",
                    "placeholder" => "Enter  your lastname"
                ]
            ]);
            
              # birthday input field
            $this->add([
                "type" => Element\DateSelect::class ,
                "name" => "birthday",
                "options" => [
                    "label" => "select birthday",
                    "create_empty_option" => true,
                    "max_year" => date("Y") - 13 ,
                    "year_attributes" => [
                        "class" => "custom-select w-30",
                    ],
                    "month_attributes" => [
                        "class" => "custom-select w-30",
                    ],
                    "day_attributes" => [
                        "class" => "custom-select w-30",
                        "id" => "day"
                    ],
                ],
                "attributes" => [
                    "required" => true ,
                ]
            ]);
            
            # email input field
            $this->add([
                "type" => Element\Email::class ,
                "name" => "email",
                "options" => [
                    "label" => "Email address"
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 128,
                    "pattern" => '^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$',
                    "autocomplete" => false ,
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => "Provide a valid and working email",
                    "placeholder" => "Enter  your Email"
                ]
            ]);
            
            # Password input field
            $this->add([
                "type" => Element\Password::class ,
                "name" => "password",
                "options" => [
                    "label" => "Password"
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "autocomplete" => false ,
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => "Password must have between 8 and 25 characters",
                    "placeholder" => "Enter  your Password"
                ]
            ]);
            
             # confirm Password input field
            $this->add([
                "type" => Element\Password::class ,
                "name" => "conf_password",
                "options" => [
                    "label" => "verify your Password"
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "autocomplete" => false ,
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => "Password must match that provided above",
                    "placeholder" => "Enter  your Password again"
                ]
            ]);
    
            # CSRF field
           $this->add([
               "type" => Element\Csrf::class ,
               "name" => "csrf",
               "options" => [
                   "csrf_options" => [
                       "timeout" => 300,
                   ]
               ],
           ]);
           
           # submit button
            $this->add([
                "type" => Element\Submit::class,
                "name" => 'create_account',
                "attributes" => [
                    "value" => "Create Account",
                    "class" => "btn btn-primary"
                ],
            ]);
        }
    }