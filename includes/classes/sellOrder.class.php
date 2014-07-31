<?php
	class SellOrder extends Order {

		function __construct(){
			$this->orderType = "sell_order";
			$args = func_get_args();
			switch (func_num_args()){
				case 1:
					parent::__construct($args[0]);
					break;
			}
		}
	}

	/*
		Price
		[1]=> string(5) "45000"
		QL
		[2]=> string(2) "20"
		Count
		[3]=> string(1) "1"
		Seller
		[4]=> string(6) "Gokart"
		Time Left
		[5]=> string(19) "13 days 13 h 41 min"
		Sell Order ID
		[6]=> string(7) "1130757"
	*/
?>