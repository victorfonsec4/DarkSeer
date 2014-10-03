<?hh

newtype MinhaData = shape('dia' => int, 'mes' => int, 'ano' => int);
newtype MeuHorario = shape('hora' => int, 'minuto' => int);

$currentDir = dirname(__FILE__); 
require_once $currentDir.'/../includes/xhp/init.php';
require_once $currentDir.'/../includes/simple_html_dom.php';

abstract class Retriever
{
	abstract public function getDate() : (MinhaData, MeuHorario);
	public string $nome;
	public string $link;

	public function __construct()
	{
		$this->nome = "abstrato";
		$this->link = "abstrato.imaginario.bolacha";
	}

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
	public function __construct()
	{
		parent::__construct();
		$this->nome = "CodeForces";
		$this->link = "http://www.codeforces.com/contests/";
	}
	public function getDate() : (MinhaData, MeuHorario)
	{
		$html = file_get_html("http://codeforces.com/contests");
		$dataString = $html->find('tr[data-contestid]', 0)->find('a[href]', 0)->plaintext;
		$dataString = trim($dataString);
		$dataSplit = preg_split("@[:/ ]@", $dataString);
		$dataSplit[0] = date('m', strtotime($dataSplit[0]));
		$data = shape('dia' => $dataSplit[1], 'mes' => $dataSplit[0], 'ano' => $dataSplit[2]);
		$horario = shape('hora' => $dataSplit[3], 'minuto' => $dataSplit[4]); 
		return parent::convertTime($data, $horario, "Europe/Moscow");
	}
}

final class RetrieverTopCoder extends Retriever
{
	public function __construct()
	{
		parent::__construct();
		$this->nome = "TopCoder";
		$this->link = "http://www.topcoder.com/tc?d1=calendar&d2=thisMonth&module=Static";
	}
	protected function getDateAux(string $URL, bool $primeira) : (MinhaData, MeuHorario)
	{
		$html = file_get_html($URL);
		$srmDays = $html->find('table[class=calendar] div[class]');
		foreach($srmDays as $day)
		{
			$dataSplit =  preg_split('/\s\s+/', $day->parent()->plaintext);
			date_default_timezone_set('America/New_York');
			$hojeDia = date('d');
			$hojeHora = date('H:i');
			if(($primeira === false) || ($hojeDia < $dataSplit[0] || ($hojeDia == $dataSplit[0] && $hojeHora < $dataSplit[2])))
			{
				$month = date('m');
				if($primeira === false)
					$month = $month+1;
				$data = shape('dia' => $dataSplit[0], 'mes' => $month, 'ano' => date('Y'));
				$horario = shape('hora' => explode(':', $dataSplit[2])[0], 'minuto' => explode(':', $dataSplit[2])[1]); 
				return parent::convertTime($data, $horario, "America/New_York");
			}
		}
		if($primeira === false)
			throw new Exception("Mês errado no TopCoder\n");
		throw new Exception("Não conseguiu pegar data no TopCoder\n");
	}
	public function getDate() : (MinhaData, MeuHorario)
	{
		try{
			return $this->getDateAux("http://www.topcoder.com/tc?d1=calendar&d2=thisMonth&module=Static", true);
		}catch (Exception $e)
		{
			$html = file_get_html("http://www.topcoder.com/tc?d1=calendar&d2=thisMonth&module=Static");
			$nextMonth = $html->find('strong')[0]->last_child()->href;
			$nextMonth = str_replace("amp;", "", $nextMonth);
			$link = "http://www.topcoder.com{$nextMonth}";
			return $this->getDateAux($link, false);
		}
	}
}

final class RetrieverURI extends Retriever
{	
	public function __construct()
	{
		parent::__construct();
		$this->nome = "URI";
		$this->link = "http://www.urionlinejudge.com.br/judge/en/contests/";
	}
	public function getDate() : (MinhaData, MeuHorario)
	{
		$html = file_get_html("https://www.urionlinejudge.com.br/judge/en/contests");
		$torneios = $html->find('div[id=table]', 0)->find('tr[class]');
		$torneios = array_reverse($torneios);
		date_default_timezone_set('America/Sao_Paulo');
		foreach($torneios as $torneio)
		{
			if($torneio->children(1) != null && $torneio->children(1)->children(0)->alt == "Public")
			{
				$dataString = $torneio->children(3)->plaintext;
				$dataSplit =  explode(' ', $dataString);
				$dataSplit = preg_split("@[:/ ]@", $dataString);
				if($dataSplit[2] > date('Y') || ($dataSplit[2] == date('Y') && $dataSplit[1] > date('m')) || 
												(($dataSplit[2] == date('Y') && $dataSplit[1] == date('m') && $dataSplit[0] > date('d')) || 
												($dataSplit[0] == date('d') && ($dataSplit[3] >= date('H')))))
				{
					$data = shape('dia' => $dataSplit[0], 'mes' => $dataSplit[1], 'ano' => $dataSplit[2]);
					$horario = shape('hora' => $dataSplit[3], 'minuto' => $dataSplit[4]); 
					return tuple($data, $horario);
				}
			}
		}
		throw new Exception("Não conseguiu achar data no URI\n");
	}
}
