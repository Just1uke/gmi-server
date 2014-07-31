<?php
	header("Content-Type: application/json");
	$data = json_decode(@file_get_contents('php://input'));
	
	$confirmText = '<h3 class="boldInline">Are you sure you want to cancel';
	$confirmText .= sizeof($data->orders) > 1 ? " these " : " this ";
	$confirmText .= '</h3><h3 class="inline" id="orderType">' . $data->type . '</h3><h3 class="inline"> order';
	$confirmText .= sizeof($data->orders) > 1 ? "s" : "";
	$confirmText .= '?</h3>';

	$orderNumbers = array();

	ob_start();
?>
	<div id="popup_main_header">
		<h2>Cancel</h2>
	</div>
	<div id="popup_body">
		<div class="popup_header">
		<?php echo $confirmText; ?>
		</div>
		<table class="width100percent">
		<colgroup>
			<col width="48px">
			<col width="100%">
			<col width="180px">
		</colgroup>
		<tr>
			<th>Icon</th>
			<th>Name</th>
			<th>Value</th>
		</tr>
			<?php
			foreach($data->orders as $orderIndex => $order){
				$orderNumbers[] = $order->order_id;
			?>
			<tr>
				<td id="orderItemIcon" class="pcenter"><img height="20" width="20" src="<?php echo $order->icon_html; ?>"/></td>
				<td id="orderItemName"><?php echo $order->name; ?></td>
				<td id="orderValue"><?php echo $order->order_value; ?></td>
			</tr>
			<?php 
			}
			?>

		</table>
		<br />
		<div id="cancelErrorText"></div>
		<br />
		<div style="width: 100px; margin-left: auto; margin-right: auto;">
		<div class="button" onclick="cancelOrders('/marketLIVE/do_cancel_<?php echo $data->type ?>', {numbers: <?php echo str_replace("\"", '', json_encode($orderNumbers)); ?>});">
			<div id="sellCancelL1" class="button_left"></div>
			<div id="sellCancelC1" class="button_center"><a href="#"><p class="button_text">Yes</p></a></div>
			<div id="sellCancelR1" class="button_right"></div>
		</div>
		<div class="button" onclick="closePopUpWindow('popup_info','overlay')" onMouseDown="clickedButton(this)" onmouseout="clickedButton(this, true)" onMouseUp="clickedButton(this)">
			<div id="sellCancelL1" class="button_left"></div>
			<div id="sellCancelC1" class="button_center"><a href="#"><p class="button_text">No</p></a></div>
			<div id="sellCancelR1" class="button_right"></div>
		</div>
		</div>
	</div>
<?php
	$return = str_replace("\t", "", str_replace("\n", "", ob_get_clean()));
	echo json_encode(array("html" => $return));
?>
