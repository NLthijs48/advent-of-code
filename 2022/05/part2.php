<?php

namespace day05\part2;

$input = file_get_contents('input.txt');

$inputSections = explode("\n\n", $input);
assert(count($inputSections) === 2);

[$stacksInput, $moveLines] = $inputSections;

// Parse the input stacks
$stackLines = explode("\n", $stacksInput);
assert(count($stackLines) >= 1);

$crateStackLines = array_slice($stackLines, 0, -1);
$stackNumberLine = $stackLines[count($stackLines) - 1];
$stackCount = intval(round(strlen($stackNumberLine) / 4));
echo "Stack count: ". $stackCount."\n";

$crateStackLines = array_reverse($crateStackLines);
/** @var array<int, string[]> $crateStacks */
$crateStacks = [];
foreach ($crateStackLines as $crateStackLine) {
	for ($crateStackIndex = 0; $crateStackIndex < $stackCount; $crateStackIndex++) {
		$crateStacks[$crateStackIndex] ??= [];

		$lineIndex = $crateStackIndex * 4 + 1;
		$character = $crateStackLine[$lineIndex];
		if ($character === ' ') {
			// Nothing on this position in the stack
			continue;
		}

		if ('A' <= $character && $character <= 'Z') {
			// Found crate, add to stack
			$crateStacks[$crateStackIndex][] = $character;
			continue;
		}

		throw new \Error('found weird char: '.$character);
	}
}

// Parse and execute the moves
foreach (explode("\n", $moveLines) as $moveLine) {
	if ($moveLine === '') {
		continue;
	}

	$matches = [];
	assert(preg_match("/^move (?<count>\d+) from (?<from>\d+) to (?<to>\d+)$/", $moveLine, $matches) !== false, 'preg_match move line');

	$count = intval($matches['count']);
	$from = intval($matches['from']);
	$from--;
	$to = intval($matches['to']);
	$to--;

	$fromStack = $crateStacks[$from];
	$moveCrates = array_slice($fromStack, -$count);
	$crateStacks[$from] = array_slice($fromStack, 0, -$count);

	$crateStacks[$to] = [...$crateStacks[$to], ...$moveCrates];
}

//var_dump($crateStacks);

foreach ($crateStacks as $crateStack) {
	echo $crateStack[count($crateStack) - 1];
}
