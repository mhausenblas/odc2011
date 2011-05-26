<?php

Abstract Class baseController {

/*
 * @registry object
 */
protected $registry;
protected $parameters= array();

function __construct($registry,$arr) {
	$this->registry = $registry;
	$this->parameters=$arr;
}

/**
 * @all controllers must contain an index method
 */
abstract function index();
}

?>
