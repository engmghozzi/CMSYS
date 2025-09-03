<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format date to dd-mm-yyyy format
     */
    public static function formatDate($date, $format = 'd-m-Y')
    {
        if (!$date) {
            return '';
        }
        
        return Carbon::parse($date)->format($format);
    }

    /**
     * Format date for HTML date input (yyyy-mm-dd)
     */
    public static function formatForInput($date)
    {
        if (!$date) {
            return '';
        }
        
        return Carbon::parse($date)->format('Y-m-d');
    }

    /**
     * Parse date from dd-mm-yyyy format
     */
    public static function parseDate($dateString)
    {
        if (!$dateString) {
            return null;
        }
        
        return Carbon::createFromFormat('d-m-Y', $dateString);
    }
}
