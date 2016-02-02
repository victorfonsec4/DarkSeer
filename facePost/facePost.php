<?hh
$dirFace = dirname(__FILE__);
require_once $dirFace.'/../includes/facebook/autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class Face
{
	private function getPageToken():string
	{
		$dirFace = dirname(__FILE__);
        $token = file_get_contents("{$dirFace}/token");

		return $token;
	}
	public function postToCodingContests(string $message, string $link) : void
	{
		FacebookSession::setDefaultApplication('1500260360223545', 'page-secret-here');
		$pageAccessToken = $this->getPageToken();
		$pageSessionn = new FacebookSession($pageAccessToken);
		$msg = array('link' => $link,
			'message' => $message);
		$request = new FacebookRequest($pageSessionn, 'POST', '/me/feed', $msg);
		$request->execute();
	}
}
