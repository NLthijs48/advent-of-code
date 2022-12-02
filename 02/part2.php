<?php

namespace day01\part2;

$strategyInput = file_get_contents('input.txt');

$strategyLines = explode("\n", $strategyInput);

enum Choice: string {
	case Rock = 'A';
	case Paper = 'B';
	case Scissers = 'C';

	public function pointsForUsingIt(): int {
		return match ($this) {
			self::Rock => 1,
			self::Paper => 2,
			self::Scissers => 3,
		};
	}

	public function playAgainst(Choice $opponent): PlayResult {
		if ($this === $opponent) {
			return PlayResult::Draw;
		}
		if ($this === self::Rock && $opponent === self::Scissers) {
			return PlayResult::Win;
		}
		if ($this === self::Paper && $opponent === self::Rock) {
			return PlayResult::Win;
		}
		if ($this === self::Scissers && $opponent === self::Paper) {
			return PlayResult::Win;
		}

		return PlayResult::Lose;
	}
}

enum PlayResult: string {
	case Win = 'Z';
	case Lose = 'X';
	case Draw = 'Y';

	public function points(): int {
		return match ($this) {
			self::Win => 6,
			self::Draw => 3,
			self::Lose => 0,
		};
	}
}


$totalScore = 0;
foreach ($strategyLines as $strategyLine) {
	if ($strategyLine === '') {
		continue;
	}
	$strategyParts = explode(' ', $strategyLine);
	assert(count($strategyParts) === 2);

	$opponent = Choice::from($strategyParts[0]);
	$desiredResult = PlayResult::from($strategyParts[1]);
	$self = null;
	foreach (Choice::cases() as $selfChoice) {
		if ($selfChoice->playAgainst($opponent) === $desiredResult) {
			$self = $selfChoice;
			break;
		}
	}
	assert($self !== null);

	$totalScore += $self->pointsForUsingIt();
	$totalScore += $desiredResult->points();
}

echo "Total score: ".$totalScore."\n";
