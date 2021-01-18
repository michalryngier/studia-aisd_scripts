<?php

class Structure
{
	private $leftChild = null;
	private $rightChild = null;
	private $value = 0;

	public function __construct(int $value)
	{
		$this->value = $value;
	}

	public function addValue(int $value, int $depth = 0) : string
	{
		if ($value > $this->value) {
			$depth += 1;
			if ($this->rightChild === null) {
				$this->rightChild = new Structure($value);
			} else {
				return$this->rightChild->addValue($value, $depth);
			}
			return "Value: {$value} added to right at {$depth}";

		} else if ($value < $this->value) {
			$depth += 1;
			if ($this->leftChild === null) {
				$this->leftChild = new Structure($value);
			} else {
				return $this->leftChild->addValue($value, $depth);
			}
			return "Value: {$value} added to left at {$depth}";
		} else {
			return "Value: {$value} already exists at {$depth}";
		}
	}

	public function search(int $v, int $depth = 0) : string
	{
		if ($v < $this->value) {
			if ($this->leftChild === null) {
				return "Value {$v} cannot be found. Ended at: {$depth}";
			}
			$depth += 1;
			return $this->leftChild->search($v, $depth);
		} else if ($v > $this->value) {
			if ($this->rightChild === null) {
				return "Value {$v} cannot be found. Ended at: {$depth}";
			}
			$depth += 1;
			return $this->rightChild->search($v, $depth);
		} else {
			return "Found {$v} at {$depth}";
		}
	}

	public function nodeCount(?Structure $structure, $count = 0) : int
	{
		if (is_null($structure)) {
			return $count;
		}
		$count += 1;
		$count = $structure->nodeCount($structure->leftChild, $count);
		$count = $structure->nodeCount($structure->rightChild, $count);
		return $count;
	}

	public function leavesCount(?Structure $structure, $count = 0) : int
	{
		if (is_null($structure)) {
			return $count;
		}
		if (is_null($structure->rightChild) && is_null($structure->leftChild)) {
			return $count + 1;
		}
		$count = $structure->leavesCount($structure->leftChild, $count);
		$count = $structure->leavesCount($structure->rightChild, $count);
		return $count;
	}

	public function rightChildrenCount(?Structure $structure, int $count = 0) : int
	{
		if (is_null($structure)) {
			return $count - 1;
		}
		if ($count === 0) {
			$count = $structure->rightChildrenCount($structure->rightChild, $count + 1);
		} else {
			$count = $structure->rightChildrenCount($structure->rightChild, $count + 1);
			$count = $structure->rightChildrenCount($structure->leftChild, $count + 1);
		}
		return $count;
	}

	public function treeHeight(?Structure $structure = null, int $height = 0) : int
	{
		if (is_null($structure)) {
			return $height - 1;
		}
		$leftHeight = $structure->treeHeight($structure->leftChild, $height + 1);
		$rightHeight = $structure->treeHeight($structure->rightChild, $height + 1);
		return $leftHeight > $rightHeight ? $leftHeight : $rightHeight;
	}

	public function readPreorder(Structure $structure = null, array $values = []) : ?array
	{
		if (is_null($structure)) {
			return $values;
		}
		$values = $this->visit($structure, $values);
		$values = $structure->readPreorder($structure->leftChild, $values);
		$values = $structure->readPreorder($structure->rightChild, $values);
		return $values;
	}

	public function readInorder(Structure $structure = null, array $values = [])
	{
		if (is_null($structure)) {
			return $values;
		}
		$values = $structure->readPreorder($structure->leftChild, $values);
		$values = $this->visit($structure, $values);
		$values = $structure->readPreorder($structure->rightChild, $values);
		return $values;
	}

	public function readPostorder(Structure $structure = null, array $values = [])
	{
		if (is_null($structure)) {
			return $values;
		}
		$values = $structure->readPreorder($structure->leftChild, $values);
		$values = $structure->readPreorder($structure->rightChild, $values);
		$values = $this->visit($structure, $values);
		return $values;
	}

	private function visit(Structure $structure = null, array $values = []) : array
	{
		if (is_null($structure->value) === false) {
			$values[] = $structure->value;
		}
		return $values;
	}
}

function createBinaryTree(
	?int $rootValue = 10,
	?int $numOfValues = 10,
	?int $minValue = 0,
	?int $maxValue = 100,
	?array $values = null
) : Structure
{
	$st = new Structure($rootValue);
	if (is_null($values)) {
		for ($i = 0; $i < $numOfValues; $i++) {
			$rand = rand($minValue, $maxValue);
			$st->addValue($rand);
		}
	} else {
		foreach ($values as $val) {
			$st->addValue($val);
		}
	}
	return $st;
}

$st = createBinaryTree(
	10,
	null,
	null,
	null,
	[9,7,2,18,56,25,60,21,39,29,55,49,66,94,77,97]
);

//var_dump($st->readPreorder($st));
//var_dump($st->readInorder($st));
//var_dump($st->readPostorder($st));
//var_dump("Structure: ", print_r($st));
var_dump("Nodes: " . $st->nodeCount($st));
var_dump("Leaves: " . $st->leavesCount($st));
var_dump("Right children: " . $st->rightChildrenCount($st));
var_dump("Height: " . $st->treeHeight($st));



