<?hh

$dir = dirname(__FILE__); 
require_once $dir.'/facePost/facePost.php';
require_once $dir.'/DateRetriever/retriever.php';

$codeForces = new RetrieverCodeForces();
$topCoder = new RetrieverTopCoder();
$uri = new RetrieverURI();
$face = new Face();
$sites = array($codeForces, $topCoder, $uri);

foreach($sites as $site)
{
	try
	{
		$data = $site->getDate();
		if($data[0]['dia'] == date('d') + 1)
		{
			$menssagem = "There's a {$site->nome} contest tomorrow!\nCoding Starts at {$data[1]['hora']}:{$data[1]['minuto']} BRT(UTC-3)!";
			$face->postToCodingContests($menssagem, $site->link);
		}
		if($data[0]['dia'] == date('d'))
		{
			$menssagem = "There's a {$site->nome} contest today!\nCoding Starts at {$data[1]['hora']}:{$data[1]['minuto']} BRT(UTC-3)!";
			$face->postToCodingContests($menssagem, $site->link);
		}
	}catch (Exception $e)
	{
		error_log(var_dump($e->getMessage()));
	}	
}
