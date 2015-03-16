<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of eventHandler, a plugin for Dotclear 2.
#
# Copyright(c) 2014 Nicolas Roudaire <nikrou77@gmail.com> http://www.nikrou.net
#
# Copyright (c) 2009-2013 Jean-Christian Denis and contributors
# contact@jcdenis.fr http://jcd.lv
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')){return;}

# Get new version
$new_version = $core->plugins->moduleInfo('ehDummy','version');
$old_version = $core->getVersion('ehDummy');
$eventhandler_version = $core->getVersion('eventHandler');
define('MIN_EH_VERSION',"2015.03.15");
# Compare versions
if (version_compare($old_version,$new_version,'>=')) return;
# Install
try {
    # Database schema
    $t = new dbStruct($core->con,$core->prefix);
    $t->eventhandler
        ->dummy    ('smallint',0,true,null);

    # Schema installation
    $ti = new dbStruct($core->con,$core->prefix);
    $changes = $ti->synchronize($t);

	# Settings options
	$s = $core->blog->settings->eventHandler;
	if(!$s)
		throw new Exception(_("Eh Dummy requires eventHandler"));
	if(version_compare($eventhandler_version, MIN_EH_VERSION,'<'))
		throw new Exception (sprintf(__("Eh Dummy requires eventHandler V%s minimum, V%s installed. Please update"),MIN_EH_VERSION,$eventhandler_version));

	$s->put('ehdummy_active',false,'boolean','Enabled eventHandler ehdummy addon',false,true);

	# Set version
	$core->setVersion('ehDummy',$new_version);

	return true;
} catch (Exception $e) {
	$core->error->add($e->getMessage());
}
return false;
