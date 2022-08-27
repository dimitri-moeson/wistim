<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 27/08/2022
     * Time: 14:07
     */
    
    namespace User\Form;
    
    use Laminas\Form\Form;
    use Laminas\Form\Element;
    use User\Model\Table\ClassesTable;


    class MedicamentForm extends Form
    {
        public function __construct(ClassesTable $classesTable )
        {
            parent::__construct("new_account");
        
            $this->setAttributes([
                "method" => "post",
                "enctype" => "multipart/form-data"
            ]);
    
            # medicament_id input field
            $this->add([
                "type" => Element\Hidden::class ,
                "name" => "medicament_id",
                "attributes" => [
                    "required" => false ,
                ]
            ]);
    
            # user_id input field
            $this->add([
                "type" => Element\Hidden::class ,
                "name" => "user_id",
                "attributes" => [
                    "required" => true ,
                ]
            ]);
            
            # name input field
            $this->add([
                "type" => Element\Text::class ,
                "name" => "name",
                "options" => [
                    "label" => "Name"
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "pattern" => '^[a-zA-z0-9]+$',
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => "Name must consist of alphanumeric characters only",
                    "placeholder" => "Enter  your lastname"
                ]
            ]);
    
            # photo input field
            $this->add([
                "type" => Element\File::class ,
                "name" => "photo",
                "options" => [
                    "label" => "Photo",
                   /* "label_attributes" =>[
                        "class" => "custom-file-label"
                    ]*/
                ],
                "attributes" => [
                    "required" => false ,
                    "class" => "form-control",
                    "id" => "photo",
                    "multiple" => false
                ]
            ]);
    
            # dosage input field
            $this->add([
                "type" => Element\Number::class ,
                "name" => "dosage",
                "options" => [
                    "label" => "Dosage"
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "pattern" => '^[a-zA-z0-9]+$',
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => "Name must consist of alphanumeric characters only",
                    "placeholder" => "Enter  your lastname"
                ]
            ]);
            
            /**
             *  protected $id;
            protected $dci;
             */
    
            # classe input field
            $this->add([
                "type" => Element\Select::class ,
                "name" => "classe_id",
                "options" => [
                    "label" => "select classe",
                    "create_empty_option" => true,
                    "empty_option" => "Select...",
                    "value_options" => $classesTable->fetchAllClasses(),
                ],
                "attributes" => [
                    "required" => true ,
                    "class" => "custom-select"
                ]
            ]);
            
            # administration input field
            $this->add([
                "type" => Element\Select::class ,
                "name" => "administration",
                "options" => [
                    "label" => "select administration",
                    "create_empty_option" => true,
                    "empty_option" => "Select...",
                    "value_options" =>[
                        "oral" => "Oral",
                        "respiratoire" => "respiratoire",
                        "injection"  => "injection",
                    ]
                ],
                "attributes" => [
                    "required" => true ,
                    "class" => "custom-select"
                ]
            ]);
            
            # unite input field
            $this->add([
                "type" => Element\Number::class ,
                "name" => "unite",
                "options" => [
                    "label" => "Unité"
                ],
                "attributes" => [
                    "required" => true ,
                    "size" => 40 ,
                    "maxlength" => 25,
                    "data-toggle" => 'tooltip',
                    "class" => "form-control",
                    "title" => "Name must consist of numeric characters only",
                    "placeholder" => "Enter your unity"
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