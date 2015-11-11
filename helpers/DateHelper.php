<?php

namespace ticketbureau\seatwave\helpers;

class DateHelper {

    public static function Date($rawString, $format = 'Y-m-d H:i:s') {
        // Date = /Date(1340550000000+0100)/
        preg_match('#/Date\(([0-9]+)\+[0-9]{4}\)/#', $rawString, $matches);

        return new \DateTime(date($format, $matches[1]/1000));
    }
}