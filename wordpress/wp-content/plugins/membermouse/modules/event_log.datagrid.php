<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_EventLogView();

if(!empty($_REQUEST["sortby"]))
{
	$dataGrid = new MM_DataGrid($_REQUEST, $_REQUEST["sortby"], "desc", 20);
}
else
{
	$dataGrid = new MM_DataGrid($_REQUEST, "date_added", "desc", 20);
}
$data = $view->getViewData($_REQUEST, $dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "event";

$rows = array();

$headers = array
(	    
   	'event_type'	=> array('content' => '<a onclick="mmjs.sort(\'event_type\');" href="#">Type</a>'),
   	'user_id'		=> array('content' => '<a onclick="mmjs.sort(\'user_id\');" href="#">Member</a>'),
   	'url'	        => array('content' => '<a onclick="mmjs.sort(\'url\');" href="#">Page</a>'),
   	'ip'			=> array('content' => '<a onclick="mmjs.sort(\'ip\');" href="#">IP Address</a>'),
   	'date_added'	=> array('content' => '<a onclick="mmjs.sort(\'date_added\');" href="#">Date Added</a>')
);

foreach($data as $key=>$item)
{	
	// member link
	$user = new MM_User($item->user_id);
	
	$memberLink = MM_NO_DATA;
	
	if($user->isValid())
	{
		$memberLink = $user->getUsername();
		$memberLink = "<a href='?page=".MM_MODULE_MANAGE_MEMBERS."&module=details_general&user_id=".$item->user_id."'>".$user->getUsername()."</a>";
	}
	
	$url = MM_NO_DATA;
	$eventType = MM_NO_DATA;
	if(!empty($item->event_type))
	{
		if($item->event_type == MM_EventLog::$EVENT_TYPE_PAGE_ACCESS)
		{
			$eventType = "<img src='".MM_Utils::getImageUrl('page_green')."' style='vertical-align:middle;' title='Page access' />";
			$urlParts = explode("?", $item->url);
			$urlParams = "";
			if(count($urlParts) > 1)
			{
				$urlParams = $urlParts[1];
			}
			
			if(!empty($item->additional_params))
			{
				$params = unserialize($item->additional_params);
				
				if(isset($params[MM_EventLog::$PARAM_PAGE_ID]))
				{
					$pageInfo = get_page($params[MM_EventLog::$PARAM_PAGE_ID]);
					
					if(isset($pageInfo->ID))
					{
						$permalink = get_permalink($pageInfo->ID);
						$url = "<a href=\"{$permalink}\" target=\"_blank\">{$pageInfo->post_title}</a>";
					}
				}
			}
			else
			{
				$url = $urlParts[0];
			}
			
			if(!empty($urlParams))
			{
				$paramString = "Parameters:\n";
				$pairs = explode("&", $urlParams);
				
				if(!empty($pairs) && count($pairs) > 0)
				{
					foreach($pairs as $pair)
					{
						$paramString .= urldecode(str_replace("=", ": ", $pair))."\n";
					}
					
					$url .= " <img src='".MM_Utils::getImageUrl('link')."' style='vertical-align:middle;' title='{$paramString}' />";
				}
			}
		}
		else if($item->event_type == MM_EventLog::$EVENT_TYPE_LOGIN)
		{
			$eventType = "<img src='".MM_Utils::getImageUrl('key')."' style='vertical-align:middle;' title='Login' />";
		}
	}	
	
	// IP Address
	$ipAddress = MM_NO_DATA;
	
	if(!empty($item->ip))
	{
		$ipAddress = "<a href='http://www.infobyip.com/ip-".$item->ip.".html' target='_blank'>".$item->ip."</a>";
	}
	
    $rows[] = array
    (
    	array('content' => $eventType),
    	array('content' => $memberLink),
    	array('content' => $url),
    	array('content' => $ipAddress),
    	array('content' => MM_utils::dateToLocal($item->date_added))
    );
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") 
{
	$dgHtml = "<p><i>No events found.</i></p>";
}

echo $dgHtml;
?>