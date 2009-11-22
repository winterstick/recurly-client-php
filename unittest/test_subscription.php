<?php
require_once('../library/recurly.php');


class SubscriptionTestCase extends UnitTestCase {
    
    function setUp() {
    }
    
    function tearDown() {
    }

	function testCreateSubscriptionNewAccount() {
		$new_acct = new RecurlyAccount(strval(time()) . '-create-sub-new', null, 'test@test.com', 'Create New', 'Subscription'. 'Test');
		$subscription = $this->buildSubscription($new_acct);
		$sub_response = $subscription->create();
		
		$this->assertIsA($sub_response, 'RecurlySubscription');
	}

	function testGetSubscriptionNewAccount() {
		$new_acct = new RecurlyAccount(strval(time()) . '-create-sub-new', null, 'test@test.com', 'Create New', 'Subscription'. 'Test');
		$subscription = $this->buildSubscription($new_acct);
		$sub_response = $subscription->create();
		
		$get_subscription = RecurlySubscription::getSubscription($new_acct->account_code);
		$this->assertIsA($get_subscription, 'RecurlySubscription');
	}

	function testCreateSubscriptionExistingAccount() {
		$acct = new RecurlyAccount(strval(time()) . '-create-sub-existing', null, 'test@test.com', 'Create Existing', 'Subscription', 'Test');
		$acct = $acct->create();
		
		$subscription = $this->buildSubscription($acct);
		$sub_response = $subscription->create();
		
		$this->assertIsA($sub_response, 'RecurlySubscription');
	}
	
	function testUpdateSubscription() {
		$acct = new RecurlyAccount(strval(time()) . '-update-sub', null, 'test@test.com', 'Update', 'Subscription', 'Test');
		$acct = $acct->create();
		
		$subscription = $this->buildSubscription($acct);
		$sub_response = $subscription->create();
		
		$this->assertIsA($sub_response, 'RecurlySubscription');
	}
    	
	function testCancelSubscription() {
		$acct = new RecurlyAccount(strval(time()) . '-cancel-sub', null, 'test@test.com', 'Cancel', 'Subscription', 'Test');
		$acct = $acct->create();
		
		$subscription = $this->buildSubscription($acct);
		$sub_response = $subscription->create();
		$this->assertIsA($sub_response, 'RecurlySubscription');
		
		$response = RecurlySubscription::cancelSubscription($acct->account_code);
		$this->assertTrue($response);
	}
	
	function testRefundSubscription() {
		$acct = new RecurlyAccount(strval(time()) . '-refund-sub', null, 'test@test.com', 'Refund', 'Subscription', 'Test');
		$acct = $acct->create();
		
		$subscription = $this->buildSubscription($acct);
		$sub_response = $subscription->create();
		$this->assertIsA($sub_response, 'RecurlySubscription');
		
		$response = RecurlySubscription::refundSubscription($acct->account_code, false);
		$this->assertTrue($response);
	}

	function testUpgradeSubscription() {
		$acct = new RecurlyAccount(strval(time()) . '-upgrade-sub', null, 'test@test.com', 'Upgrade', 'Subscription', 'Test');
		$acct = $acct->create();

		$subscription = $this->buildSubscription($acct);
		$sub_response = $subscription->create();
		$this->assertIsA($sub_response, 'RecurlySubscription');

		$response = RecurlySubscription::changeSubscription($acct->account_code, 'now', null, 2); // Change quantity to two
		$this->assertTrue($response);
	}
	
	function testDowngradeSubscription() {
		$acct = new RecurlyAccount(strval(time()) . '-downgrade-sub', null, 'test@test.com', 'Downgrade', 'Subscription', 'Test');
		$acct = $acct->create();

		$subscription = $this->buildSubscription($acct);
		$sub_response = $subscription->create();
		$this->assertIsA($sub_response, 'RecurlySubscription');

		$response = RecurlySubscription::changeSubscription($acct->account_code, 'renewal', null, null, 5.25);
		$this->assertTrue($response);
	}
	
	/* Build a subscription object for the subscription tests */
	function buildSubscription($acct) {
		$subscription = new RecurlySubscription();
		$subscription->plan_code = RECURLY_SUBSCRIPTION_PLAN_CODE;
		$subscription->account = $acct;
		$subscription->billing_info = new RecurlyBillingInfo($subscription->account->account_code);
		$billing_info = $subscription->billing_info;
		$billing_info->first_name = $subscription->account->first_name;
		$billing_info->last_name = $subscription->account->last_name;
		$billing_info->address1 = '123 Test St';
		$billing_info->city = 'San Francisco';
		$billing_info->state = 'CA';
		$billing_info->country = 'US';
		$billing_info->zip = '94105';
		$billing_info->credit_card->number = '1';
		$billing_info->credit_card->year = intval(date('Y')) + 1;
		$billing_info->credit_card->month = date('n');
		$billing_info->credit_card->verification_value = '123';
		
		return $subscription;
	}
}