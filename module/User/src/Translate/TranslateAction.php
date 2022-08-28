<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 28/08/2022
     * Time: 09:46
     */
    
    namespace User\Translate;
    
    
    use function PHPUnit\Framework\isNull;

    class TranslateAction
    {
        private static $_instance;
        
        private $locale ;
        
        private $dictionnary;
    
        private $field = "_locale";
        
        public static function getInstance($locale = null ) {
        
            if(is_null(self::$_instance)) {
                self::$_instance = new TranslateAction($locale);
            }
        
            return self::$_instance;
        }
        
        private function save_locale()
        {
            setcookie($this->field, $this->locale,time()+60*60*24*30 , '/');
            $_COOKIE[$this->field] = $this->locale;
        }
        /**
         * @param mixed $locale
         */
        public function setLocale($locale = null)
        {
            if(!is_null($locale)) {
                $this->locale = $locale;
    
                $this->save_locale();
                return;
            }
            
            if (isset($_COOKIE[$this->field])) {
                
                $this->locale = $_COOKIE[$this->field];
                $this->save_locale();
                return;
            }
            
            if(getenv('HTTP_ACCEPT_LANGUAGE') !== false) {
                
                $this->locale = substr(getenv('HTTP_ACCEPT_LANGUAGE'), 0, 2);
    
                $this->save_locale();
                return;
            }
            
            if(is_null($this->locale))
            {
                $this->locale = "fr";
                $this->save_locale();
                return;
            }
        }
    
        /**
         * @param mixed $dictionnary
         */
        public function setDictionnary()
        {
            if(isNull($this->dictionnary)) {
                
                $this->dictionnary = include realpath('./data/language/' . $this->locale . '.php');
            }
        }
    
        private function __construct($locale = null)
        {
            $this->setLocale($locale);
            $this->setDictionnary();
        }
    
        public function _($cde)
        {
            if (array_key_exists($cde, $this->dictionnary)) {
                return $this->dictionnary[$cde];
            }
            
            return $cde ;
        }
    
        /**
         * @return mixed
         */
        public function getLocale()
        {
            return $this->locale;
        }
    }