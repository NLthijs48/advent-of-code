<?php

namespace day07\part2;

$input = file_get_contents('input.txt');
$commandLines = explode("\n", $input);

const TOTAL_SPACE = 70000000;
const SPACE_REQUIRED = 30000000;

abstract class Item {
	public string $name;
	public function __construct(string $name) {
		$this->name = $name;
	}

	public abstract function getSize(): int;
}

class File extends Item {
	public int $size;

	public function __construct(string $name, int $size) {
		parent::__construct($name);
		$this->size = $size;
	}

	public function getSize(): int {
		return $this->size;
	}
}

class Directory extends Item {
	public ?Directory $parent;
	/** @var array<string, Item> */
	public array $contents = [];

	public function __construct(string $name, ?Directory $parent=null) {
		parent::__construct($name);
		$this->parent = $parent;
	}

	public function getSize(): int {
		$size = 0;
		foreach ($this->contents as $content) {
			$size += $content->getSize();
		}
		return $size;
	}
}


$root = new Directory('/');
$current = $root; // Start at the root

$lsMode = false;
for ($index = 0; $index < count($commandLines); $index++) {
	$commandLine = $commandLines[$index];
	if ($commandLine === '') {
		continue;
	}

	$words = explode(' ', $commandLine);
	if ($words[0] === '$') {
		$lsMode = false;
		$command = $words[1];
		if ($command === 'cd') {
			$path = $words[2];
			if ($path === '/') {
				// Switch to the root
				$current = $root;
			} else if ($path === '..') {
				// Go a directory up
				assert($current->parent !== null, 'cd ..  | There is a parent');
				$current = $current->parent;
			} else {
				// Go into directory
				$current = $current->contents[$path];
			}

		} else if ($command === 'ls') {
			$lsMode = true;
		}
	} else {
		assert($lsMode, 'should be in ls mode');
		if ($words[0] === 'dir') {
			// Found directory
			$directoryName = $words[1];
			$item = new Directory($directoryName, $current);
		} else {
			// Found file
			$fileSize = $words[0];
			assert(is_numeric($fileSize), 'fileSize numeric');
			$fileName = $words[1];
			$item = new File($fileName, $fileSize);
		}

		assert(!array_key_exists($item->name, $current->contents), 'directory/file already exists');
		$current->contents[$item->name] = $item;
	}
}

$usedSpace = $root->getSize();
echo "Used space: ". $usedSpace ."\n";
$freeSpace = TOTAL_SPACE - $usedSpace;
echo "Free space: ". $freeSpace ."\n";
$toDelete = SPACE_REQUIRED - $freeSpace;
echo "To delete: ". $toDelete ."\n";
assert($toDelete>0, 'to delete above zero');

/** @var Directory[] $allDirectories */
$allDirectories = [];
function recurse(Directory $directory, array &$allDirectories) {
	$allDirectories[] = $directory;
	foreach ($directory->contents as $content) {
		if (!($content instanceof Directory)) {
			continue;
		}

		recurse($content, $allDirectories);
	}
}
recurse($root, $allDirectories);

// Sort small to big
usort($allDirectories, function (Directory $a, Directory $b) {
	return $a->getSize() - $b->getSize();
});

foreach ($allDirectories as $directory) {
	if ($directory->getSize() < $toDelete) {
		continue;
	}

	// Found it
	echo "Directory to delete: ".$directory->name."\n";
	echo "Size: ".$directory->getSize()."\n";
	break;
}
