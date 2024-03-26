<?php

namespace HSPDev\HuaweiApi;

use SimpleXMLElement;

/*
 * Depends on:
 * HSPDev\HuaweiApi\CustomHttpClient
 */

/**
 * This class handles login, sessions and such.
 * and provides relevant methods for getting at the details.
 * This has probably become quite a god object, but it's nice to use.
 */
class Router
{
    /**
     * @link https://forum.mikrotik.com/viewtopic.php?t=166859#p1018120
     * Error codes.
     */
    public static $errors = [
        '100001' => 'ERROR_UNKNOWN',
        '100002' => 'ERROR_NOT_SUPPORT',
        '100003' => 'ERROR_NO_RIGHT',
        '100004' => 'ERROR_BUSY',
        '100005' => 'ERROR_FORMAT_ERROR',
        '100006' => 'ERROR_PARAMETER_ERROR',
        '100007' => 'ERROR_SAVE_CONFIG_FILE_ERROR',
        '100008' => 'ERROR_GET_CONFIG_FILE_ERROR',
        '101001' => 'ERROR_NO_SIM_CARD_OR_INVALID_SIM_CARD',
        '101002' => 'ERROR_CHECK_SIM_CARD_PIN_LOCK',
        '101003' => 'ERROR_CHECK_SIM_CARD_PUN_LOCK',
        '101004' => 'ERROR_CHECK_SIM_CARD_CAN_UNUSEABLE',
        '101005' => 'ERROR_ENABLE_PIN_FAILED',
        '101006' => 'ERROR_DISABLE_PIN_FAILED',
        '101007' => 'ERROR_UNLOCK_PIN_FAILED',
        '101008' => 'ERROR_DISABLE_AUTO_PIN_FAILED',
        '101009' => 'ERROR_ENABLE_AUTO_PIN_FAILED',
        '102001' => 'ERROR_GET_NET_TYPE_FAILED',
        '102002' => 'ERROR_GET_SERVICE_STATUS_FAILED',
        '102003' => 'ERROR_GET_ROAM_STATUS_FAILED',
        '102004' => 'ERROR_GET_CONNECT_STATUS_FAILED',
        '103001' => 'ERROR_DEVICE_AT_EXECUTE_FAILED',
        '103002' => 'ERROR_DEVICE_PIN_VALIDATE_FAILED',
        '103003' => 'ERROR_DEVICE_PIN_MODIFFY_FAILED',
        '103004' => 'ERROR_DEVICE_PUK_MODIFFY_FAILED',
        '103005' => 'ERROR_DEVICE_GET_AUTORUN_VERSION_FAILED',
        '103006' => 'ERROR_DEVICE_GET_API_VERSION_FAILED',
        '103007' => 'ERROR_DEVICE_GET_PRODUCT_INFORMATON_FAILED',
        '103008' => 'ERROR_DEVICE_SIM_CARD_BUSY',
        '103009' => 'ERROR_DEVICE_SIM_LOCK_INPUT_ERROR',
        '103010' => 'ERROR_DEVICE_NOT_SUPPORT_REMOTE_OPERATE',
        '103011' => 'ERROR_DEVICE_PUK_DEAD_LOCK',
        '103012' => 'ERROR_DEVICE_GET_PC_AISSST_INFORMATION_FAILED',
        '103013' => 'ERROR_DEVICE_SET_LOG_INFORMATON_LEVEL_FAILED',
        '103014' => 'ERROR_DEVICE_GET_LOG_INFORMATON_LEVEL_FAILED',
        '103015' => 'ERROR_DEVICE_COMPRESS_LOG_FILE_FAILED',
        '103016' => 'ERROR_DEVICE_RESTORE_FILE_DECRYPT_FAILED',
        '103017' => 'ERROR_DEVICE_RESTORE_FILE_VERSION_MATCH_FAILED',
        '103018' => 'ERROR_DEVICE_RESTORE_FILE_FAILED',
        '103101' => 'ERROR_DEVICE_SET_TIME_FAILED',
        '103102' => 'ERROR_COMPRESS_LOG_FILE_FAILED',
        '104001' => 'ERROR_DHCP_ERROR',
        '106001' => 'ERROR_SAFE_ERROR',
        '107720' => 'ERROR_DIALUP_GET_CONNECT_FILE_ERROR',
        '107721' => 'ERROR_DIALUP_SET_CONNECT_FILE_ERROR',
        '107722' => 'ERROR_DIALUP_DIALUP_MANAGMENT_PARSE_ERROR',
        '107724' => 'ERROR_DIALUP_ADD_PRORILE_ERROR',
        '107725' => 'ERROR_DIALUP_MODIFY_PRORILE_ERROR',
        '107726' => 'ERROR_DIALUP_SET_DEFAULT_PRORILE_ERROR',
        '107727' => 'ERROR_DIALUP_GET_PRORILE_LIST_ERROR',
        '107728' => 'ERROR_DIALUP_GET_AUTO_APN_MATCH_ERROR',
        '107729' => 'ERROR_DIALUP_SET_AUTO_APN_MATCH_ERROR',
        '108001' => 'ERROR_LOGIN_NO_EXIST_USER',
        '108002' => 'ERROR_LOGIN_PASSWORD_ERROR',
        '108003' => 'ERROR_LOGIN_ALREADY_LOGINED',
        '108004' => 'ERROR_LOGIN_MODIFY_PASSWORD_FAILED',
        '108005' => 'ERROR_LOGIN_TOO_MANY_USERS_LOGINED',
        '108006' => 'ERROR_LOGIN_USERNAME_OR_PASSWORD_ERROR',
        '108007' => 'ERROR_LOGIN_TOO_MANY_TIMES',
        '109001' => 'ERROR_LANGUAGE_GET_FAILED',
        '109002' => 'ERROR_LANGUAGE_SET_FAILED',
        '110001' => 'ERROR_ONLINE_UPDATE_SERVER_NOT_ACCESSED',
        '110002' => 'ERROR_ONLINE_UPDATE_ALREADY_BOOTED',
        '110003' => 'ERROR_ONLINE_UPDATE_GET_DEVICE_INFORMATION_FAILED',
        '110004' => 'ERROR_ONLINE_UPDATE_GET_LOCAL_GROUP_COMMPONENT_INFORMATION_FAILED',
        '110005' => 'ERROR_ONLINE_UPDATE_NOT_FIND_FILE_ON_SERVER',
        '110006' => 'ERROR_ONLINE_UPDATE_NEED_RECONNECT_SERVER',
        '110007' => 'ERROR_ONLINE_UPDATE_CANCEL_DOWNLODING',
        '110008' => 'ERROR_ONLINE_UPDATE_SAME_FILE_LIST',
        '110009' => 'ERROR_ONLINE_UPDATE_CONNECT_ERROR',
        '110021' => 'ERROR_ONLINE_UPDATE_INVALID_URL_LIST',
        '110022' => 'ERROR_ONLINE_UPDATE_NOT_SUPPORT_URL_LIST',
        '110023' => 'ERROR_ONLINE_UPDATE_NOT_BOOT',
        '110024' => 'ERROR_ONLINE_UPDATE_LOW_BATTERY',
        '111001' => 'ERROR_USSD_ERROR',
        '111012' => 'ERROR_USSD_FUCNTION_RETURN_ERROR',
        '111013' => 'ERROR_USSD_IN_USSD_SESSION',
        '111014' => 'ERROR_USSD_TOO_LONG_CONTENT',
        '111016' => 'ERROR_USSD_EMPTY_COMMAND',
        '111017' => 'ERROR_USSD_CODING_ERROR',
        '111018' => 'ERROR_USSD_AT_SEND_FAILED',
        '111019' => 'ERROR_USSD_NET_NO_RETURN',
        '111020' => 'ERROR_USSD_NET_OVERTIME',
        '111021' => 'ERROR_USSD_XML_SPECIAL_CHARACTER_TRANSFER_FAILED',
        '111022' => 'ERROR_USSD_NET_NOT_SUPPORT_USSD',
        '112001' => 'ERROR_SET_NET_MODE_AND_BAND_WHEN_DAILUP_FAILED',
        '112002' => 'ERROR_SET_NET_SEARCH_MODE_WHEN_DAILUP_FAILED',
        '112003' => 'ERROR_SET_NET_MODE_AND_BAND_FAILED',
        '112004' => 'ERROR_SET_NET_SEARCH_MODE_FAILED',
        '112005' => 'ERROR_NET_REGISTER_NET_FAILED',
        '112006' => 'ERROR_NET_NET_CONNECTED_ORDER_NOT_MATCH',
        '112007' => 'ERROR_NET_CURRENT_NET_MODE_NOT_SUPPORT',
        '112008' => 'ERROR_NET_SIM_CARD_NOT_READY_STATUS',
        '112009' => 'ERROR_NET_MEMORY_ALLOC_FAILED',
        '113017' => 'ERROR_SMS_NULL_ARGUMENT_OR_ILLEGAL_ARGUMENT',
        '113018' => 'ERROR_SMS_OVERTIME',
        '113020' => 'ERROR_SMS_QUERY_SMS_INDEX_LIST_ERROR',
        '113031' => 'ERROR_SMS_SET_SMS_CENTER_NUMBER_FAILED',
        '113036' => 'ERROR_SMS_DELETE_SMS_FAILED',
        '113047' => 'ERROR_SMS_SAVE_CONFIG_FILE_FAILED',
        '113053' => 'ERROR_SMS_LOCAL_SPACE_NOT_ENOUGH',
        '113054' => 'ERROR_SMS_TELEPHONE_NUMBER_TOO_LONG',
        '114001' => 'ERROR_SD_FILE_EXIST',
        '114002' => 'ERROR_SD_DIRECTORY_EXIST',
        '114004' => 'ERROR_SD_FILE_OR_DIRECTORY_NOT_EXIST',
        '114005' => 'ERROR_SD_FILE_NAME_TOO_LONG',
        '114006' => 'ERROR_SD_NO_RIGHT',
        '114007' => 'ERROR_SD_FILE_IS_UPLOADING',
        '115001' => 'ERROR_PB_NULL_ARGUMENT_OR_ILLEGAL_ARGUMENT',
        '115002' => 'ERROR_PB_OVERTIME',
        '115003' => 'ERROR_PB_CALL_SYSTEM_FUCNTION_ERROR',
        '115004' => 'ERROR_PB_WRITE_FILE_ERROR',
        '115005' => 'ERROR_PB_READ_FILE_ERROR',
        '115199' => 'ERROR_PB_LOCAL_TELEPHONE_FULL_ERROR',
        '116001' => 'ERROR_STK_NULL_ARGUMENT_OR_ILLEGAL_ARGUMENT',
        '116002' => 'ERROR_STK_OVERTIME',
        '116003' => 'ERROR_STK_CALL_SYSTEM_FUCNTION_ERROR',
        '116004' => 'ERROR_STK_WRITE_FILE_ERROR',
        '116005' => 'ERROR_STK_READ_FILE_ERROR',
        '117001' => 'ERROR_WIFI_STATION_CONNECT_AP_PASSWORD_ERROR',
        '117002' => 'ERROR_WIFI_WEB_PASSWORD_OR_DHCP_OVERTIME_ERROR',
        '117003' => 'ERROR_WIFI_PBC_CONNECT_FAILED',
        '117004' => 'ERROR_WIFI_STATION_CONNECT_AP_WISPR_PASSWORD_ERROR',
        '118001' => 'ERROR_CRADLE_GET_CRURRENT_CONNECTED_USER_IP_FAILED',
        '118002' => 'ERROR_CRADLE_GET_CRURRENT_CONNECTED_USER_MAC_FAILED',
        '118003' => 'ERROR_CRADLE_SET_MAC_FAILED',
        '118004' => 'ERROR_CRADLE_GET_WAN_INFORMATION_FAILED',
        '118005' => 'ERROR_CRADLE_CODING_FAILED',
        '118006' => 'ERROR_CRADLE_UPDATE_PROFILE_FAILED',
    ];
    protected $http = null; //Our custom HTTP provider.
    public $routerAddress = 'http://192.168.8.1'; //This is the one for the router I got.
    //These two we need to acquire through an API call.
    protected $sessionInfo = '';
    protected $tokenInfo = '';

    public function __construct()
    {
        $this->http = new CustomHttpClient();
    }

    /**
     * Sets the router address.
     */
    public function setAddress($address)
    {
        //Remove trailing slash if any.
        $address = rtrim($address, '/');

        //If not it starts with http, we assume HTTP and add it.
        if (strpos($address, 'http') !== 0) {
            $address = 'http://' . $address;
        }

        $this->routerAddress = $address;
    }

    /**
     * Most API responses are just simple XML, so to avoid repetition
     * this function will GET the route and return the object.
     *
     * @return SimpleXMLElement
     */
    public function generalizedGet($route)
    {
        //Makes sure we are ready for the next request.
        $this->prepare();

        $xml = $this->http->get($this->getUrl($route));
        $obj = new \SimpleXMLElement($xml);

        //Check for error message
        if (property_exists($obj, 'code')) {
            throw new \UnexpectedValueException('The API returned error code: ' . $obj->code);
        }

        return $obj;
    }

    /**
     * Gets the current router status.
     *
     * @return SimpleXMLElement
     */
    public function getStatus()
    {
        return $this->generalizedGet('api/monitoring/status');
    }

    /**
     * Gets traffic statistics (numbers are in bytes).
     *
     * @return SimpleXMLElement
     */
    public function getTrafficStats()
    {
        return $this->generalizedGet('api/monitoring/traffic-statistics');
    }

    /**
     * Gets monthly statistics (numbers are in bytes)
     * This probably only works if you have setup a limit.
     *
     * @return SimpleXMLElement
     */
    public function getMonthStats()
    {
        return $this->generalizedGet('api/monitoring/month_statistics');
    }

    /**
     * Info about the current mobile network. (PLMN info).
     *
     * @return SimpleXMLElement
     */
    public function getNetwork()
    {
        return $this->generalizedGet('api/net/current-plmn');
    }

    /**
     * Gets the current craddle status.
     *
     * @return SimpleXMLElement
     */
    public function getCraddleStatus()
    {
        return $this->generalizedGet('api/cradle/status-info');
    }

    /**
     * Get current SMS count.
     *
     * @return SimpleXMLElement
     */
    public function getSmsCount()
    {
        return $this->generalizedGet('api/sms/sms-count');
    }

    /**
     * Get current WLAN Clients.
     *
     * @return SimpleXMLElement
     */
    public function getWlanClients()
    {
        return $this->generalizedGet('api/wlan/host-list');
    }

    /**
     * Get notifications on router.
     *
     * @return SimpleXMLElement
     */
    public function getNotifications()
    {
        return $this->generalizedGet('api/monitoring/check-notifications');
    }

    /**
     * Gets the SMS inbox.
     * Page parameter is NOT null indexed and starts at 1.
     * I don't know if there is an upper limit on $count. Your milage may vary.
     * unreadPrefered should give you unread messages first.
     *
     * @return bool
     */
    public function setLedOn($on = false)
    {
        //Makes sure we are ready for the next request.
        $this->prepare();

        $ledXml = '<?xml version:"1.0" encoding="UTF-8"?><request><ledSwitch>' . ($on ? '1' : '0') . '</ledSwitch></request>';
        $xml = $this->http->postXml($this->getUrl('api/led/circle-switch'), $ledXml);
        $obj = new \SimpleXMLElement($xml);
        //Simple check if login is OK.
        return (string) $obj == 'OK';
    }

    /**
     * Checks whatever we are logged in.
     *
     * @return bool
     */
    public function getLedStatus()
    {
        $obj = $this->generalizedGet('api/led/circle-switch');
        if (property_exists($obj, 'ledSwitch')) {
            if ($obj->ledSwitch == '1') {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whatever we are logged in.
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        $obj = $this->generalizedGet('api/user/state-login');
        if (property_exists($obj, 'State')) {
            /*
             * Logged out seems to be -1
             * Logged in seems to be 0.
             * What the hell?
             */
            if ($obj->State == '0') {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the SMS inbox.
     * Page parameter is NOT null indexed and starts at 1.
     * I don't know if there is an upper limit on $count. Your milage may vary.
     * unreadPrefered should give you unread messages first.
     *
     * @return SimpleXMLElement
     */
    public function getInbox($page = 1, $count = 20, $unreadPreferred = false)
    {
        //Makes sure we are ready for the next request.
        $this->prepare();

        $inboxXml = '<?xml version="1.0" encoding="UTF-8"?><request>
			<PageIndex>' . $page . '</PageIndex>
			<ReadCount>' . $count . '</ReadCount>
			<BoxType>1</BoxType>
			<SortType>0</SortType>
			<Ascending>0</Ascending>
			<UnreadPreferred>' . ($unreadPreferred ? '1' : '0') . '</UnreadPreferred>
			</request>
		';
        $xml = $this->http->postXml($this->getUrl('api/sms/sms-list'), $inboxXml);
        $obj = new \SimpleXMLElement($xml);

        return $obj;
    }

    /**
     * Mark an SMS read by ID
     *
     * @param int $index ID of SMS to mark as Read
     *
     * @return boolean
     */
    public function markSmsAsRead($index)
    {
        $this->prepare();
        $readXml = '<?xml version:"1.0" encoding="UTF-8"?><request><Index>' . $index . '</Index></request>';
        $xml = $this->http->postXml($this->getUrl('api/sms/set-read'), $readXml);
        $obj = new \SimpleXMLElement($xml);
        return ((string) $obj == 'OK');
    }

    /**
     * Deletes an SMS by ID, also called "Index".
     * The index on the Message object you get from getInbox
     * will contain an "Index" property with a value like "40000" and up.
     * Note: Will return true if the Index DOES NOT exist already.
     *
     * @return bool
     */
    public function deleteSms($index)
    {
        //Makes sure we are ready for the next request.
        $this->prepare();

        $deleteXml = '<?xml version="1.0" encoding="UTF-8"?><request>
			<Index>' . $index . '</Index>
			</request>
		';
        $xml = $this->http->postXml($this->getUrl('api/sms/delete-sms'), $deleteXml);
        $obj = new \SimpleXMLElement($xml);
        //Simple check if login is OK.
        return (string) $obj == 'OK';
    }

    /**
     * Sends SMS to specified receiver. I don't know if it works for foreign numbers,
     * but for local numbers you can just specifiy the number like you would normally
     * call it and it should work, here in Denmark "42952777" etc (mine).
     * Message parameter got the normal SMS restrictions you know and love.
     *
     * @return bool
     */
    public function sendSms($receiver, $message)
    {
        //Makes sure we are ready for the next request.
        $this->prepare();

        /*
         * Note how it wants the length of the content also.
         * It ALSO wants the current date/time wtf? Oh well..
         */
        $sendSmsXml = '<?xml version="1.0" encoding="UTF-8"?><request>
			<Index>-1</Index>
			<Phones>
				<Phone>' . $receiver . '</Phone>
			</Phones>
			<Sca/>
			<Content>' . $message . '</Content>
			<Length>' . strlen($message) . '</Length>
			<Reserved>1</Reserved>
			<Date>' . date('Y-m-d H:i:s') . '</Date>
			<SendType>0</SendType>
			</request>
		';
        $xml = $this->http->postXml($this->getUrl('api/sms/send-sms'), $sendSmsXml);
        $obj = new \SimpleXMLElement($xml);
        //Simple check if login is OK.
        return (string) $obj == 'OK';
    }

    /**
     * Gets the SMS status
     *
     * @param int  $page            page number during pagination
     * @param int  $pageSize        maximum number of items per page
     * @param int  $boxType         1 = inbox, 2 = outbox
     * @param bool $unreadPreffered if True, unread SMS'es are listed first, otherwise
     *                              they are listed by date in descending order
     *
     * @return \SimpleXMLElement
     */
    public function getSms($page = 1, $pageSize = 100, $boxType = 1, $unreadPreffered = true)
    {

        $smsRequestXml = sprintf('<request>
            <PageIndex>%d</PageIndex>
            <ReadCount>%d</ReadCount>
            <BoxType>%d</BoxType>
            <SortType>0</SortType>
            <Ascending>0</Ascending>
            <UnreadPreferred>%d</UnreadPreferred>
        </request>', $page, $pageSize, $boxType, $unreadPreffered ? 1 : 0);

        $xml = $this->http->postXml($this->getUrl('api/sms/sms-list'), $smsRequestXml);
        return new \SimpleXMLElement($xml);
    }

    /**
     * Not all methods may work if you don't login.
     * Please note that the router is pretty aggressive
     * at timing your session out.
     * Call something periodically or just relogin on error.
     *
     * @return bool
     */
    public function login($username, $password)
    {
        //Makes sure we are ready for the next request.
        $this->prepare();

        /*
         * Note how the router wants the password to be the following:
         * 1) Hashed by SHA256, then the raw output base64 encoded.
         * 2) The username is appended with the result of the above,
         *   AND the current token. Yes, the password changes everytime
         *   depending on what token we got. This really fucks with scrapers.
         * 3) The string from above (point 2) is then hashed by SHA256 again,
         *    and the raw output is once again base64 encoded.
         *
         * This is how the router login process works. So the password being sent
         * changes everytime depending on the current user session/token.
         * Not bad actually.
         */
        $loginXml = '<?xml version="1.0" encoding="UTF-8"?><request>
		<Username>' . $username . '</Username>
		<password_type>4</password_type>
		<Password>' . base64_encode(hash('sha256', $username . base64_encode(hash('sha256', $password, false)) . $this->http->getToken(), false)) . '</Password>
		</request>
		';
        $xml = $this->http->postXml($this->getUrl('api/user/login'), $loginXml);
        $obj = new \SimpleXMLElement($xml);
        //Simple check if login is OK.
        return (string) $obj == 'OK';
    }

    /**
     * Sets the data switch to enable or disable the mobile connection.
     *
     * @return bool
     */
    public function setDataSwitch($value)
    {
        if (is_int($value) === false) {
            throw new \Exception('Parameter can only be integer.');
        }
        if ($value !== 0 && $value !== 1) {
            throw new \Exception('Parameter can only be integer.');
        }

        //Makes sure we are ready for the next request.
        $this->prepare();

        $dataSwitchXml = '<?xml version="1.0" encoding="UTF-8"?><request><dataswitch>' . $value . '</dataswitch></request>';

        $xml = $this->http->postXml($this->getUrl('api/dialup/mobile-dataswitch'), $dataSwitchXml);
        $obj = new \SimpleXMLElement($xml);

        //Simple check if login is OK.
        return (string) $obj == 'OK';
    }

    /**
     * Internal helper that lets us build the complete URL
     * to a given route in the API.
     *
     * @return string
     */
    protected function getUrl($route)
    {
        return $this->routerAddress . '/' . $route;
    }

    /**
     * Makes sure that we are ready for API usage.
     */
    protected function prepare()
    {
        //Check to see if we have session / token.
        if (strlen($this->sessionInfo) == 0 || strlen($this->tokenInfo) == 0) {
            //We don't have any. Grab some.
            $xml = $this->http->get($this->getUrl('api/webserver/SesTokInfo'));
            $obj = new \SimpleXMLElement($xml);
            if (!property_exists($obj, 'SesInfo') || !property_exists($obj, 'TokInfo')) {
                throw new \RuntimeException('Malformed XML returned. Missing SesInfo or TokInfo nodes.');
            }
            //Set it for future use.
            $this->http->setSecurity($obj->SesInfo, $obj->TokInfo);
        }
    }
}
