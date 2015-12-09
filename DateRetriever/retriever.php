<?hh

$currentDir = dirname(__FILE__);
require_once $currentDir.'/../includes/simple_html_dom.php';
require_once __DIR__ . '/../vendor/autoload.php';

define("CONTEST_NOT_FOUND", "not_found");

abstract class Retriever
{
	abstract public function getDate() : DateTime;
	public string $nome;
	public string $link;

	public function __construct()
	{
		$this->nome = "abstrato";
		$this->link = "abstrato.imaginario.bolacha";
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
	public function getDate() : DateTime
	{
		$html = file_get_html("http://codeforces.com/contests");
		$dataString = $html
                    ->find('tr[data-contestid]', 0)
                    ->find('a[href]', 0)->plaintext;
		$dataString = trim($dataString);
        $dataString = str_replace("/", " ", $dataString);

        date_default_timezone_set("Europe/Moscow");
        $data = new DateTime();
        $data->setTimestamp(strtotime($dataString));

		return $data;
	}
}

final class RetrieverTopCoder extends Retriever
{
	public function __construct()
	{
		parent::__construct();
		$this->nome = "TopCoder";
		$this->link = "https://www.topcoder.com/community/events/";
	}

	public function getDate() : DateTime
	{
        $client = new Google_Client();
        $client->setApplicationName("DarkSeer");

        $currentDir = dirname(__FILE__);

        $token = file_get_contents($currentDir."/google_token");
        $client->setDeveloperKey($token);

        $service = new Google_Service_Calendar($client);

        $optParams = array('q' => 'srm -registration');
        $calendar_id =
            'appirio.com_bhga3musitat85mhdrng9035jg@group.calendar.google.com';
        $events = $service->events
                ->listEvents($calendar_id);
        $items = $events['modelData']['items'];

        date_default_timezone_set('America/New_York');
        $current_time = new DateTime(date('Y-m-d\TH:i:sP'));

        $next_contest_time =
            new DateTime(date('Y-m-d\TH:i:sP', strtotime('+1 year')));
        $next_contest_name = CONTEST_NOT_FOUND;

        foreach ($items as $item) {
            $srm_pattern = '/^SRM [0-9]+$/';
            if(array_key_exists('summary', $item) &&
               preg_match($srm_pattern, $item['summary']) &&
               new DateTime($item['start']['dateTime']) > $current_time &&
               new DateTime($item['start']['dateTime']) < $next_contest_time) {
                $next_contest_time = new DateTime($item['start']['dateTime']);
                $next_contest_name = $item['summary'];
            }
        }

        return $next_contest_time;
    }
}

/*final class RetrieverURI extends Retriever
{
    public function __construct()
    {
        parent::__construct();
        $this->nome = "URI";
        $this->link = "http://www.urionlinejudge.com.br/judge/en/contests/";
    }
    public function getDate() : (DataInicio, NomeContest)
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
                   (($dataSplit[2] == date('Y') && $dataSplit[1] == date('m') && ($dataSplit[0] > date('d') ||
                                                                                  ($dataSplit[0] == date('d') && ($dataSplit[3] >= date('H')))))))
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
*/
