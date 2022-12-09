<?php

namespace day04\part2;

$input = file_get_contents('input.txt');

$elfPairLines = explode("\n", $input);

class Range {

	public int $start;
	public int $end;

	public function __construct(string $rangeInput) {
		$rangeParts = explode('-', $rangeInput);
		assert(count($rangeParts) === 2);
		assert(is_numeric($rangeParts[0]));
		assert(is_numeric($rangeParts[1]));

		$this->start = intval($rangeParts[0]);
		$this->end = intval($rangeParts[1]);

		assert($this->start <= $this->end);
	}

	public function overlaps(Range $other): bool {
		return ($this->start <= $other->end && $this->start >= $other->start)
			|| ($this->end <= $other->end && $this->end >= $other->start)
			|| ($other->start >= $this->start && $other->start <= $this->end)
			|| ($other->end >= $this->start && $other->end <= $this->end);
	}
}

$pairsOverlapping = 0;
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

	if ($rangeOne->overlaps($rangeTwo)) {
		$pairsOverlapping++;
	}
}

echo "Pairs overlapping: ".$pairsOverlapping."\n";
