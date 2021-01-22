<?php

//  TODO: write all algorithms from classes and lectures


function bubbleSort(array $array) : array
{
	// TODO: write bubble sort algorithm
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