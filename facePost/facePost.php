<?hh
$currentDir = dirname(__FILE__); 
require_once $currentDir.'/../includes/facebook/autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class Face
{
	private function getUserToken():string
	{
		return "abc";
	}
	public function postToCodingContests(string $link, string $message) : void
	{
		FacebookSession::setDefaultApplication('1500260360223545', '***REMOVED***');
		$session = new FacebookSession(getUserToken());
		$request = new FacebookRequest($session, 'GET', '/me?fields=accounts');
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		$pageAccessToken = $graphObject->getProperty('accounts')->getProperty('data')->getProperty('0')->getProperty('access_token');
		var_dump($pageAccessToken);
		$pageSessionn = new FacebookSession($pageAccessToken);
		$msg = array('link' => $link,
			'message' => $message);
		$request = new FacebookRequest($pageSessionn, 'POST', '/me/feed', $msg);
		$request->execute();
	}
}
