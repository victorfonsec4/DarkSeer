<?hh

newtype MinhaData = shape('dia' => int, 'mes' => int, 'ano' => int);
newtype MeuHorario = shape('hora' => int, 'minuto' => int);

include_once('../includes/xhp/init.php');
include_once('../includes/simple_html_dom.php');
include_once('../includes/structs.php');

abstract class Retriever{
	abstract public function getDate() : (MinhaData, MeuHorario);
	private function convertTime(MinhaData $dataOrigem, MeuHorario $horarioOrigem, int $timeZone) :(MinhaData, MeuHorario)
	{
		$data = shape('dia' => 0, 'mes' => 0, 'ano' => 0);
		$horario = shape('hora' => 0, 'minuto' => 0);
		return tuple($data, $horario);
	}
}

final class RetrieverCodeForces extends Retriever
{
	public function getDate() : (MinhaData, MeuHorario)
	{
		$html = file_get_html("http://codeforces.com/contests");
		$dateString = $html->find('tr[data-contestid]', 0)->find('a[href]', 0)->plaintext;
		$dateString = trim($dateString);
		$date = preg_split("@[:/ ]@", $dateString);
		$date[0] = date('m', strtotime(date[0]));
		$data = shape('dia' => date[1], 'mes' => date[0], 'ano' => date[2]);
		$horario = shape('hora' => date[3], 'minuto' => date[4]);
		var_dump($data);
		var_dump($horario);
		return tuple($data, $horario);
	}
}

$a = new RetrieverCodeForces();
$a->getDate();
