<?php


/**
 * Class TwoWayList
 */
class TwoWayList
{
	public ?int $data = null;
	public ?TwoWayList $next = null;
	public ?TwoWayList $prev = null;

	public function __construct(?int $data = null)
	{
		$this->data = $data;
	}
}

// Powinny być dwa pliki, ale nie chcę robić bałaganu w czymś tak małym.

/**
 * Class TwoWayListHelper
 */
class TwoWayListHelper
{
	public ?TwoWayList $head = null;

	public function __construct(?TwoWayList $head)
	{
		$this->setHead($head);
	}

	/**
	 * Set list head/
	 * @param TwoWayList|null $head
	 */
	public function setHead(?TwoWayList $head)
	{
		$this->head = $head;
	}

	/**
	 * Inserts element at given index.
	 * @param int $value
	 * @param int|null $index
	 * @return bool
	 */
	public function insert(int $value, ?int $index = null) : bool
	{
		if ($this->isCyclic()) {
			return $this->_insertIntoCyclic($value, $index, $this->head);
		}
		return $this->_insert($value, $index, $this->head);
	}

	private function _insert(int $value, ?int $index, ?TwoWayList &$head) : bool
	{
		if ($index === 0) {
			$newList = new TwoWayList($value);
			if (is_null($head) === false) {
				$newList->next = $head;
				$head->prev = $newList;
			}
			$head = $newList;
			return true;
		}
		if (is_null($head->next) && (is_null($index) || $index === 1)) {
			$newList = new TwoWayList($value);
			$newList->prev = $head;
			$head->next = $newList;
			return true;
		}
		if ($index === 1 && is_null($head->next) === false) {
			$newList = new TwoWayList($value);
			$newList->prev = $head;
			$newList->next = $head->next->next;
			$head->next = $newList;
			return true;
		}
		return $this->_insert($value, $index - 1, $head->next);
	}

	private function _insertIntoCyclic(int $value, ?int $index, ?TwoWayList &$head) : bool
	{
		if ((is_null($index) || $index === 1) && is_null($head->next->prev)) {
			$newList = new TwoWayList($value);
			$newList->next = $head->next;
			$newList->prev = $head;
			$head->next = $newList;
			return true;
		}
		if ($index === 0) {
			$lastElement = $this->_getCyclicLast($head);
			$newList = new TwoWayList($value);
			$newList->next = $head;
			$lastElement->next = $newList;
			$head = $newList;
			return true;
		}
		if ($index === 1) {
			$newList = new TwoWayList($value);
			$newList->prev = $head;
			$newList->next = $head->next;
			$head->next = $newList;
			return true;
		}
		return $this->_insertIntoCyclic($value, $index - 1, $head->next);
	}

	/**
	 * Removes first element of the list.
	 * @return bool
	 */
	public function removeFirst() : bool
	{
		if (is_null($this->head)) {
			echo PHP_EOL . "Error: Element out of range! (0)" . PHP_EOL;
		}
		$this->head = $this->head->next;
		if (is_null($this->head) === false) {
			$this->head->prev = null;
		}
		return true;
	}

	/**
	 * Removes element with given index.
	 * @param int $index
	 * @return bool
	 */
	public function remove(int $index) : bool
	{
		return $this->_remove($index, $this->head);
	}

	private function _remove(int $index, ?TwoWayList &$head) : bool
	{
		if (is_null($head)) {
			echo PHP_EOL . "Error: Element out of range!" . PHP_EOL;
			return false;
		}
		if ($index === 0) {
			if (is_null($head->prev)) {
				$head = $head->next;
				if (is_null($head) === false) {
					$head->prev = null;
				}
				return true;
			}
			if (is_null($head->next)) {
				$head->prev->next = $head->next;
				return true;
			}
			$newHead = new TwoWayList($head->next->data);
			$newHead->prev = $head->prev;
			$newHead->next = $head->next->next;
			$head->prev->next = $newHead;
			return true;
		}
		return $this->_remove($index - 1, $head->next);
	}

	/**
	 * Prints all elements in list.
	 */
	public function print() : void
	{
		echo $this->_print($this->head) . PHP_EOL;
	}

	private function _print(?TwoWayList $head) : string
	{
		if (is_null($head)) {
			return "";
		}
		return (string) $head->data . ", " . (string) $this->_print($head->next);
	}

	/**
	 * Reverses the list.
	 * @return bool
	 */
	public function reverse() : bool
	{
		if (is_null($this->head->next)) {
			echo PHP_EOL . "Warning: The list has only one value. Nothing changed." . PHP_EOL;
			return false;
		}
		$this->head = $this->_reverse($this->head);
		return true;
	}

	private function _reverse(TwoWayList $list, TwoWayList $newList = null) : TwoWayList
	{
		if (is_null($newList)) {
			$newList = new TwoWayList();
			$newList->data = $list->data;
			return $this->_reverse($list->next, $newList);
		}
		if (is_null($list->next) === false) {
			$copy = clone($list);
			$newList->prev = $list;
			$newList->prev->next = $newList;
			return $this->_reverse($copy->next, $newList->prev);
		}
		$newList->prev = $list;
		$newList->prev->next = $newList;
		$newList->prev->prev = null;
		return $newList->prev;
	}

	/**
	 * Changes the list into cyclic list.
	 * @return bool
	 */
	public function toCyclic() : bool
	{
		return $this->_toCyclic($this->head);
	}

	private function _toCyclic(TwoWayList &$list) : bool
	{
		if (is_null($list->next)) {
			$list->next = $this->head;
			return true;
		}
		return $this->_toCyclic($list->next);
	}

	/**
	 * Reverses the cyclic list. Do not use with non-cyclic list.
	 * @return bool
	 */
	public function reverseCyclic() : bool
	{
		if (is_null($this->head->next)) {
			echo PHP_EOL . "Warning: The list has only one value. Nothing changed." . PHP_EOL;
			return false;
		}
		$this->head = $this->_reverseCyclic($this->head);
		return true;
	}

	private function _reverseCyclic(?TwoWayList $list, ?TwoWayList $newList = null) : TwoWayList
	{
		if (is_null($list)) {
			$newList->prev = null;
			return $newList;
		}
		if (is_null($newList)) {
			$newList = new TwoWayList();
			$newList->data = $list->data;
			$newList->next = $this->_getCyclicLast($list);
			return $this->_reverseCyclic($list->next, $newList);
		}
		if (is_null($list->prev) === false) {
			$copy = clone($list);
			$newList->prev = $list;
			$newList->prev->next = $newList;
			return $this->_reverseCyclic($copy->next, $newList->prev);
		}
		$newList->prev = null;
		return $newList;
	}

	private function _getCyclicLast(TwoWayList $list) : TwoWayList
	{
		if (is_null($list->prev)) {
			return $list;
		}
		return $this->_getCyclicLast($list->next);
	}

	/**
	 * Prints the cyclic list. Do not use with non-cyclic list.
	 */
	public function printCyclic() : void
	{
		echo $this->_printCyclic($this->head);
	}

	private function _printCyclic(?TwoWayList $head, ?string $string = "") : string
	{
		if (is_null($head->prev) && $string !== "") {
			return $string;
		}
		$string .= $head->data . ", ";
		return $this->_printCyclic($head->next, $string);
	}

	public function isCyclic() : bool
	{
		return $this->_isCyclic($this->head);
	}

	private function _isCyclic(?TwoWayList $head) : bool
	{
		if (is_null($head)) {
			return false;
		}
		if (is_null($head->next)) {
			return false;
		}
		if (is_null($head->next) === false && is_null($head->next->prev)) {
			return true;
		}
		return $this->_isCyclic($head->next);
	}
}

function createList(int $size) : ?TwoWayListHelper
{
	$helper = new TwoWayListHelper(null);
	for ($i = 0; $i < $size; $i++) {
		$helper->insert($i, 0);
	}
	return $helper;
}

$list = createList(10);
echo "Lista:" . PHP_EOL;
$list->print();

echo PHP_EOL . "Lista wstecz: " . PHP_EOL;
$list->reverse();
$list->print();
$list->reverse();

echo PHP_EOL . "Lista cykliczna: " . PHP_EOL;
$list->toCyclic();
$list->printCyclic();

echo PHP_EOL . "Lista cykliczna wstecz: " . PHP_EOL;
$list->reverseCyclic();
$list->printCyclic();

$list->insert(100, 10);
echo PHP_EOL . "Lista cykliczna wstecz + 100 na końcu: " . PHP_EOL;
$list->printCyclic();