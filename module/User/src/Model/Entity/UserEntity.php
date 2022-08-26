<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 18:19
     */
    declare(strict_types=1);
    
    namespace User\Model\Entity;
    
    
    class UserEntity
    {
        protected $id;
        protected $firstname;
        protected $lastname;
        protected $birthday;
        protected $email;
        protected $password;
        protected $created;
        protected $modified;
    
        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }
    
        /**
         * @return mixed
         */
        public function getFirstname()
        {
            return $this->firstname;
        }
    
        /**
         * @return mixed
         */
        public function getLastname()
        {
            return $this->lastname;
        }
    
        /**
         * @return mixed
         */
        public function getBirthday()
        {
            return $this->birthday;
        }
    
        /**
         * @return mixed
         */
        public function getEmail()
        {
            return $this->email;
        }
    
        /**
         * @return mixed
         */
        public function getPassword()
        {
            return $this->password;
        }
    
        /**
         * @return mixed
         */
        public function getCreated()
        {
            return $this->created;
        }
    
        /**
         * @return mixed
         */
        public function getModified()
        {
            return $this->modified;
        }
    
        /**
         * @param mixed $firstname
         *
         * @return UserEntity
         */
        public function setFirstname($firstname)
        {
            $this->firstname = $firstname;
            return $this;
        }
    
        /**
         * @param mixed $lastname
         *
         * @return UserEntity
         */
        public function setLastname($lastname)
        {
            $this->lastname = $lastname;
            return $this;
        }
    
        /**
         * @param mixed $birthday
         *
         * @return UserEntity
         */
        public function setBirthday($birthday)
        {
            $this->birthday = $birthday;
            return $this;
        }
    
        /**
         * @param mixed $email
         *
         * @return UserEntity
         */
        public function setEmail($email)
        {
            $this->email = $email;
            return $this;
        }
    
        /**
         * @param mixed $password
         *
         * @return UserEntity
         */
        public function setPassword($password)
        {
            $this->password = $password;
            return $this;
        }
    
        /**
         * @param mixed $created
         *
         * @return UserEntity
         */
        public function setCreated($created)
        {
            $this->created = $created;
            return $this;
        }
    
        /**
         * @param mixed $modified
         *
         * @return UserEntity
         */
        public function setModified($modified)
        {
            $this->modified = $modified;
            return $this;
        }
    
    }