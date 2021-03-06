<?php
namespace Habemus\Vacuum\Filters;

use \Countable;
use \DateTimeInterface;
use Habemus\Vacuum\File;

trait Attributes {

    /* validation methods */

	private function filter_alpha_num($value){
		if (! is_string($value) && ! is_numeric($value)) {
			return false;
		}

		return preg_match('/^[\pL\pM\pN]+$/u', $value) > 0;
	}

	private function filter_alpha($value){
		return is_string($value) && preg_match('/^[\pL\pM]+$/u', $value);
	}

	private function filter_slug($value){
		return preg_match('/^[a-z0-9\-]/',$value);
	}


	private function filter_email($value){
		return filter_var($value,FILTER_VALIDATE_EMAIL);
	}

	private function filter_url($value){
		if (! is_string($value)) {
			return false;
		}

		/*
			* This pattern is derived from Symfony\Component\Validator\Constraints\UrlValidator (5.0.7).
			*
			* (c) Fabien Potencier <fabien@symfony.com> http://symfony.com
			*/
		$pattern = '~^
				(aaa|aaas|about|acap|acct|acd|acr|adiumxtra|adt|afp|afs|aim|amss|android|appdata|apt|ark|attachment|aw|barion|beshare|bitcoin|bitcoincash|blob|bolo|browserext|calculator|callto|cap|cast|casts|chrome|chrome-extension|cid|coap|coap\+tcp|coap\+ws|coaps|coaps\+tcp|coaps\+ws|com-eventbrite-attendee|content|conti|crid|cvs|dab|data|dav|diaspora|dict|did|dis|dlna-playcontainer|dlna-playsingle|dns|dntp|dpp|drm|drop|dtn|dvb|ed2k|elsi|example|facetime|fax|feed|feedready|file|filesystem|finger|first-run-pen-experience|fish|fm|ftp|fuchsia-pkg|geo|gg|git|gizmoproject|go|gopher|graph|gtalk|h323|ham|hcap|hcp|http|https|hxxp|hxxps|hydrazone|iax|icap|icon|im|imap|info|iotdisco|ipn|ipp|ipps|irc|irc6|ircs|iris|iris\.beep|iris\.lwz|iris\.xpc|iris\.xpcs|isostore|itms|jabber|jar|jms|keyparc|lastfm|ldap|ldaps|leaptofrogans|lorawan|lvlt|magnet|mailserver|mailto|maps|market|message|mid|mms|modem|mongodb|moz|ms-access|ms-browser-extension|ms-calculator|ms-drive-to|ms-enrollment|ms-excel|ms-eyecontrolspeech|ms-gamebarservices|ms-gamingoverlay|ms-getoffice|ms-help|ms-infopath|ms-inputapp|ms-lockscreencomponent-config|ms-media-stream-id|ms-mixedrealitycapture|ms-mobileplans|ms-officeapp|ms-people|ms-project|ms-powerpoint|ms-publisher|ms-restoretabcompanion|ms-screenclip|ms-screensketch|ms-search|ms-search-repair|ms-secondary-screen-controller|ms-secondary-screen-setup|ms-settings|ms-settings-airplanemode|ms-settings-bluetooth|ms-settings-camera|ms-settings-cellular|ms-settings-cloudstorage|ms-settings-connectabledevices|ms-settings-displays-topology|ms-settings-emailandaccounts|ms-settings-language|ms-settings-location|ms-settings-lock|ms-settings-nfctransactions|ms-settings-notifications|ms-settings-power|ms-settings-privacy|ms-settings-proximity|ms-settings-screenrotation|ms-settings-wifi|ms-settings-workplace|ms-spd|ms-sttoverlay|ms-transit-to|ms-useractivityset|ms-virtualtouchpad|ms-visio|ms-walk-to|ms-whiteboard|ms-whiteboard-cmd|ms-word|msnim|msrp|msrps|mss|mtqp|mumble|mupdate|mvn|news|nfs|ni|nih|nntp|notes|ocf|oid|onenote|onenote-cmd|opaquelocktoken|openpgp4fpr|pack|palm|paparazzi|payto|pkcs11|platform|pop|pres|prospero|proxy|pwid|psyc|pttp|qb|query|redis|rediss|reload|res|resource|rmi|rsync|rtmfp|rtmp|rtsp|rtsps|rtspu|s3|secondlife|service|session|sftp|sgn|shttp|sieve|simpleledger|sip|sips|skype|smb|sms|smtp|snews|snmp|soap\.beep|soap\.beeps|soldat|spiffe|spotify|ssh|steam|stun|stuns|submit|svn|tag|teamspeak|tel|teliaeid|telnet|tftp|things|thismessage|tip|tn3270|tool|turn|turns|tv|udp|unreal|urn|ut2004|v-event|vemmi|ventrilo|videotex|vnc|view-source|wais|webcal|wpid|ws|wss|wtai|wyciwyg|xcon|xcon-userid|xfire|xmlrpc\.beep|xmlrpc\.beeps|xmpp|xri|ymsgr|z39\.50|z39\.50r|z39\.50s)://                                 # protocol
				(((?:[\_\.\pL\pN-]|%[0-9A-Fa-f]{2})+:)?((?:[\_\.\pL\pN-]|%[0-9A-Fa-f]{2})+)@)?  # basic auth
				(
						([\pL\pN\pS\-\_\.])+(\.?([\pL\pN]|xn\-\-[\pL\pN-]+)+\.?) # a domain name
								|                                                 # or
						\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                    # an IP address
								|                                                 # or
						\[
								(?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
						\]  # an IPv6 address
				)
				(:[0-9]+)?                              # a port (optional)
				(?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%[0-9A-Fa-f]{2})* )*          # a path
				(?:\? (?:[\pL\pN\-._\~!$&\'\[\]()*+,;=:@/?]|%[0-9A-Fa-f]{2})* )?   # a query (optional)
				(?:\# (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%[0-9A-Fa-f]{2})* )?       # a fragment (optional)
		$~ixu';

    return preg_match($pattern, $value) > 0;
	}

	private function filter_numeric($value,$params,$field){
		if(is_numeric($value)){
			return true;
		}

		return false;
	}

	private function filter_digits($value,$params){
		$size = $this->getNumber(array_pop($params));

		return !preg_match('/[^0-9]/', $value) && strlen((string) $value) == $size;
	}


	


	private function filter_size($value,$params){

		$size = $this->getNumber(array_pop($params));

		if($this->filter_file($value)){
			return $value->getSize() == $size;
		}

		if(is_array($value)){
			return count($value) == $size;
		}


		if(is_string($value)){
			return strlen($value) == $size;
		}

		return false;
	}


	private function filter_in($value,$params){

		if(in_array($value,$params)){
			return true;
		}

		return false;
	}

	private function filter_gte($value,$params){
		$param = $this->getNumber(array_pop($params));

		return $value >= $param;
	}

	private function filter_gt($value,$params){
		$param = array_pop($params);

		return $value > $param;
	}


	private function filter_lte($value,$params){
		$param = $this->getNumber(array_pop($params));

		return $value <= $param;
	}


	private function filter_lt($value,$params){
		$param = $this->getNumber(array_pop($params));

		return $value < $param;
	}


	private function filter_min($value,$params){
		$min = $this->getNumber(array_pop($params));

		if($this->filter_file($value)){
			return $value->getSize() >= $min;
		}

		if(is_string($value)){
			return strlen($value) >= $min;
		}

		if(is_array($value)){
			return count($value) >= $min;
		}

		return $value >= $min;
	}


	private function filter_max($value,$params){
		$max = $this->getNumber(array_pop($params));

		if($value instanceof File){
			return $value->getSize() <= $max;
		}

		if(is_string($value)){
			return strlen($value) <= $max;
		}

		if(is_array($value)){
			return count($value) <= $max;
		}

		return $value <= $max;
	}



	/** type filters */

	private function filter_boolean($value){

		if(is_string($value)){
			if ($value === 'true') {
				$value = true;
			} elseif ($value === 'false') {
				$value = false;
			}
		}


		$acceptable = [true, false, 0, 1, '0', '1'];

    if(in_array($value, $acceptable, true)){

			return true;
		}

		return false;
	}

	private function filter_integer($value){

		if(filter_var($value, FILTER_VALIDATE_INT) !== false){

			return true;
		}

		return false;
	}

	private function filter_string($value){

		if(is_string($value)){

			return true;
		}

		return false;
	}

	private function filter_date($value)
	{
        if ($value instanceof DateTimeInterface) {
                return true;
        }

        if ((! is_string($value) && ! is_numeric($value)) || strtotime($value) === false) {
                return false;
        }

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }
    

    private function filter_array($value){
        if(is_array($value)){
            return true;
        }

        return false;
    }

}