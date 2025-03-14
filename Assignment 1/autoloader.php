<?php
/**
 * Autoload classes from the classes directory
 * 
 * This script automatically loads class files from the "classes" directory
 * when a class is instantiated. It uses the spl_autoload_register function
 * to register the autoload function.
 * 
 * @version 1.0.0
 * @author Joshua White
 */

/**
 * Autoload function to load class files from the "classes" directory.
 * 
 * @param string $class The name of the class to load.
 * @throws Exception If the class file is not found.
 */
spl_autoload_register(function ($class) {
    $file = "classes/" . $class . ".php";
    if (file_exists($file)) {
        require $file;
    } else {
        throw new Exception("Error: Class file for $class not found", 500);
    }
});