<?php

$input = file_get_contents('input.txt');

$inputLines = explode("\n", $input);

$mostCalories = 0;
$currentElfCalories = 0;
foreach ($inputLines as $inputLine) {
	if ($inputLine === '') {
		$mostCalories = max($currentElfCalories, $mostCalories);
		$currentElfCalories = 0;
		continue;
	}

	if (!is_numeric($inputLine)) {
		throw new Exception('input not numeric: '.$inputLine);
	}

	$calories = intval($inputLine);
	$currentElfCalories += $calories;
}

echo "Most calories: ".$mostCalories;
