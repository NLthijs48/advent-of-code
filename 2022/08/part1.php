<?php

namespace day08\part1;

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

function getVisibleInLine(array $heightMap, int $startLine, int $startColumn, int $lineDiff, int $columnDiff): array {
	$visibleInLine = [];

	$canSeeAbove = -1;
	while (true) {
		$currentTreeHeight = $heightMap[$startLine][$startColumn];
		if ($currentTreeHeight > $canSeeAbove) {
			$visibleInLine[] = "{$startLine}-{$startColumn}";
		}

		// TODO: this did not change anything, how?!
		$canSeeAbove = max($canSeeAbove, $currentTreeHeight);

		// Go to next location
		$startLine += $lineDiff;
		$startColumn += $columnDiff;
		if (!isset($heightMap[$startLine][$startColumn])) {
			// Went outside the grid
			break;
		}
	}

	return $visibleInLine;
}

for ($line = 0; $line < $lineCount; $line++) {
	$visibleFromOutside = [
		...$visibleFromOutside,
		// Left to right
		...getVisibleInLine($treeHeightMap, $line, 0, 0, 1),
		// Right to left
		...getVisibleInLine($treeHeightMap, $line, $columnCount -1, 0, -1),
	];
}
for ($column = 0; $column < $columnCount; $column++) {
	$visibleFromOutside = [
		...$visibleFromOutside,
		// Top to bottom
		...getVisibleInLine($treeHeightMap, 0, $column, 1, 0),
		// Bottom to top
		...getVisibleInLine($treeHeightMap, $lineCount - 1, $column, -1, 0),
	];
}

$visibleFromOutside = array_unique($visibleFromOutside);

// 1816
echo "Visible from outside: ".count($visibleFromOutside)."\n";