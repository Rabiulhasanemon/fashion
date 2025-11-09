<?php
namespace Date;
class English {

    private $timestamp; //timestamp as input
    private $engDate; //Current date of English Date
    private $engMonth; //Current month of English Date
    private $engYear; //Current year of English Date

    function setTimestamp($timestamp, $hour = 6 ) {
        $this->timestamp = $timestamp;
        $this->engDate = date( 'd', $timestamp );
        $this->engMonth = date( 'm', $timestamp );
        $this->engYear = date( 'Y', $timestamp );
    }


    function getDate() {
        return $this->engDate . " " . $this->engMonth . " " .  $this->engYear;
    }

    function translate($format) {
        return date($format, $this->timestamp);
    }

}