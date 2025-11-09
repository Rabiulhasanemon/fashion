<?php
namespace Translator;
class Bengali {

    public function number($number) {
        $search_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        $replace_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
        return str_replace($search_array, $replace_array, $number);
    }

}