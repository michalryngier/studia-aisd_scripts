<?php


class OneWayList
{
	private int $data;
	private ?OneWayList $next = null;

	public function __construct(int $data)
	{
		$this->data = $data;
	}

	public function insert(int $value, int $i) : bool
	{
		return $this->_insert($value, $i, $this);
	}

	private function _insert(int $value, int $i, ?OneWayList $list) : bool
	{
		if (is_null($list)) {
			return false;
		}
		if ($i === 1) {
			$tmpChild = $list->next;
			$list->next = new OneWayList($value);
			$list->next->next = $tmpChild;
			return true;
		}
		return $list->_insert($value, $i - 1, $list->next);
	}

	public function remove(int $i) : bool
	{
		return $this->_remove($i, $this);
	}

	private function _remove(int $i, ?OneWayList $list) : bool
	{
		if (is_null($list)) {
			return false;
		}
		if ($i === 1) {
				$tmpChild = $list->next->next;
				$list->next = $tmpChild;
			return false;
		}
		return $list->_remove($i - 1, $list->next);
	}

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

	public function destroy() : ?OneWayList
	{
		return $this->_destroy($this);
	}

	private function _destroy(?OneWayList $list) : ?OneWayList
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
	$list = new OneWayList(0);
	for ($i = 1; $i < $size; $i++) {
		$success = $list->insert($i, $i);
	}
	return $success ? $list : null;
}

$list = createList(10);

//var_dump($list);
//
//var_dump($list->read(4));
//var_dump($list->read(5));
//
//$list->insert(100, 5);
//
//var_dump($list->read(4));
//var_dump($list->read(5));
//var_dump($list->read(6));
//
//$list->remove(5);
//
//var_dump($list->read(4));
//var_dump($list->read(5));
//var_dump($list->read(6));

//var_dump($list->destroy());

//$list->print();
//echo PHP_EOL;
//$list->printBackward();

