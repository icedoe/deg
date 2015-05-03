<?php
namespace Deg\Table;
class TableException extends \Exception
{
	public function __construct($msg)
	{
		parent::__construct($msg);
	}
}