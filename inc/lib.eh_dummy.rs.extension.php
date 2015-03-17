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

if (!defined('DC_RC_PATH')){return;}

class rsEhDummyPublic extends rsExtPost
{
	public static function isDummy($rs){
		return (($rs->count()>0) && isset($rs->dummy) && $rs->dummy!=0);
	}
}
