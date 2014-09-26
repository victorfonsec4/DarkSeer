<?hh 

$currentDir = dirname(__FILE__); 
require_once $currentDir.'/includes/xhp/init.php';
require_once $currentDir.'/DateRetriever/retriever.php';

$a = new RetrieverURI();
$body = "http://www.facebook.com";
echo <a href = "www.facebook.com" >{$a->getDate()}</a>;
