<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
class MM_Status
{
	public static $ACTIVE = 1;
	public static $CANCELED = 2;
	public static $LOCKED = 3;
	public static $PAUSED = 4;
	public static $OVERDUE = 5;
	public static $PENDING = 6;
	public static $ERROR = 7;
	public static $EXPIRED = 8;
	
	public static function getName($statusId, $doLowercase=false)
	{
		$statusName = "";
		
		switch($statusId) 
		{
			case self::$ACTIVE:
				$statusName =  "Active";
				break;
			
			case self::$CANCELED:
				$statusName =  "Canceled";
				break;
				
			case self::$LOCKED:
				$statusName =  "Locked";
				break;
				
			case self::$PAUSED:
				$statusName =  "Paused";
				break;
				
			case self::$OVERDUE:
				$statusName =  "Overdue";
				break;
				
			case self::$PENDING:
				$statusName =  "Pending";
				break;
				
			case self::$ERROR:
				$statusName =  "Error";
				break;
				
			case self::$EXPIRED:
				$statusName = "Expired";
				break;
		}
		
		return ($doLowercase) ? strtolower($statusName) : $statusName;
	}
	
	public static function isValidStatus($statusId)
	{
		switch($statusId)
		{
			case self::$ACTIVE:
			case self::$CANCELED:
			case self::$LOCKED:
			case self::$PAUSED:
			case self::$OVERDUE:
			case self::$PENDING:
			case self::$ERROR:
			case self::$EXPIRED:
				return true;
				break;
		}
	
		return false;
	}
	
	public static function getImage($statusId)
	{
		switch($statusId) 
		{
			case self::$ACTIVE:
				return '<img src="'.MM_Utils::getImageUrl("accept").'" style="vertical-align:middle" title="Active" />';
			
			case self::$CANCELED:
				return '<img src="'.MM_Utils::getImageUrl("stop").'" style="vertical-align:middle" title="Canceled" />';
				
			case self::$LOCKED:
				return '<img src="'.MM_Utils::getImageUrl("lock").'" style="vertical-align:middle" title="Locked" />';
				
			case self::$PAUSED:
				return '<img src="'.MM_Utils::getImageUrl("pause").'" style="vertical-align:middle" title="Paused" />';
				
			case self::$OVERDUE:
				return '<img src="'.MM_Utils::getImageUrl("overdue").'" style="vertical-align:middle" title="Overdue" />';
				
			case self::$PENDING:
				return '<img src="'.MM_Utils::getImageUrl("clock").'" style="vertical-align:middle" title="Pending" />';
				
			case self::$ERROR:
				return '<img src="'.MM_Utils::getImageUrl("error").'" style="vertical-align:middle" title="Error" />';
				
			case self::$EXPIRED:
				return '<img src="'.MM_Utils::getImageUrl("hourglass").'" style="vertical-align:middle" title="Expired" />';
		}
		
		return "";
	}
	
	public static function getStatusTypesList()
	{
		$list = array();
		$list[MM_Status::$ACTIVE] = MM_Status::getName(MM_Status::$ACTIVE);
		$list[MM_Status::$CANCELED] = MM_Status::getName(MM_Status::$CANCELED);
		$list[MM_Status::$PAUSED] = MM_Status::getName(MM_Status::$PAUSED);
		$list[MM_Status::$OVERDUE] = MM_Status::getName(MM_Status::$OVERDUE);
		$list[MM_Status::$LOCKED] = MM_Status::getName(MM_Status::$LOCKED);
		$list[MM_Status::$PENDING] = MM_Status::getName(MM_Status::$PENDING);
		$list[MM_Status::$ERROR] = MM_Status::getName(MM_Status::$ERROR);
		$list[MM_Status::$EXPIRED] = MM_Status::getName(MM_Status::$EXPIRED);
		return $list;
	}
}
?>
