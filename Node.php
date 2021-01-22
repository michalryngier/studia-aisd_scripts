<?php

/**
 * THIS CLASS REPRESENTS BINARY SEARCH TREE
 */

class Node
{
	public ?Node $leftChild = null;
	public ?Node $rightChild = null;
	public ?int $value = null;

	/**
	 * Create new Node with root value equals to given value.
	 * @param int $value
	 */
	public function __construct(?int $value = null)
	{
		$this->value = $value;
	}

	/**
	 * Adds new value to the Node.
	 * @param int $value
	 * @param int $depth
	 * @return string
	 */
	public function addValue(int $value, int $depth = 0) : string
	{
		if (is_null($this->value)) {
			$this->value = $value;
			return "Value: {$value} added to root at {$depth}";
		}
		if ($value > $this->value) {
			$depth += 1;
			if ($this->rightChild === null) {
				$this->rightChild = new Node($value);
			} else {
				return$this->rightChild->addValue($value, $depth);
			}
			return "Value: {$value} added to right at {$depth}";

		} else if ($value < $this->value) {
			$depth += 1;
			if ($this->leftChild === null) {
				$this->leftChild = new Node($value);
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
	public function search(int $v, ?int $depth = 0) : string
	{
		var_dump("Value: {$this->value}" );
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

	/**
	 * Counts how many nodes the Node has.
	 * @return int
	 */
	public function nodeCount() : int
	{
		return $this->_nodeCount($this);
	}

	private function _nodeCount(?Node $node, ?int $count = 0) : int
	{
		if (is_null($node)) {
			return $count;
		}
		$count += 1;
		$count = $node->_nodeCount($node->leftChild, $count);
		$count = $node->_nodeCount($node->rightChild, $count);
		return $count;
	}

	/**
	 * Counts leaves the Node has.
	 * @return int
	 */
	public function leavesCount() : int
	{
		return $this->_leavesCount($this, 0);
	}

	private function _leavesCount(?Node $node, $count = 0) : int
	{
		if (is_null($node)) {
			return $count;
		}
		if (is_null($node->rightChild) && is_null($node->leftChild)) {
			return $count + 1;
		}
		$count = $node->_leavesCount($node->leftChild, $count);
		$count = $node->_leavesCount($node->rightChild, $count);
		return $count;
	}

	/**
	 * Counts all the right-side children of the Node.
	 * @return int
	 */
	public function rightChildrenCount() : int
	{
		return $this->_rightChildrenCount($this, 0);
	}

	private function _rightChildrenCount(?Node $node, int $count = 0) : int
	{
		if (is_null($node)) {
			return $count - 1;
		}
		if ($count === 0) {
			$count = $node->_rightChildrenCount($node->rightChild, $count + 1);
		} else {
			$count = $node->_rightChildrenCount($node->rightChild, $count + 1);
			$count = $node->_rightChildrenCount($node->leftChild, $count + 1);
		}
		return $count;
	}

	/**
	 * Gets the maximum height of the Node.
	 * @return int
	 */
	public function treeHeight() : int
	{
		return $this->_treeHeight($this, 0);
	}

	private function _treeHeight(?Node $node = null, int $height = 0) : int
	{
		if (is_null($node)) {
			return $height - 1;
		}
		$leftHeight = $node->_treeHeight($node->leftChild, $height + 1);
		$rightHeight = $node->_treeHeight($node->rightChild, $height + 1);
		return $leftHeight > $rightHeight ? $leftHeight : $rightHeight;
	}

	/**
	 * Mirrors the Node.
	 * @return Node
	 */
	public function mirrorTree() : Node
	{
		return $this->_mirrorTree($this);
	}

	private function _mirrorTree(?Node $node = null) : ?Node
	{
		if (is_null($node)) {
			return null;
		}
		$nd = new Node($node->value);
		$nd->leftChild = $this->_mirrorTree($node->rightChild);
		$nd->rightChild = $this->_mirrorTree($node->leftChild);
		return $nd;
	}

	/**
	 * Prints the Node.
	 */
	public function printTree() : void
	{
		$this->_printTree($this);
	}

	private function _printTree(?Node $node, int $depth = 0, ?string $side = "") : void
	{
		if (is_null($node)) {
			return;
		}
		$level = "{$side}";
		for ($i = 0; $i < $depth; $i++) {
			$level .= "..";
		}
		$level .= $node->value;
		echo $level . PHP_EOL;
		$node->_printTree($node->leftChild, $depth + 1, "L");
		$node->_printTree($node->rightChild, $depth + 1, "R");
	}

	/**
	 * Reads the Node by preorder.
	 * @return array
	 */
	public function readPreorder() : array
	{
		return $this->_readPreorder($this);
	}

	public function _readPreorder(Node $node = null, array $values = []) : array
	{
		if (is_null($node)) {
			return $values;
		}
		$values = $this->visit($node, $values);
		$values = $node->_readPreorder($node->leftChild, $values);
		$values = $node->_readPreorder($node->rightChild, $values);
		return $values;
	}

	/**
	 * Reads the Node by inorder.
	 * @return array
	 */
	public function readInorder() : array
	{
		return $this->_readInorder($this);
	}

	private function _readInorder(Node $node = null, array $values = []) : array
	{
		if (is_null($node)) {
			return $values;
		}
		$values = $node->_readInorder($node->leftChild, $values);
		$values = $this->visit($node, $values);
		$values = $node->_readInorder($node->rightChild, $values);
		return $values;
	}

	/**
	 * Reads the Node by postorder.
	 * @return array
	 */
	public function readPostorder() : array
	{
		return $this->_readPostorder($this);
	}

	private function _readPostorder(Node $node = null, array $values = []) : array
	{
		if (is_null($node)) {
			return $values;
		}
		$values = $node->_readPostorder($node->leftChild, $values);
		$values = $node->_readPostorder($node->rightChild, $values);
		$values = $this->visit($node, $values);
		return $values;
	}

	private function visit(Node $node = null, array $values = []) : array
	{
		if (is_null($node->value) === false) {
			$values[] = $node->value;
		}
		return $values;
	}
}

function createBinaryTree(
	?array $values = null,
	?int $numOfValues = 10,
	?int $minValue = 0,
	?int $maxValue = 100
) : Node
{
	$nd = new Node();
	if (is_null($values)) {
		for ($i = 0; $i < $numOfValues; $i++) {
			$rand = rand($minValue, $maxValue);
			$nd->addValue($rand);
		}
	} else {
		foreach ($values as $val) {
			$nd->addValue($val);
		}
	}
	return $nd;
}

$nd = createBinaryTree(
null,
	10,
	1,
	1000
);

echo PHP_EOL . "Tree: " . PHP_EOL;
$nd->printTree();

echo PHP_EOL . "Mirror tree: " . PHP_EOL;
$nd->mirrorTree()->printTree();

echo PHP_EOL . "Nodes: " . $nd->nodeCount() . PHP_EOL;

echo PHP_EOL . "Leaves: " . $nd->leavesCount() . PHP_EOL;

echo PHP_EOL . "Right children: " . $nd->rightChildrenCount() . PHP_EOL;

echo PHP_EOL . "Height: " . $nd->treeHeight() . PHP_EOL;

echo PHP_EOL . "Preorder: " . PHP_EOL;
print_r($nd->readPreorder());

echo PHP_EOL . "Inorder: " . PHP_EOL;
print_r($nd->readInorder());

echo PHP_EOL . "Postorder: " . PHP_EOL;
print_r($nd->readPostorder());


