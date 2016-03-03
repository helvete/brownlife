<?php
define('FILE_PATH', __DIR__ . '/mockDB');
$password = trim(file_get_contents('password.cfg'));
if (empty($password)) {
	echo "Missing or empty password file!";
	exit(2);
}
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
	header("HTTP/1.0 403 Forbidden");
	echo "Nothing to see here..";
	exit;
}

$now = new DateTime('2016-03-01');
$threshold = new DateTime('2016-04-01');
$storage = getArrayStorage($now, $threshold, 3);

if (!empty($_GET['change'])) {
	if (empty($_GET['pwd']) || $_GET['pwd'] !== $password) {
		header("HTTP/1.0 401 Unauthorized");
		echo "Failed to supply correct passphrase";
		exit;
	}

	list($row, $col) = explode('-', $_GET['change']);
	storeArrayStorage($row, $col);
	header('location: https://'. $_SERVER['HTTP_HOST']);
	exit;
}
?>
<html>
	<head>
		<title>DrugTracker</title>
		<style>

			.square {
				width: 50px;
				height: 50px;
				border: 1px solid black;
			}
			th.square {
				font-size: 75%;
				font-weight: normal;
				font-style: italic;
			}
			.active {
				cursor: pointer;
			}
			.container {
				width: 300px;
				margin: 0px auto;
			}
			.highlight {
				background-color: brown;
			}
		</style>
		<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
		<script>
			$(document).ready(function(){
				$('td.active').click(function(){
					var target = window.location.href;
					target = target + '?change=' + $(this).attr('id') + "&pwd=" + $('input#pwd').val();
					window.location.href = target;
				});
			});
		</script>
	</head>
	<body>
		<div class="container">
			<table>
				<tr>
					<th class="date">
						<form>
							<?/* Dummy input to engage password managers*/?>
							<input type="text" name="login" style="display:none;" />
							<input type="password" id="pwd" name="pwd" size="8" />
							<input type="submit" name="submit" value="" style="display:none;" />
						</form>
					</th>
					<th class="square">Tobacco</th>
					<th class="square">Booze</th>
					<th class="square">Weed</th>
				</tr>
<?php
$token = clone $now;
$it = 0;
while ($token < $threshold) {
	$date = $token->format('Y-m-d');
	echo <<<HTM
		<tr>
			<td class="date">$date</td>
			<td id="{$it}-0" class="square active {$storage[$date][0]}"></td>
			<td id="{$it}-1" class="square active {$storage[$date][1]}"></td>
			<td id="{$it}-2" class="square active {$storage[$date][2]}"></td>
		</tr>
HTM;
	$it++;
	$token->add(new DateInterval('P1D'));
}
?>
			</div>
		</table>
	</body>
</html>
<?php

function getArrayStorage($startDate, $endDate, $colsCount) {
	if (file_exists(FILE_PATH)) {
		return unserialize(file_get_contents(FILE_PATH));
	}
	$data = array();
	$token = clone $startDate;
	$it = 1;
	while ($token < $endDate) {
		$date = $token->format('Y-m-d');
		foreach (range(0, $colsCount - 1) as $order) {
			$data[$date][$order] = "";
		}
		$token->add(new DateInterval('P1D'));
	}
	file_put_contents(FILE_PATH, serialize($data));

	return $data;
}

function storeArrayStorage($row, $col) {
	global $storage;

	$i = -1;
	foreach ($storage as $date => $item) {
		$i++;
		if ($i < $row) {
			continue;
		}
		if ($i == $row) {
			$storage[$date][$col] = $storage[$date][$col] == false
				? 'highlight'
				: false;
		}
	}

	file_put_contents(FILE_PATH, serialize($storage));
}
?>
