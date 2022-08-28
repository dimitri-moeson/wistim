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
    use User\Translate\TranslateAction;

    class LoginForm extends Form
    {
        public function __construct()
        {
            parent::__construct("sign_in");
        
            $this->setAttribute("method", "post");
            
            # email input field
            $this->add(InputFormat::email_input());
    
            # Password input field
            $this->add(InputFormat::pswd_input("password"));
            
            #remember me checkbox
            $this->add([
                "type" => Element\Checkbox::class ,
                "name" => "recall",
                "options" => [
                    "label" => TranslateAction::getInstance()->_("Remember me?"),
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
            $this->add( InputFormat::csrf_input());
    
            # submit button
            $this->add(InputFormat::submit_input('login_account', "Sign In"));
            
        }
    }