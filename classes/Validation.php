<?php

namespace classes;

class Validation {
    private $_passed = true,
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
        error_reporting(E_ERROR | E_PARSE);

        if($source === $_FILES) {
            $counter = 0;
            foreach($items as $item=>$rules) {
                foreach($rules as $rule => $rule_value) {
                    //$item = htmlspecialchars($item);
                    $name = $item;
                    if($rule === "required" && $rule_value == true && empty($name)) {
                        $this->addError("{$rules['name']} field is required");
                    } else if(!empty($name)) {
                        switch($rule) {
                            // Some are implemented here in switch and some of them has their own functions like email func
                            case 'image':
                                /*if($source[$item]['type'] != "image/png") {
                                    $this->addError("Only PNG images are allowed in {$rules['name']} image!");
                                }

                                 Do not rely on any of the data in $_FILES. Many sites tell you to check the mime type of the file, 
                                either from $_FILES[0]['type'] or by checking the filename's extension. Do not do this. Everything under
                                $_FILES with the exception of tmp_name can be manipulated by a malicious user. If you know you want images 
                                ony call getimagesize as it actually reads image data and will know if the file is really an image. 
                                
                                CAUTION: (from PHP Doc)
                                Do not use getimagesize() to check that a given file is a valid image. Use a purpose-built solution such as the Fileinfo 
                                extension instead. 
                                */
                                // ----------------------      CHECK IMAGE TYPE      ----------------------

                                /*
                                    IMPORTANT: Notice when we pass data through javascript the server automatically convert dots(.) to _ which was always assign null to $img
                                    I stuck with this error for days before I realize it.
                                    Try to solve this problem in the client side using javascript by checking the filename or replace the last hyphen with dot
                                */
                                foreach($_FILES as $key=>$value) {
                                    /*
                                        Here why we should do that, because we need to check that the php is replace the dot with hyphen and
                                        doesn't contains a dot then we should take the original file name and past it as new key to $_FILES array
                                        otherwise if the filename doesn't contain any hyphen or when the filename contains a dotthen we do the process as usual
                                        We only have a problem when we append the data from js file to php, php take the filename and replace spaces and extension
                                        dot to hyphens

                                    */

                                    if(!strpos($key, '.') && strpos($key, '_')) {
                                        $_FILES[$value['name']] = $value;
                                        unset($_FILES[$key]);
                                    }
                                }
                                $img = $_FILES[$name];
                                $name = $img["name"];

                                $allowedImageExtensions = array(".png", ".jpeg", ".gif", ".jpg", ".jfif");

                                $original_extension = (false === $pos = strrpos($name, '.')) ? '' : substr($name, $pos);
                                $original_extension = strtolower($original_extension);

                                // This is more secure (IMPOSTANT: change name to tmp_name ine $file variable if you want to use finfo to check images)
                                /*$finfo = new \finfo(FILEINFO_MIME_TYPE);
                                $type = $finfo->file($file);*/
                                if (!in_array($original_extension, $allowedImageExtensions))
                                {
                                    $this->addError("Only PNG, JPG, JPEG, and GIF image types are allowed to be used in {$rules['name']} image!");
                                }

                                if ($img["size"] > 5500000) {
                                    $this->addError("Sorry, your file is too large.");
                                }

                                // Add some layer of image upload security later
                            break;
                            case 'video': 
                                $file = $item;
                                $allowedVideoExtensions = array(".mp4", ".mov", ".wmv", ".flv", ".avi", ".avchd", ".webm", ".mkv");

                                $original_extension = (false === $pos = strrpos($file, '.')) ? '' : substr($file, $pos);
                                $original_extension = strtolower($original_extension);

                                // This is more secure (IMPOSTANT: change name to tmp_name ine $file variable if you want to use finfo to check images)
                                /*$finfo = new \finfo(FILEINFO_MIME_TYPE);
                                $type = $finfo->file($file);*/
                                if (!in_array($original_extension, $allowedVideoExtensions))
                                {
                                    $this->addError("Only The following video formats are supported to be used.");
                                }

                                // Video should not exceed 1GB
                                if ($img["size"] > 1073741824) {
                                    $this->addError("Sorry, your file is too large.");
                                }

                        }
                    }
                }
                $counter++;
            }
        } else {
            foreach($items as $item=>$rules) {
                foreach($rules as $rule => $rule_value) {
                    $value = trim($source[$item]);
                    $item = htmlspecialchars($item);
    
                    if($rule === "required" && $rule_value == true && empty($value)) {
                        $this->addError("{$rules['name']} field is required");
                    } else if(!empty($value)) {
                        switch($rule) {
                            // Some are implemented here in switch and some of them has their own functions like email func
                            case 'min':
                                if(strlen($value) < $rule_value) {
                                    $this->addError("{$rules['name']} must be a minimum of $rule_value characters.");
                                }
                            break;
                            case 'max':
                                if(strlen($value) > $rule_value) {
                                    $this->addError("{$rules['name']} must be a maximum of $rule_value characters.");
                                }
                            break;
                            case 'matches':
                                if($value != $source[$rule_value]) {
                                    $this->addError("Passwords should be the same.");
                                }
                            break;
                            case 'unique':
                                $this->_db->query("SELECT * from user_info WHERE $item = '$value'");
                                if($this->_db->count()) {
                                    $this->addError("{$rules['name']} already exists.");
                                }
                            break;
                            case 'email-or-username':
                                $email_or_username = trim($value);
                                $email_or_username = filter_var($email_or_username, FILTER_SANITIZE_EMAIL);
                                if(strpos($email_or_username, '@') == true) {
                                    if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email_or_username)) {
                                        $this->addError("Invalid email address.");
                                    }
                                } else {
                                    // Handle username to be alphanumeric or just keep it like so (it's fine at least for now)
                                }
                            break;
                            case 'email':
                                $email = trim($value);
                                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                                if(strpos($email, '@') == true) {
                                    if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
                                        $this->addError("Invalid email address.");
                                    }
                                } else {
                                    $this->addError("Invalid email address.");
                                }
                            break;
                            case 'range':
                                if(!in_array($value, $rule_value))
                                    $this->addError("{$rules['name']} field is either not set or invalid !");
                            break;
                        }
                    }
                }
            }   
        }

        if(empty($this->_errors)) {
            $this->_passed = true;
        } else {
            $this->_passed = false;
        }

        // Clearing $_FILES
        return $this;
    }

    public function addError($error) {
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