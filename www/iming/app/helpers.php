<?php

if (!function_exists('if_first_letter_is_uppercase')) {
    function if_first_letter_is_uppercase($word) {
        if (!is_string($word) || !strlen($word)) {
            return false;
        }
        return ctype_upper($word[0]);
    }
}

?>