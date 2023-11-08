<?php

namespace App\Image;

class Config
{
    private int $width;

    private int $height;

    private int $hatchHeight;

    private string $backgroundColor;

    private array $margin = [];

    private string $startCoordinateColor;

    private string $yAxisColor;

    public function setWidth(int $width)
    {
      $this->width = $width;

      return $this;
    }

    public function getWidth()
    {
      return $this->width;
    }

    public function setHeight(int $height)
    {
      $this->height = $height;

      return $this;
    }

    public function getHeight()
    {
      return $this->height;
    }

    public function setHatchHeight(int $hatchHeight)
    {
      $this->hatchHeight = $hatchHeight;

      return $this;
    }

    public function setBackgroundColor(string $backgroundColor)
    {
      $this->backgroundColor = $backgroundColor;

      return $this;
    }

    public function getBackgroundColor(): string
    {
      return $this->backgroundColor;
    }

    public function setMargin(array $margin)
    {
      $this->margin = $margin;

      return $this;
    }

    public function getMargin(string $type): float
    {
      return $this->margin[$type] ?? 0.0;
    }


    public function setStartCoordinateColor(string $startCoordinateColor)
    {
      $this->startCoordinateColor = $startCoordinateColor;

      return $this;
    }

    public function getStartCoordinateColor(): string
    {
      return $this->startCoordinateColor;
    }

    public function setYAxisColor(string $yAxisColor)
    {
      $this->yAxisColor = $yAxisColor;

      return $this;
    }

    public function getYAxisColor(): string
    {
      return $this->yAxisColor;
    }
}
