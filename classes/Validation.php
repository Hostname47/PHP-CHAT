<?php

namespace classes;

class Validation {
    private $_passed = false,
            $_errors = array(),
            $_db = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /*
        IMPORTANT: check function will get $source argument as POST or GET arrays to fetch data from them and an array 
        of values which are inputs and each of this values has an array of rules as the value like the following :
        $validate = new Validation();

        $validate->check(array(
        "firstname"=>array(
            "required"=>false,
            "min"=>2,
            "max"=>50
        ),
        "lastname"=>array(
            "required"=>false,
            "min"=>2,
            "max"=>50
        )
        ....
        
        now we we gonna loop through the array of items and each item has an array of rules so we need also to loop through
        the rules array and first we need to check if the field is required. if the field is required but the value is missing
        there's no point to do the rest checks, we just add an error to _errors array
        if the value is not empty we need to make a switch case for each rule
        
    */
    public function check($source, $items = array()) {
        foreach($items as $item=>$rules) {
            foreach($rules as $rule => $rule_value) {
                $value = trim($source[$item]);
                $item = htmlspecialchars($item);

                if($rule === "required" && $rule_value == true && empty($value)) {
                    $this->addError("$item field is required");
                } else if(!empty($value)) {
                    switch($rule) {
                        // Some are implemented here in switch and some of them has their own functions like email func
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("$item must be a minimum of $rule_value characters.");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("$item must be a maximum of $rule_value characters.");
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("passwords should be the same.");
                            }
                        break;
                        case 'unique':
                            $this->_db->query("SELECT * from user_info WHERE $item = '$value'");
                            if($this->_db->count()) {
                                $this->addError("$item already exists.");
                            }
                        break;
                    }
                }
            }
        }

        if(empty($this->_errors)) {
            $this->_passed = true;
        }

        return $this;
    }

    private function addError($error) {
        // This will add the error at the end of array
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed ? true : false;
    }
}