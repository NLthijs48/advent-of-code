<?php

namespace day08\part2;

$input = file_get_contents('input.txt');
$treeLines = explode("\n", $input);

$treeHeightMap = [];
foreach ($treeLines as $treeLine) {
	if ($treeLine === '') {
		continue;
	}

	$treeHeights = str_split($treeLine, 1);
	foreach ($treeHeights as &$treeHeight) {
		assert(is_numeric($treeHeight), 'tree height is numeric');
		$treeHeight = intval($treeHeight);
	}
	unset($treeHeight);

	$treeHeightMap[] = $treeHeights;
}

$lineCount = count($treeHeightMap);
echo "Lines: ". $lineCount ."\n";
$columnCount = count($treeHeightMap[0]);
echo "Columns: ". $columnCount ."\n";

// As <width>-<height> strings
$visibleFromOutside = [];

function getVisibleInDirection(array $heightMap, int $startLine, int $startColumn, int $lineDiff, int $columnDiff): int {
	$startTreeHeight = $heightMap[$startLine][$startColumn];

	$visibleTrees = 0;
	$blockedHeight = 0;
	while (true) {
		// Go to next tree
		$startLine += $lineDiff;
		$startColumn += $columnDiff;

		// Outside of the grid
		if (!isset($heightMap[$startLine][$startColumn])) {
			break;
		}

		$currentTreeHeight = $heightMap[$startLine][$startColumn];

		// Example did not specify if this should only be done if $currentTreeHeight >= $blockedHeight, but apparently that is not a condition
		$visibleTrees++;

		if ($currentTreeHeight >= $startTreeHeight) {
			// Sight blocked, not more visible trees to be found
			break;
		}

		// There might be more, but our view is blocked a bit more now
		$blockedHeight = max($blockedHeight, $currentTreeHeight);
	}

	return $visibleTrees;
}

$highestScenicScore = 0;
$bestLocation = null;
for ($line = 0; $line < $lineCount; $line++) {
	for ($column = 0; $column < $columnCount; $column++) {
		$scenicScore =
			// Up
			getVisibleInDirection($treeHeightMap, $line, $column, -1, 0)
			// Right
			* getVisibleInDirection($treeHeightMap, $line, $column, 0, 1)
			// Down
			* getVisibleInDirection($treeHeightMap, $line, $column, 1, 0)
			// Left
			* getVisibleInDirection($treeHeightMap, $line, $column, 0, -1);

		if ($scenicScore > $highestScenicScore) {
			$highestScenicScore = $scenicScore;
			$bestLocation = "{$line}-{$column}";
		}
	}
}

// 1149984 too high
// 383520
// 2430 too low
echo "Highest scenic score: ".$highestScenicScore."\n";
echo "Best location: ".$bestLocation."\n";