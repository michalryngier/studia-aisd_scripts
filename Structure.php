<?php

class Structure
{
	private ?Structure $leftChild = null;
	private ?Structure $rightChild = null;
	private int $value = 0;

	/**
	 * Create new Structure with root value equals to given value.
	 * @param int $value
	 */
	public function __construct(int $value)
	{
		$this->value = $value;
	}

	/**
	 * Adds new value to the BST.
	 * @param int $value
	 * @param int $depth
	 * @return string
	 */
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

	/**
	 * Searches for given value and returns its' depth.
	 * @param int $v
	 * @return string
	 */
	public function search(int $v) : string
	{
		return $this->_search($v, 0);
	}

	private function _search(int $v, ?int $depth = 0) : string
	{
		var_dump("Value: {$this->value}" );
		if ($v < $this->value) {
			if ($this->leftChild === null) {
				return "Value {$v} cannot be found. Ended at: {$depth}";
			}
			$depth += 1;
			return $this->leftChild->_search($v, $depth);
		} else if ($v > $this->value) {
			if ($this->rightChild === null) {
				return "Value {$v} cannot be found. Ended at: {$depth}";
			}
			$depth += 1;
			return $this->rightChild->_search($v, $depth);
		} else {
			return "Found {$v} at {$depth}";
		}
	}

	/**
	 * Counts how many nodes the Structure has.
	 * @return int
	 */
	public function nodeCount() : int
	{
		return $this->_nodeCount($this);
	}

	private function _nodeCount(?Structure $structure, ?int $count = 0) : int
	{
		if (is_null($structure)) {
			return $count;
		}
		$count += 1;
		$count = $structure->_nodeCount($structure->leftChild, $count);
		$count = $structure->_nodeCount($structure->rightChild, $count);
		return $count;
	}

	/**
	 * Counts leaves the Structure has.
	 * @return int
	 */
	public function leavesCount() : int
	{
		return $this->_leavesCount($this, 0);
	}

	private function _leavesCount(?Structure $structure, $count = 0) : int
	{
		if (is_null($structure)) {
			return $count;
		}
		if (is_null($structure->rightChild) && is_null($structure->leftChild)) {
			return $count + 1;
		}
		$count = $structure->_leavesCount($structure->leftChild, $count);
		$count = $structure->_leavesCount($structure->rightChild, $count);
		return $count;
	}

	/**
	 * Counts all the right-side children of the Structure.
	 * @return int
	 */
	public function rightChildrenCount() : int
	{
		return $this->_rightChildrenCount($this, 0);
	}

	private function _rightChildrenCount(?Structure $structure, int $count = 0) : int
	{
		if (is_null($structure)) {
			return $count - 1;
		}
		if ($count === 0) {
			$count = $structure->_rightChildrenCount($structure->rightChild, $count + 1);
		} else {
			$count = $structure->_rightChildrenCount($structure->rightChild, $count + 1);
			$count = $structure->_rightChildrenCount($structure->leftChild, $count + 1);
		}
		return $count;
	}

	/**
	 * Gets the maximum height of the Structure.
	 * @return int
	 */
	public function treeHeight() : int
	{
		return $this->_treeHeight($this, 0);
	}

	private function _treeHeight(?Structure $structure = null, int $height = 0) : int
	{
		if (is_null($structure)) {
			return $height - 1;
		}
		$leftHeight = $structure->_treeHeight($structure->leftChild, $height + 1);
		$rightHeight = $structure->_treeHeight($structure->rightChild, $height + 1);
		return $leftHeight > $rightHeight ? $leftHeight : $rightHeight;
	}

	/**
	 * Mirrors the Structure.
	 * @return Structure
	 */
	public function mirrorTree() : Structure
	{
		return $this->_mirrorTree($this);
	}

	private function _mirrorTree(?Structure $structure = null) : ?Structure
	{
		if (is_null($structure)) {
			return null;
		}
		$st = new Structure($structure->value);
		$st->leftChild = $this->_mirrorTree($structure->rightChild);
		$st->rightChild = $this->_mirrorTree($structure->leftChild);
		return $st;
	}

	/**
	 * Prints the Structure.
	 */
	public function printTree() : void
	{
		$this->_printTree($this);
	}

	private function _printTree(?Structure $structure, int $depth = 0, ?string $side = "") : void
	{
		if (is_null($structure)) {
			return;
		}
		$level = "{$side}";
		for ($i = 0; $i < $depth; $i++) {
			$level .= "..";
		}
		$level .= $structure->value;
		echo $level . PHP_EOL;
		$structure->_printTree($structure->leftChild, $depth + 1, "L");
		$structure->_printTree($structure->rightChild, $depth + 1, "R");
	}

	/**
	 * Reads the Structure by preorder.
	 * @return array
	 */
	public function readPreorder() : array
	{
		return $this->_readPreorder($this);
	}

	public function _readPreorder(Structure $structure = null, array $values = []) : array
	{
		if (is_null($structure)) {
			return $values;
		}
		$values = $this->visit($structure, $values);
		$values = $structure->_readPreorder($structure->leftChild, $values);
		$values = $structure->_readPreorder($structure->rightChild, $values);
		return $values;
	}

	/**
	 * Reads the Structure by inorder.
	 * @return array
	 */
	public function readInorder() : array
	{
		return $this->_readInorder($this);
	}

	private function _readInorder(Structure $structure = null, array $values = []) : array
	{
		if (is_null($structure)) {
			return $values;
		}
		$values = $structure->_readInorder($structure->leftChild, $values);
		$values = $this->visit($structure, $values);
		$values = $structure->_readInorder($structure->rightChild, $values);
		return $values;
	}

	/**
	 * Reads the Structure by postorder.
	 * @return array
	 */
	private function readPostorder() : array
	{
		return $this->_readPostorder($this);
	}

	private function _readPostorder(Structure $structure = null, array $values = []) : array
	{
		if (is_null($structure)) {
			return $values;
		}
		$values = $structure->_readPostorder($structure->leftChild, $values);
		$values = $structure->_readPostorder($structure->rightChild, $values);
		$values = $this->visit($structure, $values);
		return $values;
	}

	private function visit(Structure $structure = null, array $values = []) : array
	{
		if (is_null($structure->value) === false) {
			$values[] = $structure->value;
		}
//		if (is_null($structure->leftChild) === false && $structure->value - $structure->leftChild->value < 2) {
//			$structure->leftChild->value += 2;
//		}
		return $values;
	}

	/**
	 * @param int $var
	 */
	public function exam(int $var)
	{
		$this->_exam($this, $var);
	}

	private function _exam(Structure $structure, int $var) : void
	{
		while (is_null($structure) === false) {
			if (is_null($structure->leftChild) === false) {
				$structure->value = $structure->leftChild->value + $var;
				$structure->_exam($structure->leftChild, $var + 1);
			}
			$structure = $structure->rightChild;
		}
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
	12,
	null,
	null,
	null,
//	[9,7,2,18,56,25,60,21,39,29,55,49,66,94,77,97]
//	[5,20,4,6,0,7,9,15,30,13]
	[7,9,5,6,17,16,15,22]
);

//var_dump($st->readPreorder());
//var_dump($st->readInorder());
//var_dump($st->readPostorder());
//var_dump("Structure: ", print_r($st));
//var_dump("Nodes: " . $st->nodeCount());
//var_dump("Leaves: " . $st->leavesCount());
//var_dump("Right children: " . $st->rightChildrenCount());
//var_dump("Height: " . $st->treeHeight());

//$st->printTree();

//print_r($st->readPreorder(363));

//$st->printTree();
//$st->mirrorTree()->printTree();

$st->exam(4);
print_r($st->readInorder());
