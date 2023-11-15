<?php

interface DB
{
  public function connect();
  public function insert();
  public function get();
}


class Mysql implements DB
{
  public function connect()
  {

  }
  public function insert()
  {

  }
  public function get()
  {

  }
}

class MariaDB extends Mysql
{
  
}
