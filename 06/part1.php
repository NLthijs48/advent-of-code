<?php

namespace day06\part1;

$input = file_get_contents('input.txt');

for ($index=0; $index<(strlen($input)-4); $index++) {
	$fourChars = substr($input, $index, 4);
	$separateChars = str_split($fourChars, 1);
	$uniqueChars = array_values(array_unique($separateChars));
	if (count($uniqueChars) === 4) {
		echo "Found at ".($index+4).": {$fourChars}\n";
		break;
	}
}
