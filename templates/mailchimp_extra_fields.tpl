            <label for="mailchimp_subscribe">Subscribe</label>
            <input type="checkbox" name="mailchimp_subscribe" id="mailchimp_subscribe" value="1"{if $OPTIONS.mailchimp_cart_subscribe_type == 'ask yes'} checked="checked"{/if} /> {$OPTIONS.mailchimp_cart_subscribe_message|default:'Subscribe to our newsletter?'}<br />
            