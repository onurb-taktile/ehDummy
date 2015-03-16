<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
	/* Name */			"Event handler dummy addon",
	/* Description*/		"Does nothing usefull except for Eh addon system debug",
	/* Author */			"Onurb",
	/* Version */			'2015.03.15-1',
	array(
		'permissions' =>	'usage,contentadmin',
		'priority' =>		1010,
		'type'		=>		'plugin'
	)
);
