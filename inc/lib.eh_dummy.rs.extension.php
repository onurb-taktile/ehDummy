<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('DC_RC_PATH')){return;}

class rsEhDummyPublic extends rsExtPost
{
	public static function isDummy($rs){
		return (($rs->count()>0) && isset($rs->dummy) && $rs->dummy!=0);
	}
}
