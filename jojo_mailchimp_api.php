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

class Jojo_Plugin_Jojo_Mailchimp_API extends Jojo_Plugin
{
    function subscribe($email, $list_id=false, $merge_vars=NULL)
    {
        if (!$list_id) $list_id = Jojo::getOption('mailchimp_cart_list_id', false);
        if (empty($list_id)) return false;
        
        $key = Jojo::getOption('mailchimp_api_key', false);
        if (empty($key)) return false;
        
        foreach (Jojo::listPlugins('external/mailchimp-api-class/MCAPI.class.php') as $pluginfile) {
            require_once($pluginfile);
            break;
        }
        
    	$api = new MCAPI($key);  
    	/* note the double-opt-in is disabled here */
    	return $api->listSubscribe($list_id, $email, $merge_vars, 'html', false); //args = ($id, $email_address, $merge_vars=NULL, $email_type='html', $double_optin=true, $update_existing=false, $replace_interests=true, $send_welcome=false)
    }
    
    function jojo_cart_success($cart)
    {
        $list_id = Jojo::getOption('mailchimp_cart_list_id', false);
        if (!$list_id) return false; //an empty value means customers aren't to be added to any list
        
        /* get billing email address from cart */
        $email = $cart->fields['billing_email'];
        if (empty($email) || !Jojo::checkEmailFormat($email)) return false; //one would hope email address is valid at this point
        
        /* attempt to subscribe */
        return self::subscribe($email, $list_id, array('FNAME' => $cart->fields['billing_firstname'], 'LNAME' => $cart->fields['billing_firstname']));        
    }
    
}