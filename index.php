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

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="https://tabler.github.io/tabler/assets/js/vendors/jquery-3.2.1.min.js"></script>
	<script src="https://tabler.github.io/tabler/assets/js/vendors/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://tabler.github.io/tabler/assets/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&subset=latin-ext">
	<link rel="stylesheet" type="text/css" href="https://tabler.github.io/tabler/assets/css/dashboard.css">
</head>
<body>
 <div class="page">
    <div class="page-single">
        <div class="container">
            <div class="row m-6">
                <div class="col-lg-6 m-auto">
                    <div class="card">
                        <div class="card-header p-6">
                            <div class="card-title">Koream MMO Ranking
								<div class="small text-muted">June 12 2018 - June 19 2018</div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                            <?php foreach($ranking as $rank => $game): ?>
                            	<tr>
                            		<td class="text-center text-muted"><?php echo $rank + 1 ?></td>
                            		<td>
                            			<a href="<?php echo $game['website'] ?>" class="text-inherit" target="_blank"><?php echo $game['english'] ?></a>
                            			<div class="small text-muted"><?php echo $game['korean'] ?></div>
                            		</td>
                            		<td><a href="<?php echo $game['website'] ?>" target="_blank"><i class="fe fe-home"></i></a></td>
                            		<td><a href="<?php echo $game['wikipedia'] ?>" target="_blank"><i class="fe fe-info"></i></a></td>
                            	</tr>
                            <?php endforeach ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>