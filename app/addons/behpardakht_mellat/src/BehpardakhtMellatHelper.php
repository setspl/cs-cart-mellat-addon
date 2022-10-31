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

namespace Tygh\Addons\Behpardakht_mellat;

class BehpardakhtMellatHelper
{
    /**
     * Include timestap with order id
     */
    public static function getTransactionId($order_id)
    {
        if ($order_id) {
            return $order_id . '_' . date("YmdHis");
        }

        return $order_id;
    }

    /**
     * Exclude timestap with order id
     */
    public static function getOrderId($order_id)
    {
        if (($pos = strrpos($order_id, '_')) !== false) {
            $order_id = substr($order_id, 0, $pos);
        }

        return $order_id;
    }

    public static function getHash($params, $salt)
    {
        if (is_array($params)) {
            $key = $params['key'];
            $txnid = $params['txnid'];
            $amount = $params['amount'];
            $productinfo = "Shopping";
            $firstname = $params['firstname'];
            $email = $params['email'];
            $udf1 = $params['udf1'];
            $udf2 = $params['udf2'];
            $udf3 = $params['udf3'];
            $udf4 = $params['udf4'];
            $udf5 = $params['udf5'];

            $keyString = "$key|$txnid|$amount|$productinfo|$firstname|$email|$udf1|$udf2|$udf3|$udf4|$udf5||||||";

            return hash(BehpardakhtMellatConstants::ENCRYPTION_ALGORITHM, $keyString . $salt);
        }

        return "";
    }

    public static function formatPhoneNumber($phone_number)
    {
        return preg_replace('/[^0-9]/', '', $phone_number);
    }
    
    public static function verifyPayment($key, $salt, $txnid, $status)
    {
        $command = "";
    }
}