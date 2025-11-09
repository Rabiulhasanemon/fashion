<?php
class Date {
	private $date;

	public function __construct($lang) {
		$class = 'Date\\' . $lang;

		if (class_exists($class)) {
			$this->date = new $class();
		} else {
			exit('Error: Could not load cache driver ' . $lang . ' cache!');
		}
	}

	public function getDate($timestamp) {
	    $this->date->setTimestamp($timestamp);
	    return $this->date->getDate();
    }

    public function translate($timestamp, $format = null) {
        $this->date->setTimestamp($timestamp);
        return $this->date->translate($format);
    }

}
