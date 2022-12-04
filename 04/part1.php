<?php

namespace day04\part1;

$input = file_get_contents('input.txt');

$elfPairLines = explode("\n", $input);

class Range {

	public int $start;
	public int $end;

	public function __construct(string $rangeInput) {
		$rangeParts = explode('-', $rangeInput);;
		assert(count($rangeParts) === 2);
		assert(is_numeric($rangeParts[0]));
		assert(is_numeric($rangeParts[1]));

		$this->start = intval($rangeParts[0]);
		$this->end = intval($rangeParts[1]);

		assert($this->start <= $this->end);
	}

	public function fullyOverlaps(Range $other): bool {
		return $this->isContainedIn($other) || $other->isContainedIn($this);
	}

	public function isContainedIn(Range $other): bool {
		 return $this->start >= $other->start && $this->end <= $other->end;
	}
}

$pairsContained = 0;
foreach ($elfPairLines as $elfPairLine) {
	if ($elfPairLine === '') {
		continue;
	}

	$elves = explode(',', $elfPairLine);
	if (count($elves) !== 2) {
		throw new \Error('elf count of ' . count($elves));
	}

	$rangeOne = new Range($elves[0]);
	$rangeTwo = new Range($elves[1]);

	if ($rangeOne->fullyOverlaps($rangeTwo)) {
		$pairsContained++;
	}
}

echo "Pairs fully contained: ".$pairsContained."\n";
