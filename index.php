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

$posts = array();
$db = mysqli_connect('localhost','root','','kmmo');
if (!mysqli_connect_errno()) {
	$sql = 'SELECT * FROM posts';
	$query 	= mysqli_query($db, $sql);
	while ($row = mysqli_fetch_array($query))
	{
		$posts[] = $row;
	}
}
mysqli_close($db);

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
	<style>
		.avatar {
			background-position: bottom;
			background-size: 30px;
			overflow: hidden;
		}

		svg {
			width: 30px;
    		margin-top: 1px;
		}

	</style>
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
                        	<?php foreach($posts as $post): ?>
                            <li class="list-group-item py-5">
                                <div class="media">
                                    <div class="media-object avatar avatar-md mr-4">
                                    	<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="48px" height="48px" viewBox="0 0 48 48" enable-background="new 0 0 16 16" xml:space="preserve" fill="#FF0000"> <path d="M 42.00,45.00c0.00,1.659-1.341,3.00-3.00,3.00L9.00,48.00 c-1.656,0.00-3.00-1.341-3.00-3.00c0.00-6.00, 5.799-11.598, 11.727-13.812 C 14.304,29.073, 12.00,25.317, 12.00,21.00L12.00,18.00 c0.00-6.627, 5.373-12.00, 12.00-12.00s 12.00,5.373, 12.00,12.00l0.00,3.00 c0.00,4.317-2.304,8.073-5.724,10.188C 36.201,33.402, 42.00,39.00, 42.00,45.00z"></path></svg>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <small class="float-right text-muted"><?php echo $post['created'] ?></small>
                                            <h5><?php echo $post['ip'] ?></h5>
                                        </div>
                                        <div>
                                            <?php echo $post['message'] ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        	<?php endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>