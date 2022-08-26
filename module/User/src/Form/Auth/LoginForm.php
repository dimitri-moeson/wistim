<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 16:44
     */
    declare(strict_types=1);
    
    namespace User\Form\Auth;
    
    use Laminas\Form\Form;
    use Laminas\Form\Element;
    
    class LoginForm extends Form
    {
        public function __construct()
        {
            parent::__construct("sign_in");
        
            $this->setAttribute("method", "post");
            
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
            
            #remember me checkbox
            $this->add([
                "type" => Element\Checkbox::class ,
                "name" => "recall",
                "options" => [
                    "label" => "Remember me?",
                    "label_attributes" => [
                        "class" => "custom-control-label"
                    ],
                    "use_hidden_element" => true,
                   // "checked_value" => 1,
                   // "unchecked_value" => 0,
                ],
                "attributes" => [
                    "value" => 0,
                    "id" => "recall",
                    "class" => "custom-control-input"
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
                "name" => 'login_account',
                "attributes" => [
                    "value" => "Sign-In",
                    "class" => "btn btn-primary"
                ],
            ]);
        }
    }