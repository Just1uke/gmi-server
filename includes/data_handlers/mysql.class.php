<?php
	class DataHandler {
		public $pdo; 

		function __construct(){
			$this->pdo = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
		}

		// public function updateBuyOrder($price, $ql, $count, $buyer, $timeLeft, $orderId){
		// 	$this->updateOrder($price, $ql, $count, $user, $timeLeft, $orderId, "buy_order");
		// }

		// public function updateSellOrder($price, $ql, $count, $seller, $timeLeft, $orderId){
		// 	$this->updateOrder($price, $ql, $count, $user, $timeLeft, $orderId, "sell_order");
		// }

		public function saveOrUpdateOrder($order){
			$sth = $this->pdo->prepare("	INSERT INTO orders (`orders`.`id`,
												`orders`.`type`,
												`orders`.`price`,
												`orders`.`ql`,
												`orders`.`count`,
												`orders`.`user`,
												`orders`.`time_left`,
												`orders`.`item`) 
											VALUES(:id, :type, :price, :ql, :count, :user, FROM_UNIXTIME(:time_left), :item) 
											ON DUPLICATE KEY UPDATE
												price=:price, 
												count=:count, 
												time_left=FROM_UNIXTIME(:time_left)");

			if($sth->execute(array(':id' => $order->orderId, ':type' => $order->orderType, 
								':price' => $order->price, ':ql' => $order->ql,
								':count' => $order->count, ':user' => $order->user,
								':time_left' => strtotime("+ " . str_replace(' h ', ' hours ', $order->timeLeft)), ':item' => $order->itemNumber))){
			} else {
				// echo("INSERT INTO orders (`orders`.`id`,
				// 								`orders`.`type`,
				// 								`orders`.`price`,
				// 								`orders`.`ql`,
				// 								`orders`.`count`,
				// 								`orders`.`user`,
				// 								`orders`.`time_left`,
				// 								`orders`.`item`) 
				// 							VALUES($order->orderId, $order->orderType, $order->price, $order->ql, $order->count, $order->user, FROM_UNIXTIME($order->timeLeft), $order->itemNumber) 
				// 							ON DUPLICATE KEY UPDATE
				// 								price=$order->price, 
				// 								count=$order->count, 
				// 								time_left=FROM_UNIXTIME($order->timeLeft)");
				echo("Failed.");
				exit;
			}
		}

		public function getSellOrderInformation($itemId){
			$test = new SellOrder();
			$sth = $this->pdo->prepare("	SELECT `orders`.`id` as `orderId`,
												`orders`.`type` as `orderType`,
												`orders`.`price`,
												`orders`.`ql`,
												`orders`.`count`,
												`orders`.`user`,
												`orders`.`time_left` as `timeLeft`,
												`orders`.`item` as `itemNumber`,
												`orders`.`timestamp` 
											FROM `orders`
											WHERE `orders`.`item` = :itemId
											AND `orders`.`type` = 'sell_order'
											ORDER BY `orders`.`time_left` DESC");

			if($sth->execute(array(':itemId' => $itemId))){
				return $sth->fetchAll(PDO::FETCH_CLASS, "SellOrder");
			} else {
				echo("Failed.");
				exit;
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
	}

?>