<?php

namespace day13\part1;

$input = file_get_contents('input.txt');
$pairGroups = explode("\n\n", $input);

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

$indicesSum = 0;
foreach ($pairGroups as $pairIndex => $pairGroup) {
	$packetLines = explode("\n", $pairGroup);

	$originalPackets = [];
	foreach ($packetLines as $packetLine) {
		if ($packetLine === '') {
			continue;
		}
		$originalPackets[] = json_decode($packetLine, true, 512, JSON_THROW_ON_ERROR);
	}
	assert(count($originalPackets) === 2, '2 packets in a group');

	$sortedPackets = $originalPackets;
	usort($sortedPackets, sortPackets(...));

	echo "Pair ".($pairIndex+1)."\n";
	foreach ($sortedPackets as $packetIndex => $sortedPacket) {
		echo "    Packet ".($packetIndex + 1).": ". json_encode($sortedPacket, JSON_THROW_ON_ERROR)."\n";
	}

	$inOrder = $sortedPackets[0] === $originalPackets[0];
	echo "    -> ".($inOrder ? 'in order' : 'out of order')."\n";
	if ($inOrder) {
		$indicesSum += ($pairIndex + 1);
	}
}

echo "Indices sum: ".$indicesSum."\n";

