<?php

namespace App\Image;

class Dot
{
    private $value;

    public function __construct($value)
    {
      $this->value = $value;
    }

    public function getValue()
    {
      return $this->value;
    }
}
