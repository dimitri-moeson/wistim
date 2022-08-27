<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 27/08/2022
     * Time: 15:08
     */
    
    namespace User\Model\Entity;
    
    
    class ClasseEntity
    {
        protected $id;
        protected $name;
    
        /**
         * @return mixed
         */
        public function getName()
        {
            return $this->name;
        }
    
        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }
    
        /**
         * @param mixed $id
         *
         * @return ClasseEntity
         */
        public function setId($id)
        {
            $this->id = $id;
            return $this;
        }
    
        /**
         * @param mixed $name
         *
         * @return ClasseEntity
         */
        public function setName($name)
        {
            $this->name = $name;
            return $this;
        }
    
    }