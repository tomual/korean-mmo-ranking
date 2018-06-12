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
// get all table rows and rows which are not headers
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

echo '<pre>';
print_r($ranking);

?>