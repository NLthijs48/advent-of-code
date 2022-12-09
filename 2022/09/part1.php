<?php

namespace day09\part1;

$input = file_get_contents('input.txt');
$moveLines = explode("\n", $input);

enum Direction: string {
	case Up = 'U';
	case Right = 'R';
	case Down = 'D';
	case Left = 'L';
}

class Knot {
	public int $x = 0;
	public int $y = 0;

	public function move(Direction $direction): void {
		if ($direction === Direction::Up) {
			$this->y++;
		} else if ($direction === Direction::Right) {
			$this->x++;
		} else if ($direction === Direction::Down) {
			$this->y--;
		} else if ($direction === Direction::Left) {
			$this->x--;
		}
	}

	public function follow(Knot $target): void {
		// Overlapping already, no need to move
		if ($this->x === $target->x && $this->y === $target->y) {
			return;
		}

		$xDiff = abs($this->x - $target->x);
		$yDiff = abs($this->y - $target->y);
		if ($xDiff >= 2 || $yDiff >= 2) {
			if ($xDiff >= 1) {
				// Don't move if already next to it
				$this->x += $this->x > $target->x ? -1 : 1;
			}
			if ($yDiff >= 1) {
				// Don't move if already next to it
				$this->y += $this->y > $target->y ? -1 : 1;
			}
		}
	}

	public function __toString(): string {
		return "({$this->x},{$this->y})";
	}
}

$head = new Knot();
$tail = new Knot();

$tailVisited = [];

foreach ($moveLines as $moveLine) {
	if ($moveLine === '') {
		continue;
	}

	echo "Move: ".$moveLine."\n";
	$parts = explode(' ', $moveLine);
	assert(count($parts) === 2, '2 parts in a move line');

	$direction = Direction::from($parts[0]);
	assert(is_numeric($parts[1]), 'move count is numeric');
	$stepCount = intval($parts[1]);

	for ($step = 0; $step < $stepCount; $step++) {
		echo "    Step ".$step."\n";
		$head->move($direction);
		$tail->follow($head);

		$tailVisited[(string)$tail] = true;
		echo "        Head: ".$head."\n";
		echo "        Tail: ".$tail."\n";
	}
}

echo "Tail visited: ".count($tailVisited)."\n";
