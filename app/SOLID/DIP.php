<?php

$customer = new Customer(new Order(), new OrderProcessor());

$customer->addItem($item);

$customer->buyItems();

class Customer
{
	private OrderInterface $currentOrder;
	private OrderProcessorIntervface $processor;

	public function __construct(OrderInterface $order, OrderProcessorIntervface $processor)
	{
			$this->currentOrder = $order;
			$this->processor = $processor;
	}

	public function buyItems()
	{
		return $this->processor->checkout($this->currentOrder);
	}

	public function addItem($item){
		return $this->currentOrder->addItem($item);
	}

	public function deleteItem($item){
		return $this->currentOrder->deleteItem($item);
	}
}

class OrderProcessor
{
	public function checkout($order){/*...*/}
}
