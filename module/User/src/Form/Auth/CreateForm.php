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
    use User\Translate\TranslateAction;

    class CreateForm extends Form
    {
        /**
         * CreateForm constructor.
         */
        public function __construct()
        {
            parent::__construct("new_account");
            
            $this->setAttribute("method","post");
           
            
            # firstname input field
            $this->add(InputFormat::text_input("firstname","Firstname"));
            
            # lastname input field
            $this->add(InputFormat::text_input("lastname","Lastname"));
            
              # birthday input field
            $this->add([
                "type" => Element\DateSelect::class ,
                "name" => "birthday",
                "options" => [
                    "label" =>TranslateAction::getInstance()->_( "Select Birthday"),
                    "create_empty_option" => true,
                    "max_year" => date("Y") - 13 ,
                    "year_attributes" => [
                        "class" => "custom-select w-30",
                    ],
                    "month_attributes" => [
                        "class" => "custom-select w-30",
                        "locale" => TranslateAction::getInstance()->getLocale() == "fr" ? "fr_FR" : "en_GB",
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
            $this->add(InputFormat::email_input());
            
            # Password input field
            $this->add(InputFormat::pswd_input("password"));
            
             # confirm Password input field
            $this->add([
                "type" => Element\Password::class ,
                "name" => "conf_password",
                "options" => [
                    "label" => TranslateAction::getInstance()->_("Verify your Password")
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "autocomplete" => false ,
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" =>  TranslateAction::getInstance()->_("Password must match that provided above"),
                    "placeholder" =>  TranslateAction::getInstance()->_("Enter your Password again")
                ]
            ]);
    
            # CSRF field
           $this->add( InputFormat::csrf_input());
           
           # submit button
            $this->add(InputFormat::submit_input('create_account', "Create Account"));
        }
    }