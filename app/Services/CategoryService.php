<?php

namespace App\Services;

class CategoryService
{
    public function handleUploadedIcon($icon)
    {
        return filled($icon) ? saveImageOnDisk($icon) : null;
    }
}