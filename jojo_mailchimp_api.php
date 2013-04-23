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
    function subscribe($email, $list_id=false, $merge_vars=NULL, $update=false)
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
    	return $api->listSubscribe($list_id, $email, $merge_vars, 'html', false, $update); //args = ($id, $email_address, $merge_vars=NULL, $email_type='html', $double_optin=true, $update_existing=false, $replace_interests=true, $send_welcome=false)
    }
    
    function subscriberInfo($email=array(), $list_id=false)
    {
        if (!$list_id) $list_id = Jojo::getOption('mailchimp_cart_list_id', false);
        if (empty($list_id)) return false;
        
        $key = Jojo::getOption('mailchimp_api_key', false);
        if (empty($key)) return false;
        
		if(count($email) < 1){
			return false; //no email address to check
		}
		
        foreach (Jojo::listPlugins('external/mailchimp-api-class/MCAPI.class.php') as $pluginfile) {
            require_once($pluginfile);
            break;
        }
        
    	$api = new MCAPI($key);  
    	return $api->listMemberInfo($list_id, $email); //args = ($id, $email_address array)
    }
	
    function jojo_cart_success($cart)
    {
        $list_id = Jojo::getOption('mailchimp_cart_list_id', false);
        if (!$list_id) return false; //an empty value means customers aren't to be added to any list
        
        $subscribe_type = Jojo::getOption('mailchimp_cart_subscribe_type', 'no');
        
        if (($subscribe_type == 'automatic') || (!empty($cart->fields['mailchimp_subscribe']) && ($cart->fields['mailchimp_subscribe'] == 1))) {
            /* get billing email address from cart */
            $email = $cart->fields['billing_email'];
            if (empty($email) || !Jojo::checkEmailFormat($email)) return false; //one would hope email address is valid at this point
            
            /* attempt to subscribe */
            return self::subscribe($email, $list_id, array('FNAME' => $cart->fields['billing_firstname'], 'LNAME' => $cart->fields['billing_firstname']));
        }
        return false;        
    }
    
    function jojo_cart_extra_fields_billing()
    {
        global $smarty;
        $subscribe_type = Jojo::getOption('mailchimp_cart_subscribe_type', 'no');
        if (($subscribe_type == 'ask yes') || ($subscribe_type == 'ask no')) {
            return $smarty->fetch('mailchimp_extra_fields.tpl');
        }
        return '';
    }
    
    function jojo_cart_checkout_get_fields($fields)
    {
        $fields[] = 'mailchimp_subscribe';
        return $fields;
    }
    
	function jojo_optin(){
		$optin = Jojo::getOption('mailchimp_optin_1form', false);
		if(!$optin || $optin != 'yes'){
			return false;
		}
		$formid = Jojo::getOption('mailchimp_optin_5formid', false);
		if(!$formid || empty($formid) || $formid != $_POST['form_id']){
			return false;
		}
		$group = Jojo::getOption('mailchimp_optin_2group', false);
		if(!$group || empty($group)){
			return false;
		}
		$subgroup = Jojo::getOption('mailchimp_optin_3subgroup', false);
		if(!$subgroup || empty($subgroup)){
			return false;
		}
		$dateField = Jojo::getOption('mailchimp_optin_4datefield', false);
		if(!$dateField || empty($dateField)){
			return false;
		}
		$lastnameField = Jojo::getOption('mailchimp_optin_7lastname', false);
		if(!$lastnameField || empty($lastnameField)){
			return false;
		}
		$firstnameField = Jojo::getOption('mailchimp_optin_6firstname', false);
		if(!$firstnameField || empty($firstnameField)){
			return false;
		};
		$emailField = Jojo::getOption('mailchimp_optin_8email', false);
		if(!$emailField || empty($emailField)){
			return false;
		};
		
		//Check if already subscribed here 
		$inList = self::subscriberInfo(array($_POST[$emailField]));
		
		$merge_vars = array( //Get form data to come throuh and merge it here
				'FNAME'=>$_POST[$firstnameField],
				'LNAME'=> $_POST[$lastnameField],
				'GROUPINGS'=>array( array(
						'name'=>$group, //Group name in mailchimp hardcoded at the moment?
						'groups'=>$subgroup),) //Subgroup must match the mailchimp sub group
				);
				
		if(isset($inList['success']) && $inList['success'] == 1){
			$addDate = True;
			foreach ($inList['data'][0]['merges']['GROUPINGS'] as $key=>$value) {
				if($value['name'] == $group && $value['groups'] == $subgroup) {
					$addDate = False;
				}
			}
			if($addDate){
				$merge_vars[$dateField] = date('Y-m-d');
			}
		} else {
			$merge_vars[$dateField] = date('Y-m-d');
		}
		
		/* attempt to subscribe */
        $subscribe = self::subscribe($_POST[$emailField], false, $merge_vars, true);
		
		//Possibly set variables here to protect the optin data from display if not set
		//Or setup a default login to restrict permission to download files by.
	}
}