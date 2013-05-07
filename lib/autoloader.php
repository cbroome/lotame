<?php
/**
 * Very very simple autoloader.
 *
 *
 */


spl_autoload_register(function ($class) {
    
    $base_directory = '';
    
    $parts = explode("\\", $class);
    if($parts[0] == 'Lib') {
        array_shift($parts);
        $base_directory =  DIR_LIB;
    }
    else {
        $base_directory = DIR_APP;
    }
    
    $rel_path = strtolower(implode("/", $parts)) . ".php";    
    include $base_directory . "/" . $rel_path;
    
    
});