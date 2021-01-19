<?php


class TwoWayList
{
	public int $index = 0;
	private int $data;
	private ?TwoWayList $next = null;
	private ?TwoWayList $prev = null;

	public function __construct(int $data)
	{
		$this->data = $data;
	}

}