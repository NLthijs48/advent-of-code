<?php

namespace day13\part2;

$input = file_get_contents('input.txt');
$packetLines = explode("\n", $input);

function sortPackets(int|array $a, int|array $b): int {
	if (is_int($a) && is_int($b)) {
		// A lower should be first
		return $a <=> $b;
	}

	// Convert remaining integers to lists
	if (is_int($a)) {
		$a = [$a];
	}
	if (is_int($b)) {
		$b = [$b];
	}

	// Sort lists
	$aCount = count($a);
	$bCount = count($b);
	$maxCount = max($aCount, $bCount);
	for ($index=0; $index<$maxCount; $index++) {
		// No items left in $a, is first
		if ($index >= $aCount) {
			return -1;
		}

		// No items left in $b, is first
		if ($index >= $bCount) {
			return 1;
		}

		// Continue if the same, otherwise try next item
		$listItemCompare = sortPackets($a[$index], $b[$index]);
		if ($listItemCompare !== 0) {
			return $listItemCompare;
		}
	}

	// No tiebreaker, same
	return 0;
}


$originalPackets = [];
foreach ($packetLines as $packetLine) {
	if ($packetLine === '') {
		continue;
	}
	$originalPackets[] = json_decode($packetLine, true, 512, JSON_THROW_ON_ERROR);
}

// Additional divider packets
$dividerPackets = [
	[[2]],
	[[6]],
];
$originalPackets = [...$originalPackets, ...$dividerPackets];

// Sort all of them
$sortedPackets = $originalPackets;
usort($sortedPackets, sortPackets(...));

// Find the divider packets
$dividerPacketsMultiplication = 1;
foreach ($dividerPackets as $dividerPacket) {
	foreach ($sortedPackets as $packetIndex => $sortedPacket) {
		if ($sortedPacket !== $dividerPacket) {
			continue;
		}

		$foundIndex = $packetIndex + 1;
		echo "Divider packet ". json_encode($dividerPacket, JSON_THROW_ON_ERROR) ." has index ". $foundIndex."\n";
		$dividerPacketsMultiplication *= $foundIndex;
	}
}

echo "Multiplied: ".$dividerPacketsMultiplication."\n";

