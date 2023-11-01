<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image\Line;
use App\Image\Colours\Black;
use App\Image\Image as ImageCanvas;

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

        $image = new \App\Image\Image(
          $properties['width']
          , $properties['height']
        );

        $fill = new \App\Image\ImageFill($image);

        $fill->fill(new \App\Image\Colours\White($image));

        $dotList = $request->all()['dot'] ?? [];

        $this->drawStartCoordinate($image, $properties);

        $this->drawYAxis($image, $properties);

        $this->drawXAxis($image, $properties);

        $this->drawHatchesOnX($image, $properties, $dotList);

        $this->drawHatchesOnY($image, $properties, $dotList);

        $this->drawPointsAndLines($image, $properties, $dotList);


        ob_start();
        imagepng($image->getResource(), null, 0);
        $image = ob_get_contents();
        ob_end_clean();

        $response = \Illuminate\Support\Facades\Response::make($image, 200);
        $response->header("Content-Type", "image/png");

        return $response;
    }

    private function drawStartCoordinate($image, $properties)
    {
      $point = new \App\Image\Point($image);

      $point->drawPoint(
          floor($properties['width']
            * $properties['margin']['horizontal']),
          floor($properties['height']
            - $properties['height']
              * $properties['margin']['vertical']),
          new \App\Image\Colours\Black($image)
        );
    }

    private function drawYAxis(ImageCanvas $image, array $properties)
    {
        $y = new Line($image);

        $y->draw(
          $this->getLeftMargin($properties),
          $this->getBottomMargin($properties),
          $this->getLeftMargin($properties),
          $this->getTopMargin($properties),
          new Black($image)
        );
    }

    private function getLeftMargin($properties): int
    {
      return floor($properties['width']
        * $properties['margin']['horizontal']);
    }

    private function getBottomMargin($properties): int
    {
      return floor($properties['height']
        - $properties['height']
          * $properties['margin']['vertical']);
    }

    private function getTopMargin($properties): int
    {
      return floor($properties['height']
        * $properties['margin']['vertical']);
    }

    private function drawXAxis($image, $properties)
    {
        $x = new Line($image);
        $x->draw(
          floor($properties['width']
            * $properties['margin']['horizontal']),

          floor($properties['height']
            - $properties['height']
            * $properties['margin']['vertical']),

          floor($properties['width']
            - $properties['width']
            * $properties['margin']['horizontal']),

          floor($properties['height']
            - $properties['height']
            * $properties['margin']['vertical']),

          new \App\Image\Colours\Black($image)
        );
    }

    private function drawHatchesOnX($image, $properties, $dotList)
    {
        $sectionLenght = $this->getXSize($properties, $dotList);

          $n = 1;
        foreach ($dotList as $key => $value) {
          $section = new Line($image);

          $section->draw(
            floor($properties['width']
              * $properties['margin']['horizontal']
              + $sectionLenght * $n
              ),

            floor($properties['height']
              - $properties['height']
                * $properties['margin']['vertical']) - $properties['hatchHeight'] / 2,

            floor($properties['width']
              * $properties['margin']['horizontal']
              + $sectionLenght * $n
              ),

            floor($properties['height']
              - $properties['height']
                * $properties['margin']['vertical']) + $properties['hatchHeight'] / 2,
                new \App\Image\Colours\Black($image)
          );
          $n++;
        }
    }

    private function getXSize(array $properties, array $dotList): int
    {
      return floor(($properties['width']
        - 2
        * ($properties['width'] * $properties['margin']['horizontal'])
        )
        / (count($dotList) + 1));
    }

    private function drawHatchesOnY($image, $properties, $dotList)
    {
        $min = min($dotList) - 1;
        $max = max($dotList) + 1;

        $sectionCount = $max - $min;

        $sectionLenght = floor(
          ($properties['height']
          - 2
          * ($properties['height'] * $properties['margin']['vertical'])
          )
          / $sectionCount);


        for ($i = 1; $i < $sectionCount; $i++)
        {
            $hatch = new Line($image);

            $hatch->draw(
              floor($properties['width']
                * $properties['margin']['horizontal']
                  - $properties['hatchHeight'] / 2
                ),
              floor($properties['height']
                * $properties['margin']['vertical']
                  + $sectionLenght * $i
                ),
              floor($properties['width']
                * $properties['margin']['horizontal']
                  + $properties['hatchHeight'] / 2
                ),
              floor($properties['height']
                * $properties['margin']['vertical']
                  + $sectionLenght * $i
                ),
            new \App\Image\Colours\Black($image)
            );
        }
    }

    private function drawPointsAndLines($image, $properties, $dotList)
    {

        $xCount = 1;

        $sectionXLenght = $this->getXSize($properties, $dotList);

        $min = min($dotList) - 1;
        $max = max($dotList) + 1;
        $sectionCount = $max - $min;

        $sectionYLenght = floor(
          ($properties['height']
          - 2
          * ($properties['height'] * $properties['margin']['vertical'])
          )
          / $sectionCount);

          $last = [];

        foreach($dotList as $dot)
        {
          $point = new \App\Image\Point($image);
          $point->drawPoint(
            $properties['width'] * $properties['margin']['horizontal']
            + $sectionXLenght * $xCount,
            floor($properties['height']
              - $properties['height']
                * $properties['margin']['vertical']
                - ($sectionYLenght * ($dot - $min))
                ),
            new \App\Image\Colours\Black($image)

          );

          if (!empty($last)) {
            $line = new Line($image);
            $line->draw(
              $last['x'],
              $last['y'],
              $properties['width'] * $properties['margin']['horizontal']
              + $sectionXLenght * $xCount,
              floor($properties['height']
                - $properties['height']
                  * $properties['margin']['vertical']
                  - ($sectionYLenght * ($dot - $min))
                  ),
              new \App\Image\Colours\Green($image)
            );
          }

          $last = [
            'x' => $properties['width'] * $properties['margin']['horizontal']
            + $sectionXLenght * $xCount,
            'y' => floor($properties['height']
              - $properties['height']
                * $properties['margin']['vertical']
                - ($sectionYLenght * ($dot - $min))
                )
          ];

          $xCount++;
        }

    }

}
