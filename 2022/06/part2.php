<?php

namespace day06\part2;

$input = file_get_contents('input.txt');

//$input = 'zcfzfwzzqfrljwzlrfnpqdbhtmscgvjw';

$detectLength = 14;
for ($index=0; $index<(strlen($input)- $detectLength); $index++) {
	$fourChars = substr($input, $index, $detectLength);
	$separateChars = str_split($fourChars, 1);
	$uniqueChars = array_values(array_unique($separateChars));
	if (count($uniqueChars) === $detectLength) {
		echo "Found at ".($index+ $detectLength).": {$fourChars}\n";
		break;
	}
}
