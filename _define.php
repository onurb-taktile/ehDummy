<?php
/* -- BEGIN LICENSE BLOCK ----------------------------------
 *
 * This file is part of ehDummy, a plugin for Dotclear 2.
 *
 * Copyright(c) 2015 Onurb Teva <dev@taktile.fr>
 *
 * Licensed under the GPL version 2.0 license.
 * A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * -- END LICENSE BLOCK ------------------------------------*/

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
	/* Name */			"Event handler dummy addon",
	/* Description*/		"Does nothing usefull except for Eh addon system debug",
	/* Author */			"Onurb Teva <dev@taktile.fr>",
	/* Version */			'2015.03.16',
	array(
		'permissions' =>	'usage,contentadmin',
		'priority' =>		1010,
		'type'		=>		'plugin'
	)
);
