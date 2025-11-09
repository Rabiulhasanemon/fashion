<?php
namespace Date;
class Bengali {

    private $timestamp; //timestamp as input
    private $morning; //when the date will change?
    private $engHour; //Current hour of English Date
    private $engDate; //Current date of English Date
    private $engMonth; //Current month of English Date
    private $engYear; //Current year of English Date
    private $bangDate; //generated Bangla Date
    private $bangMonth; //generated Bangla Month
    private $bangYear; //generated Bangla   Year

    private $bn_months = array("পৌষ", "মাঘ", "ফাল্গুন", "চৈত্র", "বৈশাখ", "জ্যৈষ্ঠ", "আষাঢ়", "শ্রাবণ", "ভাদ্র", "আশ্বিন", "কার্তিক", "অগ্রহায়ণ");
    private $bn_month_dates = array(30,30,30,30,31,31,31,31,31,30,30,30);
    private $bn_month_middate = array(13,12,14,13,14,14,15,15,15,15,14,14);
    private $lipyearindex = 3;

    function setTimestamp($timestamp, $hour = 6 ) {
        $this->timestamp = $timestamp;
        $this->engDate = date( 'd', $timestamp );
        $this->engMonth = date( 'm', $timestamp );
        $this->engYear = date( 'Y', $timestamp );
        $this->morning = $hour;
        $this->engHour = date( 'G', $timestamp );
    }

    function calculate() {
        $this->calculateDate();
        $this->calculateYear();
        $this->convert();
    }

    private function calculateDate() {
        $this->bangDate = $this->engDate - $this->bn_month_middate[$this->engMonth - 1];
        if ($this->engHour < $this->morning)
            $this->bangDate -= 1;

        if (($this->engDate <= $this->bn_month_middate[$this->engMonth - 1]) || ($this->engDate == $this->bn_month_middate[$this->engMonth - 1] + 1 && $this->engHour < $this->morning) ) {
            $this->bangDate += $this->bn_month_dates[$this->engMonth - 1];
            if ($this->isLeapyear() && $this->lipyearindex == $this->engMonth)
                $this->bangDate += 1;
            $this->bangMonth = $this->bn_months[$this->engMonth - 1];
        }
        else{
            $this->bangMonth = $this->bn_months[($this->engMonth)%12];
        }
    }

    function isLeapyear() {
        if ( $this->engYear % 400 == 0 || ($this->engYear % 100 != 0 && $this->engYear % 4 == 0) )
            return true;
        else
            return false;
    }

    function calculateYear() {
        $this->bangYear = $this->engYear - 593;
        if (($this->engMonth < 4) || (($this->engMonth == 4) && (($this->engDate < 14) || ($this->engDate == 14 && $this->engHour < $this->morning))))
            $this->bangYear -= 1;
    }

    function banglaNumber($int ) {
        $engNumber = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০');

        $converted = str_replace( $engNumber, $bangNumber, $int );
        return $converted;
    }

    function convert() {
        $this->bangDate = $this->banglaNumber( $this->bangDate );
        $this->bangYear = $this->banglaNumber( $this->bangYear );
    }

    function getDate() {
        $this->calculate();
        return $this->bangDate . " " . $this->bangMonth . " " .  $this->bangYear;
    }

    function translate($format) {
        $engDate = date($format, $this->timestamp);
        $search_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December", ":", ",");
        $replace_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০", "জানুয়ারী", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগষ্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর", ":", ",");
        return str_replace($search_array, $replace_array, $engDate);
    }

}