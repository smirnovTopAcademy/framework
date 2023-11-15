<?php

///////////////////////   BAD
class Order
{
  public function getTotal(){}
  public function getItems(){}
  public function getCustomer(){}

  public function printHtml(){}
  public function printXML(){}

  public function save(){}
  public function load(){}
  public function export(){}
}

//////////////////////////// Better

class Order
{
  public function getTotal(){}
  public function getItems(){}
  public function getCustomer(){}
}

class OrderRender
{
  public function printHtml(Order $order){}
  public function printXML(Order $order){}
}

class OrderStorage
{
  public function save(Order $order){}
  public function load(Order $order){}
  public function export(Order $order){}
}

//////////////////////////// Better

class Order
{
  public function getItems(){}
  public function getCustomer(){}
}

class OrderTotal
{
    public function calculate(Order $order){}
}

class OrderSaleTotal
{
    public function calculate(Order $order){}
}



class OrderHTML
{
  public function print(Order $order){}
}

class OrderXML
{
  public function print(Order $order){}
}

//////////////////////////// Better

interface OrderInterface
{
  public function getItems();
  public function getCustomer();
}

class Order implements OrderInterface
{
  public function getItems(){}
  public function getCustomer(){}
}

class FakeTestOrder implements OrderInterface
{
  public function getItems(){}
  public function getCustomer(){}
}



class OrderTotal
{
    public function calculate(OrderInterface $order){}
}



(new OrderTotal())->calculate(new Order())

(new OrderTotal())->calculate(new FakeTestOrder())
