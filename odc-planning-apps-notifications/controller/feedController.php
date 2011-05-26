<?php

Class feedController Extends baseController {

public function index() 
{
        $this->registry->template->name = $this->parameters['name'];
		
        $this->registry->template->show('feed');
}




}
?>
