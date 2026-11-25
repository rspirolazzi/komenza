<?php

namespace App\Helper;

class Helper{
    public static function getFormatTime($reservationTime){
        $values = explode(':',$reservationTime);
        return "{$values[0]}:{$values[1]}";
    }
}
