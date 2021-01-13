<?php

class ModelFunction{
	var $value = "";
	function __construct($fn_name, ...$args){
		$this->value = "$fn_name( ".implode(",", $args).")";
	}
}