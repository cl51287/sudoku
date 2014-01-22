<?php
require_once 'Square.php';
require_once 'Guesser.php';
$nums	= array(
	array(0, 0, 8, 3, 0, 9, 1, 0, 0),
	array(9, 0, 0, 0, 6, 0, 0, 0, 4),
	array(0, 0, 7, 5, 0, 4, 8, 0, 0),
	array(0, 3, 6, 0, 0, 0, 5, 4, 0),
	array(0, 0, 1, 0, 0, 0, 6, 0, 0),
	array(0, 4, 2, 0, 0, 0, 9, 7, 0),
	array(0, 0, 5, 9, 0, 7, 3, 0, 0),
	array(6, 0, 0, 0, 1, 0, 0, 0, 8),
	array(0, 0, 4, 6, 0, 8, 2, 0, 0),
);

$nums	= array(
	array(0, 6, 0, 5, 9, 3, 0, 0, 0),
	array(9, 0, 1, 0, 0, 0, 5, 0, 0),
	array(0, 3, 0, 4, 0, 0, 0, 9, 0),
	array(1, 0, 8, 0, 2, 0, 0, 0, 4),
	array(4, 0, 0, 3, 0, 9, 0, 0, 1),
	array(2, 0, 0, 0, 1, 0, 6, 0, 9),
	array(0, 8, 0, 0, 0, 6, 0, 2, 0),
	array(0, 0, 4, 0, 0, 0, 8, 0, 7),
	array(0, 0, 0, 7, 8, 5, 0, 1, 0),
);

$nums	= array(
	array(0, 0, 8, 0, 0, 0, 2, 0, 0),
//	array(0, 0, 8, 0, 0, 0, 0, 0, 0),
	array(0, 3, 0, 8, 0, 2, 0, 6, 0),
	array(7, 0, 0, 0, 9, 0, 0, 0, 5),
	array(0, 5, 0, 0, 0, 0, 0, 1, 0),
	array(0, 0, 4, 0, 0, 0, 6, 0, 0),
	array(0, 2, 0, 0, 0, 0, 0, 7, 0),
	array(4, 0, 0, 0, 8, 0, 0, 0, 6),
	array(0, 7, 0, 1, 0, 3, 0, 9, 0),
	array(0, 0, 1, 0, 0, 0, 8, 0, 0),
);

$nums	= array(
	array(0, 0, 1, 0, 0, 0, 6, 0, 0),
	array(0, 5, 9, 0, 0, 2, 0, 0, 0),
	array(4, 0, 0, 0, 0, 6, 0, 0, 2),
	array(0, 0, 0, 8, 7, 0, 0, 1, 0),
	array(2, 0, 0, 0, 9, 0, 0, 0, 7),
	array(0, 4, 0, 0, 5, 3, 0, 0, 0),
	array(8, 0, 0, 5, 0, 0, 0, 0, 6),
	array(0, 0, 0, 1, 0, 0, 7, 9, 0),
	array(0, 0, 4, 0, 0, 0, 5, 0, 0),
);

// 17位
$nums	= array(
	array(0, 0, 0, 0, 0, 0, 5, 2, 0),
	array(0, 8, 0, 4, 0, 0, 0, 0, 0),
	array(0, 3, 0, 0, 0, 9, 0, 0, 0),
	array(5, 0, 1, 0, 0, 0, 6, 0, 0),
	array(2, 0, 0, 7, 0, 0, 0, 0, 0),
	array(0, 0, 0, 3, 0, 0, 0, 0, 0),
	array(6, 0, 0, 1, 0, 0, 0, 0, 0),
	array(0, 0, 0, 0, 0, 0, 7, 0, 4),
	array(0, 0, 0, 0, 0, 0, 0, 3, 0),
);

// 号称最难数独
$nums	= array(
	array(8, 0, 0, 0, 0, 0, 0, 0, 0),
	array(0, 0, 3, 6, 0, 0, 0, 0, 0),
	array(0, 7, 0, 0, 9, 0, 2, 0, 0),
	array(0, 5, 0, 0, 0, 7, 0, 0, 0),
	array(0, 0, 0, 0, 4, 5, 7, 0, 0),
	array(0, 0, 0, 1, 0, 0, 0, 3, 0),
	array(0, 0, 1, 0, 0, 0, 0, 6, 8),
	array(0, 0, 8, 5, 0, 0, 0, 1, 0),
	array(0, 9, 0, 0, 0, 0, 4, 0, 0),
);

$start	= microtime(true);
$square	= new Square($nums);

if ($square->isOk()) {
	$square->draw();
} else {
	$guesser	= new Guesser($square);
	if ($guesser->guess()) {
		$guesser->getTrueSquare()->draw();
	} else {
		echo 'can not complete the square.';
	}
}

echo microtime(true) - $start;

var_dump($guesser->getTrueSquare()->checkCorrect());