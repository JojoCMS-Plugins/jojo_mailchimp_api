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


Jojo::addHook('jojo_cart_success', 'jojo_cart_success', 'jojo_mailchimp_api');

$_options[] = array(
    'id'          => 'mailchimp_api_key',
    'category'    => 'MailChimp',
    'label'       => 'MailChimp API key',
    'description' => 'Create an API key on the ACCOUNT menu in MailChimp',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'empty_plugin'
);

$_options[] = array(
    'id'          => 'mailchimp_cart_list_id',
    'category'    => 'MailChimp',
    'label'       => 'MailChimp List ID',
    'description' => 'Customers who place a successful order will be subscribed to this list. Must match a valid list in your MailChimp account',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'empty_plugin'
);