<?php

namespace ticketbureau\seatwave\helpers;

class DateHelper {

    /**
     * Date helper for parsing the response of seatwave: /Date(1340550000000+0100)/
     * @param        $rawDate
     *
     * @return \DateTime
     */
    public static function Date($rawDate) {
        preg_match('#/Date\(([0-9]+)\+[0-9]{4}\)/#', $rawDate, $matches);
        return new \DateTime(date('Y-m-d H:i:s', $matches[1]/1000));
    }
}