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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Tygh;

/**
 * Install PayU payment processor.
 * 
 * @return void
 */
function fn_behpardakht_mellat_install()
{
    /**
     * @var \Tygh\Database\Connection $db
     */
    $db = Tygh::$app['db'];

    if ($db->getField('SELECT processor_id FROM ?:payment_processors WHERE processor_script = ?s', 'behpardakht_mellat.php')) {
        return;
    }

    $db->query(
        "INSERT INTO ?:payment_processors ?e",
        [
            'processor'          => 'Behpardakht_mellat',
            'processor_script'   => 'behpardakht_mellat.php',
            'processor_template' => 'views/orders/components/payments/cc_outside.tpl',
            'admin_template'     => 'behpardakht_mellat.tpl',
            'callback'           => 'N',
            'type'               => 'P',
            'addon'              => 'behpardakht_mellat',
        ]
    );
}

/**
 * Disables PayU payment methods upon add-on uninstallation.
 * 
 * @return void
 */
function fn_behpardakht_mellat_uninstall()
{
    /**
     * @var \Tygh\Database\Connection $db
     */
    $db = Tygh::$app['db'];

    $processor_id = $db->getField(
        "SELECT processor_id FROM ?:payment_processors WHERE processor_script = ?s",
        "behpardakht_mellat.php"
    );

    if (! $processor_id) {
        return;
    }

    $db->query("DELETE FROM ?:payment_processors WHERE processor_id = ?i", $processor_id);

    $db->query(
        "UPDATE ?:payments SET ?u WHERE processor_id = ?i",
        [
            'processor_id'     => 0,
            'processor_params' => '',
            'status'           => 'D',
        ],
        $processor_id
    );
}
