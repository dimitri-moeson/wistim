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
    use User\Form\Auth\InputFormat;
    use User\Model\Table\ClassesTable;
    use User\Translate\TranslateAction;


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
            $this->add( InputFormat::hidden_input("medicament_id",false));
    
            # user_id input field
            $this->add(InputFormat::hidden_input("user_id",true));
            
            # name input field
            $this->add(InputFormat::text_input("name","Name"));
    
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
            $this->add(InputFormat::integer_input("dosage", "Dosage"));
            
            # dci input field
            $this->add(InputFormat::text_input("dci","DCI"));
    
            # classe input field
            $this->add(InputFormat::select("classe_id","Class",$classesTable->fetchAllClasses() ));
            
            # administration input field
            $this->add(InputFormat::select("administration","Route",[
                "Oral" => TranslateAction::getInstance()->_("Oral"),
                "Respiratoire" =>  TranslateAction::getInstance()->_("Respiratoire"),
                "Injection"  =>  TranslateAction::getInstance()->_("Injection"),
            ] ));
           
            
            # unite input field
            $this->add(InputFormat::integer_input("unite","Unity"));
            
            # CSRF field
            $this->add( InputFormat::csrf_input());
    
            # submit button
            $this->add(InputFormat::submit_input('medicament_save', "Record"));
        }
    }