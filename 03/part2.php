<?php

namespace day03\part2;

$input = file_get_contents('input.txt');

$rucksackLines = explode("\n", $input);

$prioritySum = 0;
$sharedItems = null;
foreach ($rucksackLines as $index => $rucksackLine) {
	if ($rucksackLine === '') {
		continue;
	}

	$rucksackItems = str_split($rucksackLine, 1);
	$uniqueRucksackItems = array_values(array_unique($rucksackItems));

	// Update shared items
	if ($sharedItems === null) {
		$sharedItems = $uniqueRucksackItems;
	} else {
		$sharedItems = array_values(
			array_intersect($sharedItems, $uniqueRucksackItems)
		);
	}

	// Wrapup if the group is complete
	$elfIndex = $index % 3;
	$elfGroup = intval($index / 3);
	if ($elfIndex === 2) {
		if (count($sharedItems) !== 1) {
			echo "elfGroup: ".$elfGroup."\n";
			throw new \Error('not 1 shared item: '.json_encode($sharedItems, JSON_THROW_ON_ERROR));
		}

		$sharedItem = $sharedItems[0];

		// Add score
		if (strtolower($sharedItem) === $sharedItem) {
			// Lowercase: a = 97, priority = 1
			$priority = ord($sharedItem) - 96;

		} else {
			// Uppercase: A = 65, priority = 27
			$priority = ord($sharedItem) - 38;
		}

		$prioritySum += $priority;

		// Reset for the next group
		$sharedItems = null;
	}
}

echo "Priority sum: ".$prioritySum."\n";
