<?hh

include_once('../includes/xhp/init.php');
include_once('../includes/simple_html_dom.php');

abstract class Retriever{
	abstract public function getDate() : (int, int, int, int);
}

final class RetrieverCodeForces extends Retriever
{
	public function getDate() : (int, int, int, int)
	{
		$html = file_get_html("http://codeforces.com/contests");
		($html->find('tr[data-contestid]', 0)->find('a[href]', 0)->plaintext);
		return tuple(1, 1, 1, 1);
	}
}

$a = new RetrieverCodeForces();
$a->getDate();
