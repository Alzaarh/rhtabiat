<?php

namespace App\Traits;

use Morilog\Jalali\Jalalian;

trait HasPersianDate
{
    protected static array $conversionTable = [
        '1' => 'یک',
        '2' => 'دو',
        '3' => 'سه',
        '4' => 'چهار',
        '5' => 'پنج',
        '6' => 'شش',
        '7' => 'هفت',
        '8' => 'هشت',
        '9' => 'نه',
        '10' => 'ده',
        '11' => 'یازده',
        '12' => 'دوازده',
        '13' => 'سیزده',
        '14' => 'چهارده',
        '15' => 'پانزده',
        '16' => 'شانزده',
        '17' => 'هفده',
        '18' => 'هجده',
        '19' => 'نانزده',
        '20' => 'بیست',
        '21' => 'بیست و یک',
        '22' => 'بیست و دو',
        '23' => 'بیست و سه',
        '24' => 'بیست و پنج',
        '26' => 'بیست و شش',
        '27' => 'بیست و هفت',
        '28' => 'بیست و هشت',
        '29' => 'بیست و نه',
        '30' => 'سی',
        '31' => 'سی و یک',
    ];

    protected string $type = 'ago';

    public function getCreatedAtFaAttribute(): string
    {
        $createdAt = $this->created_at;
        $func = 'get'.$this->type;
        return $this->$func($createdAt);
    }

    protected function getAgo(string $value): string
    {
        $str = Jalalian::forge($value)->ago();
        $int = (int)filter_var($str, FILTER_SANITIZE_NUMBER_INT);
        return preg_replace("!\d+!", static::$conversionTable[$int], $str);
    }
}
