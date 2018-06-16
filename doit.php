<?php 
include('games.php');

$ch = curl_init ("http://www.gamemeca.com/popup/ranking.php?scode=O");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$page = curl_exec($ch);

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($page);
libxml_clear_errors();
$xpath = new DOMXpath($dom);

$data = array();
$header = $xpath->query('//html/body/div/div[1]/div[2]/p[1]');
foreach($header as $p) {
    foreach($p->childNodes as $text) {
        $title = $text->textContent;
    }
}
preg_match('/([0-9]+)년 ([0-9]+)월 ([0-9]+)일 /', $title, $matches);
$year = $matches[1];
$month = $matches[2];
$day = $matches[3];

$table_rows = $xpath->query('//html/body/div/div[1]/div[2]/table/tbody/tr');
foreach($table_rows as $row => $tr) {
    foreach($tr->childNodes as $td) {
        $data[$row][] = preg_replace('~[\r\n]+~', '', trim($td->nodeValue));
    }
    $data[$row] = array_values(array_filter($data[$row]));
}

$ranking = array();

foreach ($data as $row) {
	$game = array(
		'korean' => $row[1],
		'english' => $games[$row[1]]['english'] ?? null,
		'website' => $games[$row[1]]['website'] ?? null,
		'wikipedia' => $games[$row[1]]['wikipedia'] ?? null,
	);
	$ranking[] = $game;
}

$ranking = array_slice($ranking, 0, 15);

$date = "$year-$month-$day";
$file = "json/$date.js";
$handle = fopen($file, 'w') or die('Cannot open file:  '.$file);
$data = json_encode($ranking);
fwrite($handle, $data);

$file = 'latest.php';
$handle = fopen($file, 'w') or die('Cannot open file:  '.$file);
$data = $date;
fwrite($handle, $data);
?>