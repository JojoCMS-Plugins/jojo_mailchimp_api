<?php
/**
 * Jojo CMS - MailChimp API
 *
 * Copyright 2011 Jojo CMS
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */


Jojo::addHook('jojo_cart_success',              'jojo_cart_success',              'jojo_mailchimp_api');
Jojo::addHook('jojo_cart_extra_fields_billing', 'jojo_cart_extra_fields_billing', 'jojo_mailchimp_api');
Jojo::addFilter('jojo_cart_checkout:get_fields', 'jojo_cart_checkout_get_fields', 'jojo_mailchimp_api');

$_options[] = array(
    'id'          => 'mailchimp_api_key',
    'category'    => 'MailChimp',
    'label'       => 'MailChimp API key',
    'description' => 'Create an API key on the ACCOUNT menu in MailChimp',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jojo_mailchimp_api'
);

$_options[] = array(
    'id'          => 'mailchimp_cart_list_id',
    'category'    => 'MailChimp',
    'label'       => 'MailChimp List ID',
    'description' => 'Customers who place a successful order will be subscribed to this list. Must match a valid list in your MailChimp account',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jojo_mailchimp_api'
);

$_options[] = array(
    'id'          => 'mailchimp_cart_subscribe_type',
    'category'    => 'MailChimp',
    'label'       => 'MailChimp Subscribe type',
    'description' => 'Automatic = customers are subscribed automatically after purchase. Ask after = customers are shown a subscribe button after purchase. Ask yes = custoers are shown a tickbox defaulting to yes, Ask no = custoers are shown a tickbox defaulting to no',
    'type'        => 'radio',
    'default'     => 'automatic',
    'options'     => 'automatic,ask yes,ask no',//"ask after" option to be added also
    'plugin'      => 'jojo_mailchimp_api'
);

$_options[] = array(
    'id'          => 'mailchimp_cart_subscribe_message',
    'category'    => 'MailChimp',
    'label'       => 'MailChimp subscribe message',
    'description' => 'Displayed by the checkbox asking customers if they wish to subscribe eg Would you like to subscribe to our newsletter',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jojo_mailchimp_api'
);