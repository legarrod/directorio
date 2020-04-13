<?php
if (!function_exists('get_directorist_option')){

    /**
     * It retrieves an option from the database if it exists and returns false if it is not exist.
     * It is a custom function to get the data of custom setting page
     * @param string $name          The name of the option we would like to get. Eg. map_api_key
     * @param mixed $default        Default value for the option key if the option does not have value then default will be returned
     * @param bool $force_default   Whether to use default value when database return anything other than NULL such as '', false etc
     * @return mixed    It returns the value of the $name option if it exists in the option $group in the database, false otherwise.
     */
    function get_directorist_option($name, $default=false, $force_default = false){
        // at first get the group of options from the database.
        // then check if the data exists in the array and if it exists then return it
        // if not, then return false
        if (empty($name)) { return $default; }
        // get the option from the database and return it if it is not a null value. Otherwise, return the default value
        $options = (array) get_option('atbdp_option');
        $v = (array_key_exists($name, $options))
            ? $v =  $options[sanitize_key($name)]
            : null;
        // use default only when the value of the $v is NULL
        if (is_null($v)) { return $default; }
        if ($force_default){
            // use the default value even if the value of $v is falsy value returned from the database
            if(empty($v)) { return $default; }
        }
        return (isset($v) ) ? $v : $default; // return the data if it is anything but NULL.
    }
}