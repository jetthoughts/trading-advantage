<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 * 
 * MM_ScheduledEvent represents an event that was created by the MemberMouse scheduler. It is important to differentiate this 
 * local entity from the entity on central with the same name. Despite having identical name, and representing the same logical concept, 
 * they are very different objects because the interfaces were written from the perspective of the requestor in the former case, and the perspective 
 * of the dispatcher in the latter case.
 * 
 */
abstract class MM_ScheduledEvent extends MM_Entity
{	
	
	protected $eventType     = "";
	protected $eventData     = "";
	protected $scheduledDate = "";
	protected $processedDate = "";
	protected $status = 0;
	
	//constants representing event types
	public static $PAYMENT_SERVICE_EVENT = 1;
	
	//constants representing commands
	public static $SCHEDULER_COMMAND_UPDATE = "UPDATE";
	public static $SCHEDULER_COMMAND_DELETE = "DELETE";
	
	public static $QUEUE_COMMAND_UPDATE = "0"; //numeric constants used for queueing, because that table could potentially become pretty big
	public static $QUEUE_COMMAND_DELETE = "1";
	
	//constants representing event statuses
	public static $EVENT_PENDING = 0;
	public static $EVENT_PROCESSED = 1;
	public static $EVENT_REGISTRATION_ERROR = 6; //event was created locally but not registered by central
	
	
	/**
	 * Retrieve stored schedule information. 
	 * 
	 * @see MM_Entity::getData()
	 */
	public function getData()
	{
		global $wpdb;
		
		if (intval($this->id) > 0)
		{
			$sql = "SELECT * from ".MM_TABLE_SCHEDULED_EVENTS." where id={$this->id}";
			$result = $wpdb->get_row($sql);
			if($result)
			{
				$this->setData($result);
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
	
	/**
	 * Populate this entity based on the schedule information in object $data
	 *
	 * @param array $data An object of type stdclass attributes represent the columns of a row in the underlying database table
	 * @see MM_Entity::setData()
	 */
	public function setData($data)
	{
		try
		{
			$this->id = $data->id;
			$this->eventType = $data->event_type;
			$this->setScheduledDate($data->scheduled_date);
			$this->setProcessedDate($data->processed_date);
			$this->status = $data->status;
			
			if (!empty($data->event_data))
			{
				$unserialized_settings = unserialize($data->event_data);
				if (is_array($unserialized_settings))
				{
					foreach ($unserialized_settings as $k=>$v)
					{
						if (isset($this->$k))
						{
							$this->$k = $v; //don't create attributes, only restore existing ones
						}
					}
				}
			}
				
			parent::validate();
		}
		catch (Exception $ex)
		{
			parent::invalidate();
		}
	}
	
	
	/**
	 * Commit the contents of this entity to the database
	 * @see MM_Entity::commitData()
	 *
	 * @param $updateRemotely boolean true if the scheduling server should be updated
	 * @return MM_Response object representing success or failure
	 * 
	 */
	public function commitData($updateRemotely=true)
	{
		global $wpdb;
	
		if (empty($this->scheduledDate))
		{
			return new MM_Response("Error creating scheduled event: No date was set",MM_Response::$ERROR);
		}
		
		$isUpdate = (intval($this->id) >0)?true:false;
		$newRecord = !$isUpdate;
	
		$scheduleData = array(  "event_data"  		=> $this->prepareSettings(),
								"event_type"  		=> $this->eventType,
								"scheduled_date"    => $this->getScheduledDate(),
								"status"			=> $this->getStatus()
		);
		
		if (!empty($this->processedDate))
		{
			$scheduleData["processed_date"] = $this->getProcessedDate();
		}
	
		if (!$isUpdate)
		{
			//this is a new record
			$result = $wpdb->insert(MM_TABLE_SCHEDULED_EVENTS,$scheduleData);
			if($result === false)
			{
				return new MM_Response("ERROR: unable to create scheduled event: error = ".$wpdb->last_error, MM_Response::$ERROR);
			}
			$this->id = $wpdb->insert_id;
		}
		else
		{
			$updateWhere = array("id"=>$this->id);
			$result = $wpdb->update(MM_TABLE_SCHEDULED_EVENTS,$scheduleData,$updateWhere);
			if($result === false)
			{
				return new MM_Response("ERROR: unable to update scheduled event: error = ".$wpdb->last_error, MM_Response::$ERROR);
			}
		}		
		parent::validate();
		
		if ($updateRemotely)
		{
			$remoteResponse = $this->updateSchedulingServer();
		}
		return new MM_Response();
	}
	
	
	protected function getEventData()
	{
		return $this->eventData;
	}
	
	
	protected function setEventData($eventData)
	{
		$this->eventData = $eventData;
	}
	
	
	public function getEventType()
	{
		return $this->eventType;
	}
	
	
	protected function setEventType($eventType)
	{
		$this->eventType = $eventType;
	}
	
	
	public function getScheduledDate()
	{
		return date("Y-m-d H:i",$this->scheduledDate);
	}
	
	
	public function setScheduledDate($scheduledDate)
	{
		$this->scheduledDate = is_int($scheduledDate)?$scheduledDate:strtotime($scheduledDate);
	}
	
	public function getProcessedDate()
	{
		return date("Y-m-d H:i",$this->processedDate);
	}
	
	
	public function setProcessedDate($processedDate)
	{
		$this->processedDate = is_int($processedDate)?$processedDate:strtotime($processedDate);
	}
	
	
	public function getStatus()
	{
		return $this->status;
	}
	
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	
	public function delete($deleteRemotely=true)
	{
		global $wpdb;
		
		if (intval($this->id) > 0)
		{
			$wpdb->query("DELETE FROM ".MM_TABLE_SCHEDULED_EVENTS." WHERE id='{$this->getId()}'");
			if ($deleteRemotely)
			{
				$response = $this->updateSchedulingServer(MM_ScheduledEvent::$SCHEDULER_COMMAND_DELETE);
				if (MM_Response::isError($response))
				{
					return $response;
				}
			}
			parent::invalidate();
			return new MM_Response();
		}
		else 
		{
			return new MM_Response("Unable to delete this scheduled event because it was never committed",MM_Response::$ERROR);
		}
	}
	
	
	/**
	* Each subclass of the ScheduledEvent entity can have specific settings. These settings are stored as attributes on the object. Prior to commit,
	* prepareSettings creates an array using these attributes and then serializes that array and stores it in $this->eventData, so that the settings are persisted
	* when commitData is called. Atrributes with names that start with "transient_" are ignored
	* 
	* @param $additionalExclusions an array of additional class members to exclude from serialization
	* 
	* @return A string representing a serialized array of all the visible class members, minus the exclusions, suitable for storage in the eventData field
	*/
	protected function prepareSettings($additionalExclusions=array())
	{
		$exclusions = array('id','eventData','eventType','scheduledDate','processedDate','status','notifyServices') + $additionalExclusions;
		$settings = array();
		foreach ($this as $key=>$value)
		{
			if (!in_array($key,$exclusions) && (strpos($key,"transient_") !== 0))
			{
				$settings[$key] = $value;
			}
		}
		return serialize($settings);
	}
	
	
	/**
	 * Updates or deletes this scheduled event on MemberMouse central 
	 * 
	 * @param string $command A string set to either "UPDATE" or "DELETE"
	 * @return MM_Response indicating success or failure
	 */
	protected function updateSchedulingServer($command="UPDATE")
	{
		$command = strtoupper($command);
		if (($command != MM_ScheduledEvent::$SCHEDULER_COMMAND_UPDATE) && ($command != MM_ScheduledEvent::$SCHEDULER_COMMAND_DELETE))
		{
			return new MM_Response("Error updating the scheduling server: Invalid command: '{$command}'",MM_Response::$ERROR);
		}
		
		$license = new MM_License("",false);
		MM_MemberMouseService::getLicense($license);
		
		$messageArray = array("api_key"        => $license->getApiKey(),
							  "api_secret"     => $license->getApiSecret(),
							  "reference_id"   => $this->id,
							  "scheduled_date" => $this->getScheduledDate(),
							  "command"		   => $command
		);
		
		$message = json_encode($messageArray);
		
		$ch = curl_init(MM_SCHEDULING_SERVER_URL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,30); //timeout if the connection cannot be made within 30 secs
		
		$updateResponse = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if (($updateResponse === false) || ($httpCode == 404))
		{
			//the assumption is that false indicates a connection error, which shouldnt happen, and that any 
			//non-404 response (even an error response) is not a connection error.
			return $this->queueSchedulingServerUpdate($command);
		}
		else 
		{
			$updateResponse = json_decode($updateResponse);
			if (($updateResponse != null) && ($updateResponse->status == "ok"))
			{
				return new MM_Response();
			}
			else
			{
				$updateError = (isset($updateResponse->message))?":{$updateResponse->message}":"";
				return new MM_Response("Error communicating with scheduling server{$updateError}",MM_Response::$ERROR);
			}
		}
	}
	
	
	/**
	 * Queues updates to the scheduling server if it can't be contacted for whatever reason. This allows a site to continue processing
	 * until connection with the scheduler can be reestablished
	 * 
	 * @param String $command The original command that was being sent to the scheduling server
	 * @return MM_Response indicating if the command was successfully queued or not
	 */
	protected function queueSchedulingServerUpdate($command)
	{
		global $wpdb;
		
		//creates or replaces a queue entry for this event
		$tableData = array("event_id"    => $this->getId(), 
						   "command"     => ($command == self::$SCHEDULER_COMMAND_DELETE)?self::$QUEUE_COMMAND_DELETE:self::$QUEUE_COMMAND_UPDATE,
						   "queued_date" => MM_Utils::getCurrentTime()
		);
		$result = $wpdb->replace(MM_TABLE_QUEUED_SCHEDULED_EVENTS,$tableData);
		
		if ($result === false)
		{
			return new MM_Response("Error queueing scheduled event after server connection failed: Event ID: {$this->getId()}",MM_Response::$ERROR);
		}
		return new MM_Response();
	}
	
	
	/**
	 * Send queued schedule updates to the MemberMouse scheduling server. Successfully synchronized events are removed from the queue, 
	 * while unsuccessful events remain until the next pass 
	 */
	public static function callSynchronizationHandler()
	{
		global $wpdb;
		
		//only call the synchronization handler if its necessary
		
		$shouldSync = $wpdb->get_var("SELECT EXISTS (SELECT * FROM ".MM_TABLE_QUEUED_SCHEDULED_EVENTS.")");
		if (!is_null($shouldSync) && ($shouldSync == "1"))
		{
			$synchonizationHandlerURL = WP_PLUGIN_URL.'/'.MM_PLUGIN_NAME."/scheduler/synchronize.php";
			wp_remote_get($synchonizationHandlerURL,array('blocking'=>'false'));
		}
	}
}