<?php

Class countyController Extends baseController {

public function index() 
{
        $this->registry->template->name = $this->parameters['name'];
		$this->registry->template->long = $this->parameters['long'];
		$this->registry->template->lat = $this->parameters['lat'];
        $this->registry->template->show('county_index');
}




}
?>
