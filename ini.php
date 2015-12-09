<?hh

$dir = dirname(__FILE__);
require_once $dir.'/facePost/facePost.php';
require_once $dir.'/DateRetriever/retriever.php';

$codeForces = new RetrieverCodeForces();
$topCoder = new RetrieverTopCoder();
//$uri = new RetrieverURI();
$face = new Face();
$sites = array($codeForces, $topCoder);

foreach($sites as $site) {
    $contest_time = $site->getDate();

    $tz = new DateTimeZone('UTC');

    $contest_time->setTimezone($tz);

    date_default_timezone_set('UTC');

    $current_time = new DateTime(date('Y-m-d\TH:i:sP'));
    $tomorrow_time =
        new DateTime(date('Y-m-d\TH:i:sP', strtotime('+24 hours')));
    $after_tomorrow_time =
        new DateTime(date('Y-m-d\TH:i:sP', strtotime('+48 hours')));

    $world_clock_args = 
        "?hour={$contest_time->format('H')}&".
        "min={$contest_time->format('i')}&".
        "day={$contest_time->format('d')}&".
        "month={$contest_time->format('m')}&".
        "year={$contest_time->format('Y')}";
    $world_clock_link =
        "https://www.timeanddate.com/worldclock/fixedtime.html".
        $world_clock_args;

    $menssagem =
        "There's a {$site->nome} contest tomorrow!\n".
        "Coding Starts at {$contest_time->format('H:i')} UTC!\n".
        $world_clock_link;

    if ($contest_time > $tomorrow_time &&
        $contest_time <= $after_tomorrow_time) {
        $face->postToCodingContests($menssagem, $site->link);
    } else if ($contest_time > $current_time &&
               $contest_time <= $tomorrow_time) {
        $menssagem = str_replace("tomorrow", "today", $menssagem);
        $face->postToCodingContests($menssagem, $site->link);
    }
}