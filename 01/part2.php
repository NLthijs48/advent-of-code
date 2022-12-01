<?php

$input = file_get_contents('input.txt');

$inputLines = explode("\n", $input);

$elfCalories = [];
$currentElfCalories = 0;
foreach ($inputLines as $inputLine) {
	if ($inputLine === '') {
		$elfCalories[] = $currentElfCalories;
		$currentElfCalories = 0;
		continue;
	}

	if (!is_numeric($inputLine)) {
		throw new Exception('input not numeric: '.$inputLine);
	}

	$calories = intval($inputLine);
	$currentElfCalories += $calories;
}

usort($elfCalories, function (int $a, int $b) {
	return $a > $b ? -1 : 1;
});

$sum = 0;
for ($i=0; $i<3; $i++) {
	echo "Elf ".($i+1).": ".$elfCalories[$i]."\n";
	$sum += $elfCalories[$i];
}
echo "Sum: ".$sum."\n";
