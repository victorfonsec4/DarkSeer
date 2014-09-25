<?hh

newtype MinhaData = shape('dia' => int, 'mes' => int, 'ano' => int);
newtype MeuHorario = shape('hora' => int, 'minuto' => int);

include_once('../includes/xhp/init.php');
include_once('../includes/simple_html_dom.php');

abstract class Retriever
{
	abstract public function getDate() : (MinhaData, MeuHorario);

	protected function convertTime(MinhaData $dataOrigem, MeuHorario $horarioOrigem, string $timeZone) :(MinhaData, MeuHorario)
	{
		$dataString = "{$dataOrigem['ano']}-{$dataOrigem['mes']}-{$dataOrigem['dia']} {$horarioOrigem['hora']}:{$horarioOrigem['minuto']}:00";
		$zonedData = new DateTime($dataString, new DateTimeZone($timeZone));
		$zonedData->setTimezone(new DateTimeZone ('America/Sao_Paulo'));
		$dataString = $zonedData->format('d-m-Y-H-i');
		$dataSplit = explode('-', $dataString);
		$data = shape('dia' => $dataSplit[0], 'mes' => $dataSplit[1], 'ano' => $dataSplit[2]);
		$horario = shape('hora' => $dataSplit[3], 'minuto' => $dataSplit[4]);
		return tuple($data, $horario);
	}
}

final class RetrieverCodeForces extends Retriever
{
	public function getDate() : (MinhaData, MeuHorario)
	{
		$html = file_get_html("http://codeforces.com/contests");
		$dataString = $html->find('tr[data-contestid]', 0)->find('a[href]', 0)->plaintext;
		$dataString = trim($dataString);
		$dataSplit = preg_split("@[:/ ]@", $dataString);
		$dataSplit[0] = date('m', strtotime($dataSplit[0]));
		$data = shape('dia' => $dataSplit[1], 'mes' => $dataSplit[0], 'ano' => $dataSplit[2]);
		$horario = shape('hora' => $dataSplit[3], 'minuto' => $dataSplit[4]); 
		var_dump($data);
		var_dump($data);
		return parent::convertTime($data, $horario, "Europe/Moscow");
	}
}

$a = new RetrieverCodeForces();
var_dump($a->getDate());
