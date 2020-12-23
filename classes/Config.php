<?php
    /*
    Notice we define our configuration in init.php file, we could directly access config data by using :
    $GLOBALS["config"]["mysql"]['host'] for exemple to access host, but this is somewhat messy, we need to build 
    Config class so that we can access our config in a super easy way using forward slash manner like:
    
        echo Config::get('mysql/host'); // Output: 127.0.0.1
    */
    
    namespace classes;

    class Config {

        public static function get($path = null) {
            /* Later, we will use this function as following: Config::get('mysql/host');
                                                                           ^^^^^^^^^^ This is the $path
               Now If we have a path, then we need first to get our configs from globals config (wich will be included from init.php)
               Then we set it in $config variable, then we explode the path using / delimeter to get path pieces
               and we loop through these pieces as following:
               first we ask if mysql isset in $config ? YES so we change our config from $GLOBALS to 'mysql' section in GLOBALS
               the second iteration we ask if host isset inside mysql section yes we set $config = 127.0.0.1 which is the value in host
               Finally we return the data associated to the last segment in the path
                
               Other ex if the following path is given : Config::get('mysql/username');
               Then root will be returned

               Hint: Notice if we give it the following path: mysql/username/garbage : we'll still get root !
               Hint: Notice if we gave it invalid path: garbage/username it will return all the confif infos because it fail
               to catch segments in the loop and so the $config variable will not changed and will preserve the value of $GLOBALS
               Hint: The only way to get function to return false if to pass null or empty !
            */
            
            if($path) {
                $config = $GLOBALS["config"];
                $path = explode('/', $path);

                foreach($path as $bit) {
                    if(isset($config[$bit])) {
                        $config = $config[$bit];
                    }
                }

                return $config;
            }

            // If we don't have a path given get method return false
            return false;
        }
    }

?>