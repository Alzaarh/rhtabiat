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
        '32' => 'سی و دو',
        '33' => 'سی و سه',
        '34' => 'سی و چهار',
        '35' => 'سی و پنج',
        '36' => 'سی و شش',
        '37' => 'سی و هفت',
        '38' => 'سی و هشت',
        '39' => 'سی و نه',
        '40' => 'چهل',
        '41' => 'چهل و یک',
        '42' => 'چهل و دو',
        '43' => 'چهل و سه',
        '44' => 'چهل و چهار',
        '45' => 'چهل و پنج',
        '46' => 'چهل و شش',
        '47' => 'چهل و هفت',
        '48' => 'چهل و هشت',
        '49' => 'چهل و نه',
        '50' => 'پنجاه',
        '51' => 'پنجاه و یک',
        '52' => 'پنجاه و دو',
        '53' => 'پنجاه و سه',
        '54' => 'پنجاه و چهار',
        '55' => 'پنجاه و پنج',
        '56' => 'پنجاه و شش',
        '57' => 'پنجاه و هفت',
        '58' => 'پنجاه و هشت',
        '59' => 'پنجاه و نه',
    ];

    protected string $type = 'ago';

    public function getCreatedAtFaAttribute(): string
    {
        $createdAt = $this->created_at;
        $func = 'get'.$this->type;
        return $this->$func($createdAt);
    }

    public function getUpdatedAtFaAttribute(): string
    {
        $updatedAt = $this->updated_at;
        $func = 'get'.$this->type;
        return $this->$func($updatedAt);
    }

    protected function getAgo(string $value): string
    {
        $str = Jalalian::forge($value)->ago();
        $int = (int)filter_var($str, FILTER_SANITIZE_NUMBER_INT);
        return preg_replace("!\d+!", static::$conversionTable[$int], $str);
    }
}
