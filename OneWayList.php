<?php


class OneWayList
{
	public ?int $data = null;
	public ?OneWayList $next = null;

	public function __construct(?int $data = null)
	{
		$this->data = $data;
	}

	/**
	 * Inserts new value at specified index or at index = 0.
	 * @param int $value
	 * @param int $i
	 * @return bool
	 */
	public function insert(int $value, int $i = 0) : bool
	{
		return $this->_insert($value, $i, $this);
	}

	private function _insert(int $value, int $i, ?OneWayList $list) : bool
	{
		if (is_null($list)) {
			return false;
		} else if ($i === 0 && is_null($list->data)) {
			$list->data = $value;
			return true;
		} else if ($i === 0) {
			$newList = new OneWayList($list->data);
			$newList->next = $list->next;
			$list->data = $value;
			$list->next = $newList;
			return true;
		} else if ($i === 1) {
			$tmpChild = $list->next;
			$list->next = new OneWayList($value);
			$list->next->next = $tmpChild;
			return true;
		}
		return $list->_insert($value, $i - 1, $list->next);
	}

	/**
	 * Returns new list with specified index removed from this object.
	 * @param int $i
	 * @return OneWayList
	 */
	public function remove(int $i) : OneWayList
	{
		return $this->_remove($i, $this);
	}

	private function _remove(int $i, ?OneWayList &$list) : ?OneWayList
	{
		if (is_null($list)) {
			return null;
		}
		if ($i === 0) {
			if (is_null($list->next)) {
				return null;
			}
			$newList = new OneWayList();
			$newList->data = $list->next->data;
			$newList->next = $list->next->next;
			return $newList;
		}
		if ($i === 1) {
			$tmpChild = $list->next->next;
			$list->next = $tmpChild;
			return $list;
		}
		return $list->_remove($i - 1, $list->next);
	}

	/**
	 * Returns specified index value.
	 * @param int $i
	 * @return int|null
	 */
	public function read(int $i) : ?int
	{
		return $this->_read($i, $this);
	}

	private function _read(int $i, ?OneWayList $list) : ?int
	{
		if (is_null($list)) {
			return null;
		}
		if ($i === 0) {
			return $list->data;
		}
		return $list->_read($i - 1, $list->next);
	}

	/**
	 * Returns the list size.
	 * @return int
	 */
	public function size() : int
	{
		return $this->_size($this, 0);
	}

	private function _size(?OneWayList $list, int $size) : int
	{
		if (is_null($list)) {
			return $size;
		}
		return $list->_size($list->next, $size + 1);
	}

	/**
	 *
	 */
	public function print()
	{
		$this->_print($this);
	}

	private function _print(?OneWayList $list) : void
	{
		if (is_null($list)) {
			return;
		}
		if (is_null($list->next)) {
			echo $list->data;
		} else {
			echo $list->data . ", ";
		}
		$list->_print($list->next);
	}

	/**
	 * Prints tle list backward
	 */
	public function printBackward()
	{
		echo $this->_printBackward($this);
	}

	private function _printBackward(?OneWayList $list) : string
	{
		if (is_null($list->next)) {
			return (string) $list->data;
		}
		return $list->_printBackward($list->next) . ", " . $list->data;
	}

	/**
	 * @return OneWayList|null
	 */
	public function destroy() : ?OneWayList
	{
		return $this->_destroy($this);
	}

	private function _destroy(?OneWayList &$list) : ?OneWayList
	{
		if (is_null($list->next)) {
			// destroy $list
			return null;
		}
		$list = $list->_destroy($list->next);
		return $list;
	}
}

function createList(int $size) : ?OneWayList
{
	$success = true;
	$list = new OneWayList();
	for ($i = 1; $i < $size; $i++) {
		$success = $list->insert($i, $i - 1);
	}
	return $success ? $list : null;
}

$list = createList(10);

echo "3 index: " . $list->read(3) . PHP_EOL;
echo "Lista:" . PHP_EOL;
$list->print();

echo PHP_EOL . "Lista wstecz: " . PHP_EOL;
$list->printBackward();

echo PHP_EOL . "Wielkość: " . PHP_EOL;
echo $list->size();