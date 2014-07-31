// $(function(){
// 		$('div#menu').prepend('<div class="picture_tab"><img class="deep_stats" src="/staticLIVE/images/tab_right.png" /></div>\
//           		<div class="url_tab deep_stats"><a href="http://aomarket.funcom.com/market/deep_stats.php"><p class="pHeader">Deep Stats</p></a></div>\
//           		<div class="picture_tab"><img class="deep_stats" src="/staticLIVE/images/tab_left.png" /></div>');
// 		var currentTab = location.href.substr(location.href.lastIndexOf('/')+1);

// 		if(currentTab == "deep_stats.php"){
// 			$('img.deep_stats[src="/staticLIVE/images/tab_right.png"]').attr('src', '/staticLIVE/images/tab_right_active.png');
// 			$('img.deep_stats[src="/staticLIVE/images/tab_left.png"]').attr('src', '/staticLIVE/images/tab_left_active.png');
// 			$('div.deep_stats').removeClass('url_tab').addClass('url_active_tab');
// 		}
// });

jQuery.extend({
	postJSON: function(url, data, callback, errorCallback) {
		$.ajax({url: url,
						type: 'POST',
						contentType: 'application/json', dataType: 'json',
						data: JSON.stringify(data),
						processData: false,
						success: callback,
						error: errorCallback});
	}
});

function hotKeys(event) {
	if(event.keyCode == 27) {
		closePopUpWindow('popup_info', 'overlay');
	}
}

function ajaxError(jqXHR, textStatus, errorThrown) {
	popUp = createPopUp('Error', 'Message',
											'<p>An error has occurred, please contact customer support and include the text below:</p><br /><div id="serverErrorText" class="scroll-pane-arrows" style="height:300px;max-width:513px;border:dashed 1px;margin-left:3px"></div>', 
											'popup_info_error_window', 
											'overlay_error_window');
		
		var element = jQuery('#serverErrorText', popUp).jScrollPane({
			showArrows: true,
			horizontalGutter: 10
		});
		
		var api = element.data('jsp');
		api.getContentPane().html(jqXHR.responseText);
		api.reinitialise();
}

function canClickOnce(button)
{
	if (!button) return true;
	if ($(button).data('clickedOnce') == true) return false;
	return true;
}

function applyClickOnce(button)
{
	if (!button) return true;
	if ($(button).data('clickedOnce') == true) return false;
	$(button).data('clickedOnce', true)
	
	var left = jQuery('div[class^="button_left"]', button);
	left.attr("class", "button_left_disabled");
	var center = jQuery('div[class^="button_center"]', button);
	center.attr("class", "button_center_disabled");
	var right = jQuery('div[class^="button_right"]', button);
	right.attr("class", "button_right_disabled");
	
	return true;
}

function freeClickOnce(button)
{
	if (!button) return;
	$(button).data('clickedOnce', false);
	
	var left = jQuery('div[class^="button_left"]', button);
	left.attr("class", "button_left");
	var center = jQuery('div[class^="button_center"]', button);
	center.attr("class", "button_center");
	var right = jQuery('div[class^="button_right"]', button);
	right.attr("class", "button_right");
}

/**
* This function will change the div ids of a button. 
* It is important that you have made 3 div tag ids in your css file named:
* button_left, button_center, button_right
* button_left_pressed, button_center_pressed, button_right_pressed
* Param 1: The button you want to change.
**/
function clickedButton(button, out)
{
	if (!canClickOnce(button)) return true;

	var suffix = "";
	if (! out == true)
	{
		if (!button.pressed)
			suffix = "_pressed";
	} else {
		if (!button.pressed)
			return true;
	}
		
	var left = jQuery('div[class^="button_left"]', button);
	left.attr("class", "button_left" + suffix);
	var center = jQuery('div[class^="button_center"]', button);
	center.attr("class", "button_center" + suffix);
	var right = jQuery('div[class^="button_right"]', button);
	right.attr("class", "button_right" + suffix);

	button.pressed = !button.pressed;

	return true;
}

function searchForItem(divId, url, e, imgUrl) {
	if(e.which == 13 || e==true)
	{
		$('#plussMinus').attr('src', imgUrl);
		$('#plussMinus').attr('status', 'pluss');
		$('#selectSlot').css('display', 'none');
		
		$('#plussMinus2').attr('src', imgUrl);
		$('#plussMinus2').attr('status', 'pluss');
		$('#orderSearch').css('display', 'none');

		var item_name = document.getElementsByName("ItemName");
		var category = document.getElementsByName("category");
		var orders = document.getElementsByName("orders");
		var minQL = document.getElementsByName("minQL");
		var maxQL = document.getElementsByName("maxQL");
		var minPrice = document.getElementsByName("minPrice");
		var maxPrice = document.getElementsByName("maxPrice");
		
		var placement = 0xffffffff;
		if ($('input[name="slot"]').filter(':not(:checked)').length > 0) {
			placement = 0;
			$('input[name="slot"]:checked').each(function() {
					placement |= $(this).val();
				});
		}

		var data = {'search' : true,
								'name': item_name[0].value,
								'category': category[0].value,
								'placement': placement,
								'order_mode': orders[0].value,
								'ql_low': minQL[0].value,
								'ql_high': maxQL[0].value,
								'price_low': minPrice[0].value,
								'price_high': maxPrice[0].value};

		var url = window.location.protocol + "//" + window.location.host + window.location.pathname;
		window.location = url + "?" + jQuery.param(data);
	}
}

function updateDiv(divId, url, data) {
		jQuery.postJSON(url, data, reply(divId), ajaxError);
}

function reply(divId) {
	return function(response, text_status, jqXHR) {
		$('#resultHeader').css("display", "block");
		
		var element = $('#result').jScrollPane({
			showArrows: true,
			autoReinitialise: true,
			horizontalGutter: 10
		});
		
		var api = element.data('jsp');
		api.getContentPane().html(response["html"]);
		api.reinitialise();
		
	}
}

/**
* 
* Param 1: The id name of the pop up window.
* Param 2: The id name of the overlay window.
* Param 3: URL to the page you want to display in the pop up window.
**/
function getPage(popupWindowName, overlayName, url, data, dataToUpdate, readyFunc) {
	jQuery.postJSON(url, data, itemReply(popupWindowName, overlayName, dataToUpdate, readyFunc), ajaxError);
}

function itemReply(popupWindowName, overlayName, dataToUpdate, readyFunc) {
	return function(response, text_status, jqXHR) {
			if ("redirect" in response) {
				if (response["banner_text"] != false) {
					location.href=response["redirect"]+"?banner_text="+response["banner_text"];
				}
				else {
					location.href=response["redirect"];
				}
				return;
			}
			//Adding a black overlay to the page
			overlay = document.getElementById(overlayName);
			if (!overlay) {
				overlay = document.createElement('div'); 
				overlay.id = overlayName;
				document.body.appendChild(overlay);
			}
			
			//Adding a new pop-up to the page
			var popUpCreated = false;
			var popUp = document.getElementById(popupWindowName);
			if (!popUp) {
				var popUp = document.createElement('div'); 
				popUp.id = popupWindowName;
				popUpCreated = true;
			}
			popUp.innerHTML = response["html"];
			if (popUpCreated) {
				document.body.appendChild(popUp);
			}
			var height = popUp.offsetHeight;
			popUp.style.marginTop = -(height/2) + "px";
			
			for(a in dataToUpdate.replace) {
				//console.log(a);
				//console.log(dataToUpdate.replace[a]);
				if (typeof(dataToUpdate.replace[a]) == "function") {
					jQuery('#' + a, popUp).html(dataToUpdate.replace[a]())
				} else {
					jQuery('#' + a, popUp).html(dataToUpdate.replace[a])
				}
			}

			//creates a close button placed on the top right of the pop up window.
			var close = document.createElement('div'); 
			close.className = "xbutton";
			close.onclick = Function("closePopUpWindow('" + popupWindowName + "','" + overlayName + "')");
			popUp.appendChild(close);
			
			//create scrollbars
			$('.popup-scroll-pane-arrows').jScrollPane(
				{
					showArrows: true,
					horizontalGutter: 10,
					contentWidth: 500
				}
			);
			
			if (readyFunc) {
				readyFunc();
			}
		}
}

function createPopUp(heading, subHeading, text, windowName, overlayName) {
	//Adding a black overlay to the page
	var overlay = document.createElement('div'); 
	overlay.id = overlayName;
	document.body.appendChild(overlay);

	//Adding a new pop-up to the page
	var popUp = document.createElement('div'); 
	popUp.id = windowName;
	popUp.innerHTML = '<div id="popup_main_header"><h2>' + heading + '</h2></div><div id="popup_body"><div class="popup_header"><h3>' + subHeading + '</h3></div><br />' + text + '</div>';
	document.body.appendChild(popUp);
	var height = popUp.offsetHeight;
	popUp.style.marginTop = -(height/2) + "px";

	//creates a close button placed on the top right of the pop up window.
	var close = document.createElement('div'); 
	close.className = "xbutton";
	close.onclick = Function("closePopUpWindow('" + windowName + "','" + overlayName + "')");
	popUp.appendChild(close);
	
	return popUp;
}

function warningPopUp(warningText, popupWindowName, overlayName) {
	createPopUp('Warning', 'Message', warningText, popupWindowName, overlayName)
}

function statusReply(errorTextElementId, button, count, page, itemName, text) {
	return function(response, text_status, jqXHR) {
		var status = response['status'];
		var date= new Date();
		var hrs = date.getHours();
		var min = date.getMinutes();
		if (hrs <10) {
			hrs = "0"+hrs;
		}
		if (min <10) {
				min = "0"+min;
		}
		var timestamp = hrs +":"+ min;
		if (status == false) {
			$('#' + errorTextElementId).html("<p class=\"redTextColor\">Error: " +response['message'] + "</p>");
			// On error, enable the button again to allow the user to try again
			if (typeof button != "undefined") {
				freeClickOnce(button);
			}
		}
		else if (typeof count != "undefined" && typeof page != "undefined" && typeof itemName != "undefined" && typeof text != "undefined" ) {
			closePopUpWindow('popup_info', 'overlay');
			if (count == 1) {
				window.location =page + "?banner_text=At " + timestamp + " you " + text + " " + count + " unit of " + itemName + ".";
			} else {
				window.location =page + "?banner_text=At " + timestamp + " you " + text + " " + count + " units of " + itemName + ".";
			}
		} 
		else if (typeof count != "undefined" && typeof page != "undefined" && typeof itemName == "undefined" && typeof text == "undefined" ) {
			closePopUpWindow('popup_info', 'overlay');
			if (count == 1) {
				window.location = page + "?banner_text=At " + timestamp + " you withdrew " + count + " credit."; 
			} else {
				window.location = page + "?banner_text=At " + timestamp + " you withdrew " + count + " credits."; 
			}
		}
		else {
			closePopUpWindow('popup_info', 'overlay');
			var url = window.location.protocol + "//" + window.location.host + window.location.pathname;
			window.location = url;
		}
	}
}

function sellToBuyOrder(url, orderId, openPage, itemName, button) {
	if (!applyClickOnce(button)) return;
	
	var itemCount = $('#selctUnits').val();
	var slotNum = $('#slotNumber').text();

	var data = {'item_count': itemCount,
							'slot_num': slotNum,
							'order_id': orderId};

	jQuery.postJSON(url, data, statusReply("errorText", button, itemCount, openPage, itemName, 'sold'), ajaxError);
}

function buyFromSellOrder(url, orderId, button) {
	if (!applyClickOnce(button)) return;
	
	var itemCount = $('#orderUnits').val();

	var data = {'item_count': itemCount,
							'order_id': orderId};

	jQuery.postJSON(url, data, statusReply("buyOrderErrorText", button), ajaxError);
}

function withdrawItem(url, itemCount, slotNum, openPage, itemName, button) {
	if (!applyClickOnce(button)) return;
	
	var data = {'item_count': itemCount,
							'slot_num': slotNum};

	jQuery.postJSON(url, data, statusReply("withdrawItemErrorText", button, itemCount, openPage, itemName, 'withdrew'), ajaxError);
}

function withdrawCash(url, amount, openPage, button) {
	if (!applyClickOnce(button)) return;
	jQuery.postJSON(url, {'amount': amount}, statusReply("withdrawCashErrorText", button, amount, openPage),ajaxError);
}

function cancelOrder(url, orderId, button) {
	if (!applyClickOnce(button)) return;
	jQuery.postJSON(url, {'order_id': orderId}, statusReply("cancelErrorText", button), ajaxError);
}

function createSellOrder(url, clusterId, templateQL, unitPrice, itemCount, orderDuration, button)
{
	if (!applyClickOnce(button)) return;
	jQuery.postJSON(url, {'cluster_id': clusterId, 'template_ql': templateQL, 'unit_price': unitPrice, 'item_count': itemCount, 'order_duration': orderDuration}, statusReply("createSellErrorText", button), ajaxError);
}

function createBuyOrder(url, clusterId, qlLow, qlHigh, unitPrice, itemCount, orderDuration, button)
{
	if (!applyClickOnce(button)) return;
	jQuery.postJSON(url, {'cluster_id': clusterId, 'ql_low': qlLow, 'ql_high': qlHigh,'unit_price': unitPrice, 'item_count': itemCount, 'order_duration': orderDuration}, statusReply("createBuyErrorText", button), ajaxError);
}

function createDropDownSelect(selectID, selectValueMap, selectOnChangeScript) {
	var html = "<select id=" + selectID + " onchange=\"" + selectOnChangeScript + "\">";
	for (key in selectValueMap) {
		html += "  <option value=\"" + selectValueMap[key] + "\">" + key + "</option>";
	}
	html += "</select>";
	return html;
}

function createMinMaxDropDownSelect(selectID, minValue, maxValue, selectOnChangeScript)
{
	var values = {};
	for (var i = minValue; i <= maxValue; i++) {
		values[i] = i;
	}
	return createDropDownSelect(selectID, values, selectOnChangeScript);
}

function getMinMaxDropDownSelectGenerator(selectID, minValue, maxValue, selectOnChangeScript)
{
	return function() { return createMinMaxDropDownSelect(selectID, minValue, maxValue, selectOnChangeScript); }
}

function replaceHTML(id, html) {
	var elementId = document.getElementById(id);
	elementId.innerHTML = html;
}

/**
* This function will remove two specified div files from the website.
* Param 1: The id name of the pop up window.
* Param 2: The id name of the overlay window.
**/
function closePopUpWindow(popupWindowName, overlayName) {
	var popupWindow = document.getElementById(popupWindowName);
	if (popupWindow != null) {
		popupWindow.parentNode.removeChild(popupWindow);
	}
	
	var overlay = document.getElementById(overlayName);
	if (overlay != null) {
		overlay.parentNode.removeChild(overlay);
	}
	
}

function highlight(radioButton) {
	var tr = $(radioButton).parent().parent();
	jQuery(".highlight", tr.parent()).attr('class', '');
	tr.attr('class', 'highlight');
}

function calculateTax(price, taxMultiplier, taxCap)
{
	var tax = price * taxMultiplier;
	return Math.min(tax, taxCap);
}

function calculateOrderTotals(priceSelector, unitsSelector, taxMultiplierSelector, taxCapSelector, totalTaxSelector, totalPriceSelector)
{
	var price = Globalize.parseInt($(priceSelector).text());
	var units = Globalize.parseInt($(unitsSelector).val());
	var taxMultiplier = Globalize.parseFloat($(taxMultiplierSelector).text()) / 100.0;
	var taxCap = Globalize.parseInt($(taxCapSelector).text());

	var totalPrice = price * units;
	var tax = calculateTax(totalPrice, taxMultiplier, taxCap);
	totalPrice = totalPrice - tax;
	
	$(totalTaxSelector).html(Globalize.format(tax, "n0"));
	$(totalPriceSelector).html(Globalize.format(totalPrice, "n0"));
}

function calculateAdjustmentFee(oldPrice, newPrice, units, adjustmentFeePercentage, adjustmentFeeMinimum)
{
	var diffPrice = newPrice - oldPrice;
	var fee = diffPrice * units * (adjustmentFeePercentage / 100.0);
	if (fee < adjustmentFeeMinimum) {
		fee = adjustmentFeeMinimum;
	}
	return fee;
}

function calculateSellAdjustmentFee(originalPriceSelector, newPriceSelector, unitsSelector, adjustmentFeePercentageSelector, adjustmentFeeMinimumSelector, totalFeeSelector)
{
	var oldPrice = Globalize.parseInt($(originalPriceSelector).text());
	var newPrice = Globalize.parseInt($(newPriceSelector).val());
	var units = Globalize.parseInt($(unitsSelector).text());
	var adjustmentFeePercentage = Globalize.parseFloat($(adjustmentFeePercentageSelector).text());
	var adjustmentFeeMinimum = Globalize.parseInt($(adjustmentFeeMinimumSelector).text());
	
	var fee = calculateAdjustmentFee(oldPrice, newPrice, units, adjustmentFeePercentage, adjustmentFeeMinimum);
	
	$(totalFeeSelector).html(Globalize.format(fee, "n0"));
}

function calculateBuyAdjustmentFee(originalPriceSelector, newPriceSelector, unitsSelector, adjustmentFeePercentageSelector, adjustmentFeeMinimumSelector, totalFeeSelector, priceDeltaSelector, totalSelector)
{
	var oldPrice = Globalize.parseInt($(originalPriceSelector).text());
	var newPrice = Globalize.parseInt($(newPriceSelector).val());
	var units = Globalize.parseInt($(unitsSelector).text());
	var adjustmentFeePercentage = Globalize.parseFloat($(adjustmentFeePercentageSelector).text());
	var adjustmentFeeMinimum = Globalize.parseInt($(adjustmentFeeMinimumSelector).text());
	
	var fee = calculateAdjustmentFee(oldPrice, newPrice, units, adjustmentFeePercentage, adjustmentFeeMinimum);
	var priceDelta = (newPrice - oldPrice) * units;
	var total = priceDelta + fee;
	
	$(totalFeeSelector).html(Globalize.format(fee, "n0"));
	$(priceDeltaSelector).html(Globalize.format(priceDelta, "n0"));
	$(priceDeltaSelector).attr('class', ((priceDelta < 0) ? 'greenInline' : 'redInline'));
	$(totalSelector).html(Globalize.format(total, "n0"));
	$(totalSelector).attr('class', ((total < 0) ? 'greenInline' : 'redInline'));
}

function modifySellOrder(url, orderId, unitPrice, button)
{
	if (!applyClickOnce(button)) return;
	jQuery.postJSON(url, {'order_id': orderId, 'unit_price': unitPrice}, statusReply("modifySellErrorText", button), ajaxError);
}

function modifyBuyOrder(url, orderId, unitPrice, button)
{
	if (!applyClickOnce(button)) return;
	jQuery.postJSON(url, {'order_id': orderId, 'unit_price': unitPrice}, statusReply("modifyBuyErrorText", button), ajaxError);
}


/**
* New Code
*/

//Add tooltips to the "Name" field on the "Log" page to help with long item names.
if(document.location.href.substring(document.location.href.lastIndexOf("/")+1).indexOf("log") == 0){
	$(document).ajaxComplete(function(){
		$('div#mySales table tr').each(function(index, value){
			// console.log($($(value).children()[1]));
			$($(value).children()[1]).each(function(index, value){
				if($(value).html().length >= 22)
					$(value).attr('title', $(value).html());
			});
		})
		$(document).tooltip();
	});
}


if(document.location.href.substring(document.location.href.lastIndexOf("/")+1).indexOf("my_orders") == 0){
	$(document).ajaxComplete(function(){
		var numSellOrdersSelected = $('input[name="selectedSellOrders"]:checked').length;
		var numBuyOrdersSelected = $('input[name="selectedSellOrders"]:checked').length;

		$('input[name="selectedSellOrder"]').each(function(index, value){
			$(value).replaceWith("<input type='checkbox' name='selectedSellOrders' value='" + $(value).val() + "'>");
		});
		$('input[name="selectedBuyOrder"]').each(function(index, value){
			$(value).replaceWith("<input type='checkbox' name='selectedBuyOrders' value='" + $(value).val() + "'>");
		});

		//Add ID's to our buttons, because why not.
		$($('#sellButtons').children()[0]).attr('id', 'cancelSaleButton');
		$($('#sellButtons').children()[1]).attr('id', 'modifySaleButton');
		$($('#sellButtons').children()[2]).attr('id', 'saleDetailsButton');
		$($('#buyButtons').children()[0]).attr('id', 'cancelBuyButton');
		$($('#buyButtons').children()[1]).attr('id', 'modifyBuyButton');
		$($('#buyButtons').children()[2]).attr('id', 'buyDetailsButton');

		//Sanitize our buttons so we can make them work how we want them to.
		$('#sellButtons').children().each(function(index, element){
			$(element).prop("onclick", null).unbind('click');
			$(element).prop("onMouseDown", null).unbind('mousedown');
			$(element).prop("onmouseout", null).unbind('mouseout');
			$(element).prop("onMouseUp", null).unbind('mouseup');
		})
		$('#buyButtons').children().each(function(index, element){
			$(element).prop("onclick", null).unbind('click');
			$(element).prop("onMouseDown", null).unbind('mousedown');
			$(element).prop("onmouseout", null).unbind('mouseout');
			$(element).prop("onMouseUp", null).unbind('mouseup');
		})

//I'm sure there's a better way to do these next two click actions. Refactor later. 
		$('#sellButtons').children().hide();
		$('input[name="selectedSellOrders"]').click(function(){
			numSellOrdersSelected = $('input[name="selectedSellOrders"]:checked').length;
			$('#sellButtons').removeClass('bottomButtons');
			// $('#sellButtons').children().css('opacity', '0.25');
			$('#sellButtons').children().hide();

		
			if(numSellOrdersSelected == 0){
				//Do nothing
			} else if(numSellOrdersSelected == 1){
				// $('#sellButtons').children().css('opacity', '1.0');
				$('#sellButtons').children().show();
			} else if(numSellOrdersSelected > 1){
				// $("#cancelSaleButton").css('opacity', '1.0');
				$("#cancelSaleButton").show();
			}
		});

		$('#buyButtons').children().hide();
		$('input[name="selectedBuyOrders"]').click(function(){
			numBuyOrdersSelected = $('input[name="selectedBuyOrders"]:checked').length;
			$('#buyButtons').removeClass('bottomButtons');
			$('#buyButtons').children().hide();
			// $('#buyButtons').children().css('opacity', '0.25');
		
			if(numBuyOrdersSelected == 0){
				//Do nothing
			} else if(numBuyOrdersSelected == 1){
				$('#buyButtons').children().show();
				// $('#buyButtons').children().css('opacity', '1.0');
			} else if(numBuyOrdersSelected > 1){
				$('#cancelBuyButton').show();
				// $("#cancelBuyButton").css('opacity', '1.0');
			}
		});

		//Refactor later. Broken record much? 
		$('#cancelSaleButton').click(function(event){
			if(numSellOrdersSelected > 0){
				var selectedItems = [];
				$('input[name="selectedSellOrders"]:checked').each(function(index, element){
					var item = {};
					var parent = $(element).parent().parent();
					item.order_id = $(parent).attr('order_id');
					item.icon_html = $(parent).find('#icon_url').find('img').attr('src');
					item.name = $(parent).find('#item_name').text();
					item.count = Globalize.parseInt($(parent).find('#item_count').text());
					//Watch out here when refactoring: See below in #cancelBuyButton.click()
					item.price = Globalize.parseInt($(parent).find('#selling_price').text());
					item.order_value = Globalize.format(item.count * item.price, "n0");
					selectedItems.push(item);
				});
				confirmCancelSO(selectedItems);
			} else {
				warningPopUp('<p>You must select at least one sell order to cancel.</p><br />', 'popup_info', 'overlay');
			}
		});

		$('#cancelBuyButton').click(function(event){
			if(numBuyOrdersSelected > 0){
				var selectedItems = [];
				$('input[name="selectedBuyOrders"]:checked').each(function(index, element){
					var item = {};
					var parent = $(element).parent().parent();
					item.order_id = $(parent).attr('order_id');
					item.icon_html = $(parent).find('#icon_url').find('img').attr('src');
					item.name = $(parent).find('#item_name').text();
					item.count = Globalize.parseInt($(parent).find('#item_count').text());
					//Watch out here when refactoring: #buying_price instead of #selling_price
					item.price = Globalize.parseInt($(parent).find('#buying_price').text());
					item.order_value = Globalize.format(item.count * item.price, "n0");
					selectedItems.push(item);
				});
				confirmCancelBO(selectedItems);
			} else {
				warningPopUp('<p>You must select at least one buy order to cancel.</p><br />', 'popup_info', 'overlay');
			}
		});

		//Refactor later.
		//Modify window doesn't display the name of the item you're changing? Maybe something to add in later...
		$('#modifySaleButton').click(function(event){
			if(numSellOrdersSelected == 1){
				modify_sell_click($('input[name="selectedSellOrders"]:checked'))
			} else if(numSellOrdersSelected > 1){
				warningPopUp('<p>You may only modify one sell order at a time.</p><br />', 'popup_info', 'overlay');
			} else {
				warningPopUp('<p>You must choose a sell order to modify.</p><br />', 'popup_info', 'overlay');
			}
		});

		$('#modifyBuyButton').click(function(event){
			if(numBuyOrdersSelected == 1){
				modify_buy_click($('input[name="selectedBuyOrders"]:checked'))
			} else if(numBuyOrdersSelected > 1){
				warningPopUp('<p>You may only modify one buy order at a time.</p><br />', 'popup_info', 'overlay');
			} else {
				warningPopUp('<p>You must choose a buy order to modify.</p><br />', 'popup_info', 'overlay');
			}
		});

		//Refactor later.
		$('#saleDetailsButton').click(function(event){
			if(numSellOrdersSelected == 1){
				open_details('/marketLIVE/item_orders/', $('input[name="selectedSellOrders"]:checked').parent().parent().attr('cluster_id'))
			} else if(numSellOrdersSelected > 1){
				warningPopUp('<p>You may only view the details of one sell order at a time.</p><br />', 'popup_info', 'overlay');
			} else {
				warningPopUp('<p>You must choose a sell order to view.</p><br />', 'popup_info', 'overlay');
			}
		});

		$('#buyDetailsButton').click(function(event){
			if(numBuyOrdersSelected == 1){
				open_details('/marketLIVE/item_orders/', $('input[name="selectedBuyOrders"]:checked').parent().parent().attr('cluster_id'))
			} else if(numBuyOrdersSelected > 1){
				warningPopUp('<p>You may only view the details of one buy order at a time.</p><br />', 'popup_info', 'overlay');
			} else {
				warningPopUp('<p>You must choose a buy order to view.</p><br />', 'popup_info', 'overlay');
			}
		});

	});
}

	//Refactor later. Jesus, so much refactoring. 
	function confirmCancelSO(orders) {
			getPage('popup_info', 'overlay', '/market/cancel_order.php', {'orders': orders, 'type': 'sell'}, {});
	}

	function confirmCancelBO(orders) {
			getPage('popup_info', 'overlay', '/market/cancel_order.php', {'orders': orders, 'type': 'buy'}, {});
	}

	function shouldRefreshPage(done, total){
		if(done == total){
			location.reload();
		}
	}

	function cancelOrders(url, orders) {
		var done = 0;
		jQuery.each(orders.numbers, function(index, value){
			jQuery.postJSON(url, {'order_id': value}, function(data){
				done++;
				shouldRefreshPage(done, orders.numbers.length);
			}, ajaxError);
		})
	}

	$(document).ajaxComplete(function(event, xhr, settings ) {
		if (settings.url === "/marketLIVE/item_view" ) {
			var itemName = $($('div#popup_main_header').children()[0]).html();
			var itemId = JSON.parse(settings.data).cluster_id
			var itemQL = $('tbody tr td select option:selected').val();

			$($('div#popup_main_header').children()[0]).append('<img alt="Look up item in Auno - Beta" id="load_auno" style="padding-left: 10px; top: 3px; position: relative;" src="/staticLIVE/images/auno.png">');
			$($('div#popup_main_header').children()[0]).append('<img alt="View Item Statistics - Beta" id="item_stats" style="height: 16px; width: 16px; padding-left: 10px; top: 3px; position: relative;" src="/staticLIVE/images/stats.png">');
			$('#load_auno').click(function(event){
				jQuery.getJSON("http://aomarket.funcom.com/market/item_search.php", {"itemName": itemName, "ql": itemQL}, function(data){
					if(typeof data.results[0] !== "undefined"){
						document.location = "http://auno.org/ao/db.php?id=" + data.results[0].HighID;
					} else {
						warningPopUp("<p>Could not find item on Auno.</p><br />", "popup_info", "overlay");
					}
				});
			})

			$('#item_stats').click(function(event){
				document.location = '/market/item_stats.php?itemName=' + itemName + "&itemId=" + itemId;
			});

		}
	});
