<?php

extract( shortcode_atts( array (
    'roomID' => '001'
), $atts ) );
/*** These parameters can be gathered from the Modify Organization Settings section of the admin page.
Contact us if you have any questions regarding these credentials ***/
$companyID = 433;
$companyPass = 'secrets';
$roomID = 001;

// *** Construct Company Username

/*** The companyUsername is a field that allows omNovia to differentiate users you pass to
its servers. It MUST be a unique id (text or number) for each individual user. 
Leaving companyUsername empty or null results in a security risk and may produce login 
inconsistencies. 
For SSO to work with the mobile app, this variable must not be null.
The companyUserName can be whatever you feel is appropriate. You would usually use the email
address you already have in your database or another unique identifier you have for the user
in your own database. If using the email address, the '@' must be replaced by '_'. It must 
not contain any spaces or special characters. You must do one of the following and comment 
the other one! 
 ***/

// Example 1 : with Email address
$companyUsername = str_replace("@", "_", "joe@smith.com");

// Example 2: with username
$companyUsername = "joemartin";

// *** Other User Information
// This info usually comes from your database
$firstName = 'joe';
$lastName = 'martin';

// email is optional
$email = 'joe@martin.com';

// phone number is optional
$phone = '252-252-2522';

// companyCustomData is optional -- The custom data is available in the CSV version of 
// room reports for a particular meeting
$companyCustomData = 'some custom data about user';


// 0 for attendees, 2 for presenter, 3 for moderator -- cannot be 1
$role = 0;

/*** 0 for attendees.  1 for presenter or moderator
// $isPresenter = 0;   // DEPRECATED -- DO NOT USE

For Attendees: Set it to 0 
For moderators/presenters: 
- Set it to 0 if the person does not have an account at omNovia
- Set it to the userNo in the omNovia system if they have one. 
Ask omNovia Representative for more info in this case. ***/
$userNo = 0;


/*** Construct Time Stamp in GMT with n min Expiration
duration in number of minutes between the time you generate the link and the time they can actually access
the room. This is a very important parameter! If it is too large, they may bookmark the login page and 
access the room without going through your site. If it is too little, they might not have enough time to 
access the room. We suggest you set it between 5 and 30 minutes Please also note that your server time 
(even in GMT) might differ from the omNovia time by a few minutes. ***/

$link_duration = 10;

/*** You do  not need to change any value below. However if your server time is NOT in GMT, you must
convert the time into GMT ***/
$timestamp = time() + (60 * $link_duration);


/*** Direct or Indirect Room Access
You may send the user to the omNovia login page, in which case he/she will not be asked for any passwords
but would still need to click a button to actually access the room. You may also send them directly into 
the room. We recommend you send to the login page for the following reasons:
- The omNovia login page performs some tests to evaluate if they have the prerequisite conditions such as 
the right Flash Player
- The omNovia login page allows them to open the room in 2 window mode / choose language ... 
Note you may be able to offer these
options in your own site as well (See below).
Set this to 1 for direct access or to 0 for access to the omNovia login page ***/
$directAccess = 1;

/***
The language field ONLY matters if you have $directAccess set to 1. Otherwise the user may choose it in 
the omNovia login page.
Set it to nothing ('') if you do not wish to set to any lanagues. The default is english
Options are EN, ES, FR, DE, RU, HE ... ***/
$language = 'EN';

/*** The $openInSeparateWindow ONLY matters if you set $directAccess to 1;
You may want to open the room in a separate browser window when they click the link or button to join
the room. The advantage of opening the room in a separate window is the room will have a more space since 
the top toolbars and other options in the browser window are removed. ***/
$openInSeparateWindow = 1;


// ----------------------------------------------------------------------------------------------
// YOU DO NOT NEED TO MAKE ANY CHANGES IN THIS BLOCK
// ----------------------------------------------------------------------------------------------

$version = '1.3';
$base_omnovia_link = "https://www.omnovia.com/pages/sc2/room_login.php?";

$inquiry = '';
if ($directAccess == 1)
{
    $inquiry = '&inquiry=login';
    if ($language != '')
        $inquiry .= "&language=$language";
}


$extra = "";
$extra .= ($phone ? "&phone=$phone" : "");
$extra .= ($companyCustomData ? "&companyCustomData=$companyCustomData" : "");

//*** Create a md5 hash - IT MUST BE IN THIS ORDER
//*** Construct the link
if ($userNo == 0)
{
    $extra .= ($email ? "&email=$email" : "");
    $md5 = md5($companyID.$companyPass.$roomID.$firstName.$lastName.$role.$version.$companyUsername.$timestamp);
    $room_link = "companyID=$companyID&role=$role&loginType=2&roomID=$roomID$inquiry"
        . "&firstName=" . rawurlencode($firstName) . "&lastName=" . rawurlencode($lastName)
        . "&companyUsername=$companyUsername&_ts=$timestamp&_t=$md5&_v=$version$extra";
}
else
{
    $md5 = md5($companyID.$companyPass.$roomID.$userNo.$role.$version.$companyUsername.$timestamp);
    $room_link = "companyID=$companyID&role=$role&loginType=2&roomID=$roomID$inquiry"
        . "&userNo=$userNo&companyUsername=$companyUsername&_ts=$timestamp&_t=$md5&_v=$version$extra";
}


/*** Construct the full link ***/
$room_link = $base_omnovia_link . $room_link;


/*** Display Link
You can now use $room_link anywhere in your PHP script / HTML as the URL
to one of your rooms. If you have multiple rooms, you will need to
loop through the $room_link, $enc_room_link generation code for each $roomID you have ***/

if ($openInSeparateWindow == 1)
{
    echo "<script>var isIE  = (navigator.appVersion.indexOf('MSIE') != -1) ? true : false;
 var scrollbars = (isIE ? 'no' : 'yes');";
    echo "function openRoom(){ window.open('".$room_link."', 'w" . $roomID . "', 'height=700, width=850,
  toolbar=no, menubar=no, scrollbars=' + scrollbars + ', location=no, directories=no, status=yes, 
 resizable=1');}</script>";
}

// ... you can add your own code here and then add a link or button:

if ($openInSeparateWindow == 1)
    echo "<a href='javascript:void(0)' onClick='openRoom()'>Access the room Here</a>";
else
    echo "<a href=\"$room_link\"> Access the room Here</a>";
