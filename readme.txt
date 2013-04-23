This simple plugin does 3 things.

1. Makes the MailChimp API available.
Documentation for this is available on the Mailchimp site, or in MCAPI.class.php

To get started, try the following code...

$key = Jojo::getOption('mailchimp_api_key', false);
foreach (Jojo::listPlugins('external/mailchimp-api-class/MCAPI.class.php') as $pluginfile) {
    require_once($pluginfile);
    break;
}
$api = new MCAPI($key);  
$api->........do something here.....

The MailChimp API key should be entered into the options, and can be retrieved using...
Jojo::getOption('mailchimp_api_key', false);


2. Any customers who complete a purchase are added to the MailChimp list. In order for this to be acive, the following must be done...
- jojo_cart installed
- Enter the relevant MailChimp List ID into the Jojo options. You can find the list ID under the list management section of MailChimp.
- run setup to activate the hook

3. Provide options for a form to act as an optin device.
- set the form id
- set the group and subgroup and date field that the autoresponders run from.
(if an email is already in the group / subgroup then the date field is not updated to prevent resetting the auto response chain)
- set the field names to go to mailchimp list for first name, last name and email address