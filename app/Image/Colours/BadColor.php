<?php

namespace App\Image\Colours;

use App\Image\Image;

class BadColour
{
   private Image $image;

   function getRed(): int
   {
     return imagecolorallocate(
       $this->image->getResource()
       , 255
       , 0
       , 0
     );
   }

  function getGeen(): int
  {
    return imagecolorallocate(
      $this->image->getResource()
      , 0
      , 255
      , 0
    );
  }

   function getBlue(): int
   {
     return imagecolorallocate(
       $this->image->getResource()
       , 0
       , 0
       , 255
     );
   }

    function getBlack(): int
    {
      return imagecolorallocate(
        $this->image->getResource()
        , 0
        , 0
        , 0
      );
    }

     function getMagenta(): int
     {
       return imagecolorallocate(
         $this->image->getResource()
         , 0
         , 0
         , 0
       );
     }
}
