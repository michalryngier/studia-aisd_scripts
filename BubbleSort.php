<?php

function bubbleSort(array $array) : array
{

}

function printIteration(int $iteration, int $element, array $array) : void
{
	$stringArray = "";
	for ($i = 0; $i < $countElements = count($array); $i++) {
		if ($i < $countElements - 1) {
			$stringArray .= (string) $array[$i] . ", ";
		} else {
			$stringArray .= (string) $array[$i];
		}
	}
	echo "Iteracja: {$iteration}, element: {$element}, tablica: {$stringArray}";
}