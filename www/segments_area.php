<?php
	error_reporting(1);
	ini_set('display_errors', 1);
	include_once '../lib/RoadsHelper.php';
	include_once '../lib/DB.php';
	include_once '../lib/TestsFactory.php';
	if (!isset($_GET['area'])){
		die;
	}
	preg_match('(\d+)', $_GET['area'], $m);
	$area_id = reset($m);
	$nav = array('/' => 'Главная страница');
	$road = Waze\RoadsHelper::instance();
	$db = Waze\DB::instance();
	$tests = new Waze\TestsFactory();
	$area_name = $db->as_array('SELECT areas_mapraid.name FROM areas_mapraid WHERE areas_mapraid.id = ' . $area_id);
	if (count($area_name) != 1) {
		die;
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Россия</title>
	<link rel="stylesheet" media="all" href="builds/bootstrap.min.css" data-turbolinks-track="true">
	<link rel="stylesheet" href="builds/site.css">
</head>
<body>
	<header class="navbar navbar-fixed-top bg-info">
		<nav class="navbar-inner">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 col-md-8 col-lg-8">
						<h4>Россия</h4>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4">
						<ol class="breadcrumb text-right">
							<?php foreach ($nav as $url => $title) { ?>
								<?php if($_SERVER['REQUEST_URI'] == $url) { ?>
									<li class="active small"><?= $title ?></li>
								<?php } else { ?>
									<li class="small">
										<a href="<?= $url ?>"><?= $title ?></a>
									</li>
								<?php } ?>
							<?php } ?>
						</ol>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<div id="main" role="main">
		<div class="container">
			<div class="row">
				<div class="span12">
					<!--main-->
					<div class="page-header">
						<h3><?= $area_name[0]['name'] ?></h3>
					</div>
					<div id="accordion" class="panel-group" role="tablist" aria-multiselecttable="true">
						<?= $tests->area($area_id) ?>
					</div>
					<!--endmain-->
				</div>
			</div>
		</div>
	</div>
	<footer class="container">
		<div class="row">
			<div class="col-sm-0 col-md-3 col-lg-3">&nbsp;</div>
			<div class="col-sm-12 col-md-3 col-lg-3">
				<!--<a href="/options" class="text-right">Настройки</a>-->
			</div>
		</div>
	</footer>
	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.2.0/list.min.js"></script>
	<script src="builds/bootstrap.min.js" data-turbolinks-track="true"></script>
	<script src="builds/filter.js"></script>
</body>
</html>
