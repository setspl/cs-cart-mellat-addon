<?php
/***************************************************************************
*                                                                          *
*   (c) 2022 Salasar eCommerce Total Solutions Pvt Ltd                     *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

use Tygh\Addons\Behpardakht_mellat\BehpardakhtMellatConstants;
use Tygh\Addons\Behpardakht_mellat\BehpardakhtMellatHelper;
use Tygh\Enum\OrderStatuses;
use Tygh\Enum\YesNo;
use Tygh\Http;
// use Tygh\Addons\Mehpardakht_mellat\MiladRahimi\PhpMellatBank\Gateway;
use SoapClient;


if (!defined('BOOTSTRAP')) { die('Access denied'); }

if (defined('PAYMENT_NOTIFICATION')) {
    $order_id = ! empty($_POST['txnid']) ? BehpardakhtMellatHelper::getOrderId($_POST['txnid']) : 0;

    if (! fn_check_payment_script('behpardakht_mellat.php', $order_id)) {
        die('Access denied');
    }

    $order_info = fn_get_order_info($order_id);

    if ($mode === 'success') {
        if ($_REQUEST['status'] === 'success') {
            $pp_response['order_status'] = OrderStatuses::PAID;
		    $pp_response['reason_text'] = "Thank you. Your order has been processed successfully.";
        }
    } else if ($mode === 'failed') {
        if ($_REQUEST['status'] === 'failure') {
            $pp_response['order_status'] = OrderStatuses::DECLINED;
		    $pp_response['reason_text'] = "Thank you. Your order has been declined due to security reasons.";
        }
    } else if ($mode === 'cancel') {
        if ($_REQUEST['status'] === 'failure') {
            $pp_response['order_status'] = OrderStatuses::DECLINED;
		    $pp_response['reason_text'] = "Thank you. Your order has been declined due to security reasons.";
        }
    }

    if ($order_info['user_id'] != 0) {
		fn_login_user($order_info['user_id']);
	}

    fn_change_order_status($order_id, $pp_response['order_status']);
    fn_finish_payment($order_id, $pp_response, array());
    fn_order_placement_routines('route', $order_id);
    
    exit;
} else {
    $processorParams = $processor_data['processor_params'];

    $amount = fn_format_price_by_currency(
        $order_info['total'],
        CART_PRIMARY_CURRENCY,
        BehpardakhtMellatConstants::SUPPORTED_CURRENCY
    );
    $url     = BehpardakhtMellatConstants::URL;
    $params  = [
        'terminalId' => BehpardakhtMellatConstants::TERMINALID,
        'userName' => BehpardakhtMellatConstants::USERNAME,
        'userPassword' => BehpardakhtMellatConstants::PASSWORD,
        'amount' => $amount,
        'localDate' => date('Ymd'),
        'localTime' => date('His'),
        'additionalData' => 'Have 3 items in basket',
        'callBackUrl' => 'http://www.mysite.com/myfolder/callbackmellat.aspx',
        'payerId' => 0,
        'orderId' => $order_id
    ];
    
    $client = new SoapClient($url);
    
    $result = $client->bpPayRequest($params);
    $result = json_encode($result);
    // var_dump($result); die;
    // fn_redirect($respone, true);

    // fn_set_notification('W', __('important'), ('Something went wrong. Please contact administrator.'));
    
    fn_redirect('checkout.checkout');
    
    exit;
}