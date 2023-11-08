<?php

namespace App\Image\Colours;

use App\Image\Image;

class Factory
{
    public function getColor(Image $image, string $class): Colour
    {
      return new $class($image);
    }
}
