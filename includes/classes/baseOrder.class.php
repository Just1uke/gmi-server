<?php
	class Order {
		public $itemNumber;
		public $price;
		public $ql; 
		public $count;
		public $user;
		public $timeLeft;
		public $orderId;
		public $timestamp;

		function __construct($orderDetails) {
			global $globals;
			$this->itemNumber = $orderDetails[0];
			$this->price = $orderDetails[1];
			$this->ql = $orderDetails[2];
			$this->count = $orderDetails[3];
			$this->user = $orderDetails[4];
			$this->timeLeft = $orderDetails[5];
			$this->orderId = $orderDetails[6];

			$globals->dataHandler->saveOrUpdateOrder($this);
		}
	}
?>