<?php

namespace day03\part1;

$input = file_get_contents('input.txt');

$rucksackLines = explode("\n", $input);

$prioritySum = 0;
foreach ($rucksackLines as $rucksackLine) {
	if ($rucksackLine === '') {
		continue;
	}

	$rucksackItems = str_split($rucksackLine, 1);
	$rucksackItemCount = count($rucksackItems);
	assert($rucksackItemCount % 2 === 0, 'rucksack must have even number of items');

	$firstCompartmentItems = array_slice($rucksackItems, 0, $rucksackItemCount/2);
	$secondCompartmentItems = array_slice($rucksackItems, $rucksackItemCount/2);

	$sharedItem = null;
	foreach ($firstCompartmentItems as $firstCompartmentItem) {
		if (!in_array($firstCompartmentItem, $secondCompartmentItems, true)) {
			continue;
		}

		if ($firstCompartmentItem === $sharedItem) {
			// Same item encountered another time
			continue;
		}

		if ($sharedItem !== null) {
			echo $rucksackLine . "\n";
			echo $sharedItem . "\n";
			echo $firstCompartmentItem . "\n";
			throw new \Error('cannot have 2 shared items');
		}
		$sharedItem = $firstCompartmentItem;
	}
	assert($sharedItem !== null, 'should have a shared item');

	if (strtolower($sharedItem) === $sharedItem) {
		// Lowercase: a = 97, priority = 1
		$priority = ord($sharedItem) - 96;

	} else {
		// Uppercase: A = 65, priority = 27
		$priority = ord($sharedItem) - 38;
	}

	$prioritySum += $priority;
}

echo "Priority sum: ".$prioritySum."\n";
