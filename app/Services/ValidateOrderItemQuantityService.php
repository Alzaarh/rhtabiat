<?php


namespace App\Services;


class ValidateOrderItemQuantityService
{
    protected array $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function handle()
    {
        dd('works');
    }
}
