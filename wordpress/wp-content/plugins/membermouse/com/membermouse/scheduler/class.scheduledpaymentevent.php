<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 *
 * MM_ScheduledPaymentEvent represents a recurring payment that was scheduled by the MemberMouse scheduling framework. It specifically represents 
 * a payment event
 *
 */
class MM_ScheduledPaymentEvent extends MM_ScheduledEvent
{
	protected $paymentNumber    = "";
	private   $orderItemId      = "";
	private   $userId           = "";
	private   $paymentServiceId = "";
	
	private $billingStatus = "";
	
	//constants representing remote statuses
	public static $FIRST_REBILL_FAILED = 2;
	public static $SECOND_REBILL_FAILED = 3;
	public static $THIRD_REBILL_FAILED = 4;
	public static $PERMANENTLY_FAILED = 5;	
	
	public function __construct($id="", $getData=true) 
	{
		parent::__construct($id,$getData);
		$this->eventType = MM_ScheduledEvent::$PAYMENT_SERVICE_EVENT;
	}
	
	
	public function getData()
	{
		global $wpdb;
		
		parent::getData();
	
		if (intval($this->id) > 0)
		{
			$sql = "SELECT * from ".MM_TABLE_SCHEDULED_PAYMENTS." where id={$this->id}";
			$result = $wpdb->get_row($sql);
			if($result)
			{
				$this->setAdditionalData($result);
			}
			else
			{
				parent::invalidate();
			}
		}
		else
		{
			parent::invalidate();
		}
	}
	
	
	public function setAdditionalData($data)
	{
		$this->orderItemId = $data->order_item_id;
		$this->userId = $data->user_id;
		$this->paymentServiceId = $data->payment_service_id;
	}
	
	
	public function commitData($updateRemotely=true)
	{
		global $wpdb;
		
		//figure out if this is a new record or an update
		$isUpdate = (intval($this->id) >0)?true:false;
		$newRecord = !$isUpdate;
	
		//call the parent commit to generate the event
		$response = parent::commitData($updateRemotely);
		if (MM_Response::isError($response))
		{
			return $response;
		}
		
		//primary table updated, and schedule created, now create/update details table
		$scheduleDetailData = array(  "user_id"  			=> $this->userId,
									  "order_item_id"    	=> $this->orderItemId,
									  "payment_service_id"  => $this->paymentServiceId
		);
		
		if (!$isUpdate)
		{
			//this is a new record
			$scheduleDetailData = array("id" => $this->id) + $scheduleDetailData;
			$result = $wpdb->insert(MM_TABLE_SCHEDULED_PAYMENTS,$scheduleDetailData);
			if($result === false)
			{
				return new MM_Response("ERROR: unable to create scheduled event: error = ".$wpdb->last_error, MM_Response::$ERROR);
			}
		}
		else
		{
			$updateWhere = array("id"=>$this->id);
			$result = $wpdb->update(MM_TABLE_SCHEDULED_PAYMENTS,$scheduleDetailData,$updateWhere);
			if($result === false)
			{
				return new MM_Response("ERROR: unable to update scheduled event: error = ".$wpdb->last_error, MM_Response::$ERROR);
			}
		}
			
		parent::validate();
		return new MM_Response();
	}
	
	
	public function delete($deleteRemotely=true)
	{
		global $wpdb;
	
		if (intval($this->id) > 0)
		{
			$wpdb->query("DELETE FROM ".MM_TABLE_SCHEDULED_PAYMENTS." WHERE id='{$this->id}'");
			$response = parent::delete($deleteRemotely);
			if (MM_Response::isError($response))
			{
				return $response;
			}
			parent::invalidate();
			return new MM_Response();
		}
		else
		{
			return new MM_Response("Unable to delete this scheduled payment event because it is invalid",MM_Response::$ERROR);
		}
	}
	
	
	protected function prepareSettings($additionalExclusions=array())
	{
		$additionalExclusions[] = "orderItemId";
		$additionalExclusions[] = "userId";
		$additionalExclusions[] = "paymentServiceId";
		return parent::prepareSettings($additionalExclusions);
	}
	
	
	/**
	 * Returns the payment number
	 * 
	 * @return An integer representing the payment number
	 */
	public function getPaymentNumber()
	{
		return $this->paymentNumber;
	}
	
	
	/**
	 * Sets the payment number
	 * 
	 * @param int $paymentNumber
	 */
	public function setPaymentNumber($paymentNumber)
	{
		if (is_numeric($paymentNumber))
		{
			$this->paymentNumber = intval($paymentNumber);
		}
	}
	
	
	protected function setEventType($eventType)
	{
		//don't allow the event type to be changed
		$this->eventType = MM_ScheduledEvent::$PAYMENT_SERVICE_EVENT;
	}
	
	
	public function getOrderItemId()
	{
		return $this->orderItemId;
	}
	
	
	public function setOrderItemId($orderItemId)
	{
		$this->orderItemId = $orderItemId;
	}
	
	
	public function getUserId()
	{
		return $this->userId;
	}
	
	
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}
	
	
	public function getPaymentServiceId()
	{
		return $this->paymentServiceId;
	}
	
	
	public function setPaymentServiceId($paymentServiceId)
	{
		$this->paymentServiceId = $paymentServiceId;
	}
	
	
	public function getBillingStatus()
	{
		return $this->billingStatus;
	}
	
	
	public function setBillingStatus($billingStatus)
	{
		$this->billingStatus = $billingStatus;
	}
	
}