<?php

namespace App\Models\Scheme;

class GroupLetters
{

    /**
     * @return array
     */
    public static function get() : array
    {
        return range('A', 'Z');
    }

}
