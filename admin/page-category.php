<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

	header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	require("includes/visitickets.php");

	use includes\classes\controllers\CategoryController;
	use includes\classes\models\Cart;
    use includes\classes\models\Event;

    $response = array("content" => "ERROR");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-category.htm");

	$category = new CategoryController();
	$cart = new Cart();
    $basket_items = $cart->items;
///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

	$contentid = $_POST['contentid'] ?? null;

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET CATEGORY DETAILS///
///*****************************************************************************///

		if($contentid)
		{
		    unset($_SESSION['selected_date']);

		    $category = $category->view($contentid);
		    $items = $category->items();

            $PHPTemplateLayer->assign("categorytitle",$category->name);

			if($category->description != "")
			{
			$PHPTemplateLayer->block("DESCRIPTION");
			$PHPTemplateLayer->assign("categorydescription",$category->description);
			}

            $show_donations_toggle = false;
            $show_total_row = false;

            if (!empty($items))
            {
                foreach ($items as $item)
                {
                    if ($item->itemType !== 3) {
                        if (!isset($item->item->sellOnline) || !$item->item->sellOnline) {
                            continue;
                        }
                    }

                    $PHPTemplateLayer->block("ITEM");

                    if ($item->itemType == 3) {
                        $PHPTemplateLayer->block("ITEMEVENT");
                    } else {
                        $PHPTemplateLayer->block("ITEMPRODUCT");
                        $show_total_row = true;
                    }

                    $itempicture = SETTING_DEFAULTIMAGE;

                    if(isset($item->imageFileName))
                    {
                        $itempicture = trailingslashit(API_URL) . "images/" . $item->imageFileName;
                    }

                    $donation_price = $item->item->onlinePrice ?? 0;

                    if (isset($item->item) && !$item->item->donationItem && isset($item->item->donationRate)) {
                        if ($item->item->donationRate->type === 1 && isset($item->item->donationRate->amount) && $item->item->donationRate->amount > 0) { // fixed amount donation rate
                            $donation_price = price($item->item->onlinePrice + $item->item->donationRate->amount);

                            $show_donations_toggle = true;
                        }

                        if ( $item->item->donationRate->type === 2 && isset($item->item->donationRate->percentage) && $item->item->donationRate->percentage > 0) { // percentage donation rate
                            $donation_price = price($item->item->onlinePrice + $item->item->onlinePrice / 100 * $item->item->donationRate->percentage);

                            if (isset($item->item->donationRate->roundUpTo) && $item->item->donationRate->roundUpTo > 0) {
                                $donation_price = ceil($donation_price / $item->item->donationRate->roundUpTo) * $item->item->donationRate->roundUpTo;
                            }

                            $show_donations_toggle = true;
                        }
                    }

                    $PHPTemplateLayer->assign("name",$item->name);
                    $PHPTemplateLayer->assign("description", get_content_shortversion($item->description,75));
                    $PHPTemplateLayer->assign("picture",$itempicture);
                    $PHPTemplateLayer->assign("id",$item->id);
                    $PHPTemplateLayer->assign("price", show_prices_without_donation() ? price($item->item->onlinePrice) : price($donation_price));
                    $PHPTemplateLayer->assign("donationprice", price($donation_price));
                    $PHPTemplateLayer->assign("nodonationprice", isset($item->item) ? price($item->item->onlinePrice) : null);
                    $PHPTemplateLayer->assign("minquantity", isset($item->item->purchaseQuantityMinimum) ? $item->item->purchaseQuantityMinimum : 1);

                    // remove used item
                    $maxquantity = isset($item->item->purchaseQuantityMaximum) ? $item->item->purchaseQuantityMaximum : 99;
                    if(!empty($basket_items)){
                        // echo "<pre>";
                        // var_dump($basket_items);
                        // echo "<br>";
                        // var_dump($item->item);
                        // echo "----------------------------------------";
                        foreach($basket_items as $row){
                            if($row->itemId == $item->item->id){
                                $maxquantity -= $row->quantity;
                            }
                        }
                    }

                    $PHPTemplateLayer->assign("maxquantity", $maxquantity);

                    if ($item->itemType == 3) {
                        $event = new Event($item->id);
                        if (count($event->getSessions())) {
                            $PHPTemplateLayer->block("BOOKTICKETS");
                            $PHPTemplateLayer->assign("id",$item->id);
                            $PHPTemplateLayer->assign("minprice", price($event->minPrice));
                        }
                    }
                }
            }

            if ($show_donations_toggle) {
                $PHPTemplateLayer->block("DONATIONPRICETOGGLE");
                $PHPTemplateLayer->assign('checked',  show_prices_without_donation() ? '' : 'checked');
            }

            if ($show_total_row) {
                $PHPTemplateLayer->block("TOTAL");
            }

            $PHPTemplateLayer->assignGlobal("items", $cart->itemCount);

            if (count($items) === 1 && !$show_total_row) {
                $response['redirect'] = 'page-event.php';
                $response['contentid'] = $items[0]->id;
            } else {
                $response["content"] = $PHPTemplateLayer->display('VARIABLE', '', 'MINIFY');
            }
        }

    echo json_encode($response);