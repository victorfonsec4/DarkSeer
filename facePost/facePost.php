<?hh
$currentDir = dirname(__FILE__); 
require_once $currentDir.'/../includes/facebook/autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class Face
{
	private function getUserToken():string
	{
		$myfile = fopen("token", "r");
		
		return fread($myfile,filesize("token"));
	}
	public function postToCodingContests(string $link, string $message) : void
	{
		FacebookSession::setDefaultApplication('1500260360223545', 'ca5882a5a5ce92ce25d474e5ab784dd1');
		$session = new FacebookSession($this->getUserToken());
		$request = new FacebookRequest($session, 'GET', '/me?fields=accounts');
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		$pageAccessToken = $graphObject->getProperty('accounts')->getProperty('data')->getProperty('0')->getProperty('access_token');
		$pageSessionn = new FacebookSession($pageAccessToken);
		$msg = array('link' => $link,
			'message' => $message);
		$request = new FacebookRequest($pageSessionn, 'POST', '/me/feed', $msg);
		$request->execute();
	}
}
