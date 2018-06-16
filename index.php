<?php 
$file = "latest.php";
$handle = fopen($file, 'r');
$data = fread($handle,filesize($file));
$ranking = json_decode($data);

$date = $data;
$file = "json/$date.js";
$handle = fopen($file, 'r');
$data = fread($handle,filesize($file));
$ranking = json_decode($data);

$start = date('Y-m-d', strtotime($date));
$end = date('Y-m-d', strtotime('+6 days', strtotime($date)));
?>

<!DOCTYPE html>
<html>
<head>
	<title>Korean MMO Rankings 2018 - <?php echo date('F j', strtotime($start)) ?> to <?php echo date('F j', strtotime($end)) ?></title>
    <meta name="description" content="Weekly rankings of Korea's MMO games. The most popular games in Korea right now for the week <?php echo date('d/m/Y', strtotime($start)) ?> to <?php echo date('d/m/Y', strtotime($end)) ?>">
	<script src="https://tabler.github.io/tabler/assets/js/vendors/jquery-3.2.1.min.js"></script>
	<script src="https://tabler.github.io/tabler/assets/js/vendors/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://tabler.github.io/tabler/assets/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&subset=latin-ext">
	<link rel="stylesheet" type="text/css" href="https://tabler.github.io/tabler/assets/css/dashboard.css">
    <style type="text/css">
        a:hover {
            text-decoration: none;
        }
    </style>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WH3SL76');</script>
    <!-- End Google Tag Manager -->
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
								<div class="small text-muted"><?php echo date('F j Y', strtotime($start)) ?> - <?php echo date('F j Y', strtotime($end)) ?></div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                            <?php foreach($ranking as $rank => $game): ?>
                            	<tr>
                            		<td class="text-center text-muted"><?php echo $rank + 1 ?></td>
                            		<td>
                            			<a href="<?php echo $game->website ?>" class="text-inherit" target="_blank"><?php echo $game->english ?></a>
                            			<div class="small text-muted"><?php echo $game->korean ?></div>
                            		</td>
                            		<td><a href="<?php echo $game->website ?>" target="_blank"><i class="fe fe-home"><span class="sr-only">Home</span></i></a></td>
                            		<td><a href="<?php echo $game->wikipedia ?>" target="_blank"><i class="fe fe-info"><span class="sr-only">Info</span></i></a></td>
                            	</tr>
                            <?php endforeach ?>
                            </table>
                        </div>
                    </div>
                    <div class="text-center text-muted small">
                        Written by <a href="https://tomual.com/">tomual</a> using data from <a href="http://www.gamemeca.com/" target="_blank">GameMeca</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WH3SL76"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
</body>
</html>