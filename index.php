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

if(!empty($_POST)) {
	$db = mysqli_connect('localhost','root','','kmmo');
	if (!mysqli_connect_errno()) {
		$ip = '1.1.1.1';
		$name = 'A-001';
		$message = $_POST['message'];
		$message = $db->real_escape_string($message);
		$sql = "INSERT INTO posts (ip, name, message) VALUES ('$ip', '$name', '$message')";
		mysqli_query($db, $sql);
		mysqli_close($db);
  	}
}
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
                <div class="col-lg-6">
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
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <form class="input-group" method="post">
                                <input class="form-control" placeholder="Message" type="text" name="message">
                                <div class="input-group-append">
                                    <input type="submit" class="btn btn-secondary" value="Post">
                                </div>
                            </form>
                        </div>
                        <ul class="list-group card-list-group">
                            <li class="list-group-item py-5">
                                <div class="media">
                                    <div class="media-object avatar avatar-md mr-4" style="background-image: url(demo/faces/male/16.jpg)"></div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <small class="float-right text-muted">4 min</small>
                                            <h5>Peter Richards</h5>
                                        </div>
                                        <div>
                                            Aenean lacinia bibendum nulla sed consectetur. Vestibulum id ligula porta felis euismod semper. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                                        </div>
                                        <ul class="media-list">
                                            <li class="media mt-4">
                                                <div class="media-object avatar mr-4" style="background-image: url(demo/faces/female/17.jpg)"></div>
                                                <div class="media-body">
                                                    <strong>Debra Beck: </strong> Donec id elit non mi porta gravida at eget metus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec ullamcorper nulla non metus auctor fringilla. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Sed posuere consectetur est at lobortis.
                                                </div>
                                            </li>
                                            <li class="media mt-4">
                                                <div class="media-object avatar mr-4" style="background-image: url(demo/faces/male/32.jpg)"></div>
                                                <div class="media-body">
                                                    <strong>Jack Ruiz: </strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item py-5">
                                <div class="media">
                                    <div class="media-object avatar avatar-md mr-4" style="background-image: url(demo/faces/male/16.jpg)"></div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <small class="float-right text-muted">12 min</small>
                                            <h5>Peter Richards</h5>
                                        </div>
                                        <div>
                                            Donec id elit non mi porta gravida at eget metus. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item py-5">
                                <div class="media">
                                    <div class="media-object avatar avatar-md mr-4" style="background-image: url(demo/faces/male/16.jpg)"></div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <small class="float-right text-muted">34 min</small>
                                            <h5>Peter Richards</h5>
                                        </div>
                                        <div>
                                            Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
                                        </div>
                                        <ul class="media-list">
                                            <li class="media mt-4">
                                                <div class="media-object avatar mr-4" style="background-image: url(demo/faces/male/26.jpg)"></div>
                                                <div class="media-body">
                                                    <strong>Wayne Holland: </strong> Donec id elit non mi porta gravida at eget metus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec ullamcorper nulla non metus auctor fringilla. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Sed posuere consectetur est at lobortis.
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>