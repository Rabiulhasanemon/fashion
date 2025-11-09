<?php
class  Translator {

    public function __construct($lang) {
        $class = 'Translator\\' . $lang;

        if (class_exists($class)) {
            $this->translator = new $class();
        } else {
            exit('Error: Could not load cache driver ' . $lang . ' cache!');
        }
    }

    public function number($number) {
       return $this->translator->number($number);
    }
}