<?php


/**
 * Checks if given array of search stack values can be proceeded when searching for given value
 * @param array $searchStack
 * @param int $value
 * @return bool
 */
function checkIfSearchIsValid(array $searchStack, int $value) : bool
{
	$stackLen = count($searchStack);
	if ($stackLen === 0) {
		return true;
	}
	$root = $searchStack[0];
	$newStack = [];
	if ($root > $value) {
		for ($i = 1; $i < $stackLen - 1; $i++) {
			if ($searchStack[$i] > $root) {
				echo "Błąd wartości: {$searchStack[$i]}, root: $root";
				return false;
			}
			$newStack[] = $searchStack[$i];
		}
	} else if ($root < $value) {
		for ($i = 1; $i < $stackLen - 1; $i++) {
			if ($searchStack[$i] < $root) {
				echo "Błąd wartości: {$searchStack[$i]}, root: $root";
				return false;
			}
			$newStack[] = $searchStack[$i];
		}
	} else {
		return $stackLen === 0;
	}
	return checkIfSearchIsValid($newStack, $value);
}

$check = checkIfSearchIsValid(
	[1000, 123, 122 , 98, 992, 32, 15, 200],
	100
);

echo PHP_EOL . "Sprawdzenie: " . ($check ? 'możliwe' : 'błędne') . PHP_EOL;