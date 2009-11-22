<?php
require_once('../library/recurly.php');


class BillingInfoTestCase extends UnitTestCase {
    
    function setUp() {
		$acct = new RecurlyAccount(strval(microtime(true)) . '-billing', 'user', 'test@test.com', 'Verena', 'Test');
		$this->acct = $acct->create();
    }
    
    function tearDown() {
    }

	function testUpdateBillingInfo() {
		$acct = $this->acct;
		$billing_info = new RecurlyBillingInfo($acct->account_code);
		$billing_info->first_name = $acct->first_name;
		$billing_info->last_name = $acct->last_name;
		$billing_info->address1 = '123 Test St';
		$billing_info->city = 'San Francisco';
		$billing_info->state = 'CA';
		$billing_info->country = 'US';
		$billing_info->zip = '94105';
		$billing_info->credit_card->number = '4111111111111111';
		$billing_info->credit_card->year = intval(date('Y')) + 1;
		$billing_info->credit_card->month = date('n');
		$billing_info->credit_card->verification_value = '123';
		
		$updated_billing = $billing_info->update();
		
		$this->assertIsA($updated_billing, 'RecurlyBillingInfo');
	}
}