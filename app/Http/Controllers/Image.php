<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image\Line;
use App\Image\Colours\Black;
use App\Image\Image as ImageCanvas;
use App\Image\Config;
use App\Image\Point;
use App\Image\Dot;
use App\Image\ImageFill;
use App\Image\Colours\Factory as ColoursFactory;

class Image extends Controller
{
    public function create(Request $request)
    {
        $res = imagecreatetruecolor(1024, 568);

        $backgroundcolor = imagecolorallocate($res, 255, 255, 255);

        imagefill($res, 0, 0, $backgroundcolor);

        $textcolor = imagecolorallocate($res, 0, 0, 255);

        imagestring($res, 5, 0, 0, 'Hello world!', $textcolor);

        $blackcolor = imagecolorallocate($res, 0, 0, 0);

        imageline(
            $res,
            100,
            100,
            1024 - 100,
            568 - 100,
            $blackcolor
        );

        imagettftext(
          $res,
          24,
          0,
          100,
          568 - 100,
          $blackcolor,
          __DIR__ . '/../../TTF/DarlingtonDemo-z8xjG.ttf',
          'Academy Top'
        );


        ob_start();
        imagepng($res, null, 0);
        $image = ob_get_contents();
        ob_end_clean();

        $response = \Illuminate\Support\Facades\Response::make($image, 200);
        $response->header("Content-Type", "image/png");

        return $response;
    }

    public function graph (Request $request)
    {
        $properties = [
          'margin' => [
            'vertical' => 0.1,
            'horizontal' => 0.05,
          ],
          'width' => 1024,
          'height' => 568,
          'hatchHeight' => 10,
        ];


        $config = $this->getConfig();


        $response = (new ImageCreate())->create($request, $config, $properties)



        return $response;
    }

    private function getConfig($type = 'image_graph')
    {
      $config = new Config();

      $config
        ->setHeight(config("$type.height"))
        ->setWidth(config("$type.width"))
        ->setHatchHeight(config("$type.hatchHeight"))
        ->setBackgroundColor(config("$type.backgroundcolor"))
        ->setStartCoordinateColor(config("$type.startCoordinateColor"))
        ->setMargin(config("$type.margin"))
        ->setYAxisColor(config("$type.yAxis"))
        ;

      return $config;
    }
}
