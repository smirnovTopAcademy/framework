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


/********************************************************************/

        $dotList = $this->getDotList($request);


        $image = $this->getImage($config);

        $this
          ->fillImage($image, $config)
          ->drawStartCoordinate($image, $config)
          ->drawYAxis($image, $config);



        $this->drawXAxis($image, $properties);

        $this->drawHatchesOnX($image, $properties, $dotList);

        $this->drawHatchesOnY($image, $properties, $dotList);

        $this->drawPointsAndLines($image, $properties, $dotList);

        $response = $this->getResponse($image);

        return $response;
    }

    private function fillImage(ImageCanvas $image, Config $config)
    {
      $fill = new ImageFill($image);

      $fill->fill(
        (new ColoursFactory())
          ->getColor($image, $config->getBackgroundColor())
        );

        return $this;
    }

    private function getImage(Config $config)
    {
      return new ImageCanvas($config->getWidth(), $config->getHeight());
    }

    private function drawStartCoordinate(ImageCanvas $image, Config $config)
    {
      $point = new Point($image);

      $point->drawPoint(
        $this->getLeftMargin($config),
        $this->getBottomMargin($config),
        (new ColoursFactory())
          ->getColor($image, $config->getStartCoordinateColor())
        );

        return $this;
    }

    private function drawYAxis(ImageCanvas $image, Config $config)
    {
        $y = new Line($image);

        $y->draw(
          $this->getLeftMargin($config),
          $this->getBottomMargin($config),
          $this->getLeftMargin($config),
          $this->getTopMargin($config),
          (new ColoursFactory())
            ->getColor($image, $config->getYAxisColor())
        );
    }

    private function getLeftMargin(Config $config): int
    {
      return floor($config->getWidth()
        * $config->getMargin('horizontal')
      );
    }

    private function getBottomMargin(Config $config): int
    {
      return floor($config->getHeight()
        - $config->getHeight()
          * $config->getMargin('vertical')
        );
    }

    private function getTopMargin(Config $config): int
    {
      return floor($config->getHeight()
        * $config->getMargin('vertical'));
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

    private function getResponse($image)
    {
      ob_start();
      imagepng($image->getResource(), null, 0);
      $image = ob_get_contents();
      ob_end_clean();

      $response = \Illuminate\Support\Facades\Response::make($image, 200);
      $response->header("Content-Type", "image/png");

      return $response;
    }

    private function getDotList($request)
    {
      $list = [];

      foreach( $request->all()['dot'] ?? [] as $dot)
      {
        $list[] = new Dot($dot);
      }

      return $request->all()['dot'] ?? [];
    }
}
