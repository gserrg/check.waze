<?php
	error_reporting(1);
	ini_set('display_errors', 1);
	$nav = array('/waze/' => 'Главная страница');
	include_once '../lib/DB.php';
	include_once '../lib/TestsFactory.php';
	$db = Waze\DB::instance();
	$tests = new Waze\TestsFactory();
	$areas = $db->as_array('SELECT areas_mapraid.name, areas_mapraid.id, updates.updated_at
		FROM areas_mapraid
		LEFT JOIN states_shapes ON(areas_mapraid.id = states_shapes.id_1)
		LEFT JOIN updates ON(states_shapes.hasc_1 = updates.object) ORDER BY areas_mapraid.name');
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
					<div class="container">
						<div class="row">
							<?php foreach ($areas as $area) { ?>
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="panel panel-default">
										<div class="panel-heading">
											<div class="panel-title">
												<?= $area['name'] ?>
											</div>
										</div>
										<div class="panel-body">
											<div class="text-right">
												<a href="segments_area.php?area=<?= $area['id'] ?>">Обновлено <?= strftime('%d/%m/%Y %H:%M %Z', strtotime($area['updated_at'])) ?></a>
											</div>
										</div>
										<ul class="list-group">
											<?= $tests->index($area['id']) ?>
										</ul>
									</div>
								</div>
							<?php } ?>
						</div>
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
</body>
</html>
