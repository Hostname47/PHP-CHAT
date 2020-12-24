<?php

namespace classes;

class Common {
    public static function getInput($source, $fieldName) {
        if(isset($source[$fieldName])) {
            return $source[$fieldName];
        }

        return '';
    }
}