<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 27/08/2022
     * Time: 11:54
     */
    
    namespace User\Model\Entity;
    
    
    class MedicamentEntity
    {
        /**
         * @var
         */
        protected $medicament_id;
        protected $photo;
        protected $classe_id;
        protected $dci;
        protected $name;
        protected $administration;
        protected $dosage;
        protected $unite;
    
    
        /**
         * @return mixed
         */
        public function getMedicamentId()
        {
            return $this->medicament_id;
        }
    
    
        /**
         * @return mixed
         */
        public function getPhoto()
        {
            return $this->photo;
        }
    
        /**
         * @return mixed
         */
        public function getDci()
        {
            return $this->dci;
        }
    
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
        public function getAdministration()
        {
            return $this->administration;
        }
    
        /**
         * @return mixed
         */
        public function getDosage()
        {
            return $this->dosage;
        }
    
        /**
         * @return mixed
         */
        public function getUnite()
        {
            return $this->unite;
        }
    
        /**
         * @param mixed $photo
         *
         * @return MedicamentEntity
         */
        public function setPhoto($photo)
        {
            $this->photo = $photo;
            return $this;
        }
    
        /**
         * @param mixed $dci
         *
         * @return MedicamentEntity
         */
        public function setDci($dci)
        {
            $this->dci = $dci;
            return $this;
        }
    
        /**
         * @param mixed $name
         *
         * @return MedicamentEntity
         */
        public function setName($name)
        {
            $this->name = $name;
            return $this;
        }
    
        /**
         * @param mixed $administration
         *
         * @return MedicamentEntity
         */
        public function setAdministration($administration)
        {
            $this->administration = $administration;
            return $this;
        }
    
        /**
         * @param mixed $dosage
         *
         * @return MedicamentEntity
         */
        public function setDosage($dosage)
        {
            $this->dosage = $dosage;
            return $this;
        }
    
        /**
         * @param mixed $unite
         *
         * @return MedicamentEntity
         */
        public function setUnite($unite)
        {
            $this->unite = $unite;
            return $this;
        }
    
        /**
         * @param mixed $id
         *
         * @return MedicamentEntity
         */
        public function setMedicamentId($medicament_id)
        {
            $this->medicament_id = $medicament_id;
            return $this;
        }
    
        /**
         * @return mixed
         */
        public function getClasseId()
        {
            return $this->classe_id;
        }
    
        /**
         * @param mixed $classe_id
         *
         * @return MedicamentEntity
         */
        public function setClasseId($classe_id)
        {
            $this->classe_id = $classe_id;
            return $this;
        }
    
    }