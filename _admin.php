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

if (!defined('DC_CONTEXT_ADMIN')) {
	return;
}

$core->addBehavior('adminEventHandlerSettings', array('adminEhDummy', 'adminEventHandlerSettings'));
$core->addBehavior('adminEventHandlerSettingsSave', array('adminEhDummy', 'adminEventHandlerSettingsSave'));

if ($core->blog->settings->eventHandler->ehdummy_active) {
	$core->addBehavior('adminEventHandlerEventsCustomFilterDisplay', array('adminEhDummy', 'adminEventHandlerEventsCustomFilterDisplay'));
	$core->addBehavior('adminEventHandlerEventsPageCustomize', array('adminEhDummy', 'adminEventHandlerEventsPageCustomize'));
	$core->addBehavior('coreEventHandlerGetEvents', array('adminEhDummy', 'coreEventHandlerGetEvents'));
	$core->addBehavior('coreEventHandlerBeforeGetEvents', array('adminEhDummy', 'coreEventHandlerBeforeGetEvents'));
	$core->addBehavior('adminEventHandlerEventsListHeaders', array('adminEhDummy', 'adminEventHandlerEventsListHeaders'));
	$core->addBehavior('adminEventHandlerEventsListBody', array('adminEhDummy', 'adminEventHandlerEventsListBody'));
	$core->addBehavior('adminEventHandlerMinilistCustomize', array('adminEhDummy', 'adminEventHandlerMinilistCustomize'));
	$core->addBehavior('adminEventHandlerForm', array('adminEhDummy', 'adminEventHandlerForm'));
	$core->addBehavior('adminEventHandlerFormSidebar', array('adminEhDummy', 'adminEventHandlerFormSidebar'));
	$core->addBehavior('adminEventHandleTab', array('adminEhDummy', 'adminEventHandlerTab'));
	$core->addBehavior('adminBeforeEventHandlerCreate', array('adminEhDummy', 'adminBeforeEventHandlerCreate'));
	$core->addBehavior('adminAfterEventHandlerCreate', array('adminEhDummy', 'adminAfterEventHandlerCreate'));
	$core->addBehavior('adminBeforeEventHandlerUpdate', array('adminEhDummy', 'adminBeforeEventHandlerUpdate'));
	$core->addBehavior('adminAfterEventHandlerUpdate', array('adminEhDummy', 'adminAfterEventHandlerUpdate'));
	$core->addBehavior('adminBeforeEventHandlerDelete', array('adminEhDummy', 'adminBeforeEventHandlerDelete'));
	$core->addBehavior('adminEventHandlerActionsCombo', array('adminEhDummy', 'adminEventHandlerActionsCombo'));
	$core->addBehavior('adminEventHandlerActionsManage', array('adminEhDummy', 'adminEventHandlerActionsManage'));
	$core->addBehavior('adminPostsActionsPage', array('adminEhDummy', 'adminPostsActionsPage'));
	$core->addBehavior('adminEventHandlerCustomEventTpl', array('AdminEhDummy', 'adminEventHandlerCustomEventTpl'));
	$core->addBehavior('adminEventHandlerCustomEventsTpl', array('adminEhDummy', 'adminEventHandlerCustomEventsTpl'));

	
}

class adminEhDummy {
	# this behavior creates some specific settings for the addon and displays 
	# these new settings on the admin page

	public static function adminEventHandlerSettings() {

		global $active, $core, $s;

		$ehdummy_active = (boolean) $s->ehdummy_active;

		include(dirname(__FILE__) . '/tpl/settings.tpl');
	}

	# this behavior handles the saving of the addon's specific settings 
	# works with adminEventHandlerSettings

	public static function adminEventHandlerSettingsSave() {
		global $s;
		$ehdummy_active = !empty($_POST['ehdummy_active']);
		$s->put('ehdummy_active', $ehdummy_active, 'boolean');
	}

	#this behavior displays a custom filter on the Events page
	#works with adminEventHandlerEventsPageCustomize

	public static function adminEventHandlerEventsCustomFilterDisplay() {
		global $dummy, $dummy_combo;
		?>
		<p><label for="status" class="ib"><?php echo __('Dummy:'); ?></label>
		<?php echo form::combo('dummy', $dummy_combo, $dummy); ?>
		</p>
			<?php
		}

		# this behavior allows to customize several aspects of the Events page :
		# $args is an array of by reference parameters :
		# $params, $sortby_combo, $show_filters, $redir, $hidden_fields
		# other variables can be created as global for later use 
		#	filtercombos to use in adminEventHandlerEventsCustomFilterDisplay e.g.

		public static function adminEventHandlerEventsPageCustomize($args) {
			foreach ($args as $v => $k)
				$$v = &$args[$v];

			global $dummy, $core;
			$dummy = !empty($_GET['dummy']) ? $_GET['dummy'] : '';
			if (!$core->error->flag()) {
				global $dummy_combo;
				$dummy_combo = array('-' => '', __('Not dummy') => 0, __('Dummy') => 1);
			}

			# - Selected filter
			if ($dummy !== '' && in_array($dummy, $dummy_combo)) {
				$params['dummy'] = $dummy;
				$show_filters = true;
			}

			$redir = $redir . '&amp;dummy=' . $dummy;

			$hidden_fields = $hidden_fields . form::hidden(array('dummy'), $dummy);

			$sortby_combo[__('Dummy')] = 'dummy';
		}

		#this behavior is used to customize the event mini list displayed during
		#event binding to posts process.
		#the parameter is array('params'=>&$params)

		public static function adminEventHandlerMinilistCustomize($args) {
			foreach ($args as $v => $k)
				$$v = &$args[$v];

			#$params['dummy']=1; This would filter the events displayed on the dummy==1 criteria
		}

		# this behavior is for getEvents records manipulation, generally applying some
		# extensions.

		public static function coreEventHandlerGetEvents($rs) {
			$rs->extend('rsEhDummyPublic');
		}

		#this behavior is used to set some specific addons settings before getting events
		#the parameters are array(&$params) and the eventHandler object instance.

		public static function coreEventHandlerBeforeGetEvents($eh, $args) {
			foreach ($args as $v => $k) {
				$$v = &$args[$v];
			} #Recreates the byref args.
			$col = (array) $params['columns'];
			$col[] = 'dummy';
			$params['columns'] = $col;

			if (!empty($params['dummy'])) {
				$params['sql'] .= "AND EH.dummy = '" . $eh->con->escape($params['dummy']) . "' ";
			}
		}

		#this behavior is used to perform some specific addon's actions on the database cursor
		#the parameters are $eh, the eventHandler object instance, $post_id, $cur_post and $cur_event

		public static function coreEventHandlerGetEventCursor($eh, $post_id, $cur_post, $cur_event) {
			
		}

		#this behavior is set to do something before an event is deleted.

		public static function coreEventHandlerEventDelete($eh, $post_id) {
			#do something before event deletion
		}

		#this behavior is set to do something before an event is created (set some addon's customs fields e.g.)
		#the parameters are $eh, the eventHandler object instance, and array(&$cur_post, &cur_event)

		public static function coreEventHandlerBeforeEventAdd($eh, $cur_post, $cur_event) {
			
		}

		#this behavior is set to do something after an event is created
		#the parameters are $eh, the eventHandler object instance, the new event $post_id, $cur_post and $cur_event

		public static function coreEventHandlerAfterEventAdd($eh, $post_id, $cur_post, $cur_event) {
			
		}

		#this behavior is set to do something before an event is updated (set some addon's customs fields e.g.)
		#the parameters are $eh, the eventHandler object instance, $cur_post and $cur_event

		public static function coreEventHandlerBeforeEventUpdate($eh, $cur_post, $cur_event) {
			
		}

		#  this behavior permits events list lines manipulation.
		# the parameter is array('columns'=>&$colums) which is an array containing the html
		# for the list header (<th> cells); You can insert or delete some but be careful
		# to do the same in adminEventHandlerEventsListBody to get a coherent table.
		# When called from Minilist, the parameter $ismini is true
		
		public static function adminEventHandlerEventsListHeaders($args,$ismini=false) {
			$columns=&$args['columns'];
			$num = 3; //Insert a new column header @3rd position.
			if ($ismini)
				$num++;#Minilist adds a 'period' column so we increase $num
			$columns = array_merge(array_slice($columns, 0, $num), array('<th>' . __('Dummy') . '</th>'), array_slice($columns, $num));
		}

		# this behavior permits events list lines manipulation.
		# the parameter is array('columns'=>&$colums) which is an array containing the html
		# for the list header (<th> cells); You can insert or delete some but be careful
		# to do the same in adminEventHandlerEventsListHeaders to get a coherent table.
		# When called from Minilist, the parameter $ismini is true

		public static function adminEventHandlerEventsListBody($rs,$args,$ismini=false) {
			$columns=&$args['columns'];
			foreach ($args as $v => $k) {
				$$v = &$args[$v];
			} #Recreates the byref args.
			$num = 3; //Insert a new column header @3rd position.
			if ($ismini)
				$num++;#Minilist adds a 'period' column so we increase $num
			$columns = array_merge(array_slice($columns, 0, $num), array("<td>" . form::checkbox('dummy[' . $rs->post_id . ']', '1', (boolean) $rs->dummy, '', '', true) . "</td>"), array_slice($columns, $num));
		}

		#this behavior is for action combo manipulation. 
		#the parameter is array(&$action_combo)

		public static function adminPostsActionsPage($core, $ap) {
			if ($ap->getURI() != 'plugin.php')
				return;#prevents the menu to be added on posts list.
			$ap->addAction(
					array(__('Dummy') => array(
					__('Set dummy') => 'dummify',
					__('Set not dummy') => 'undummify'
				)), array('adminEhDummy', 'doChangeDummy')
			);
		}

		#This callback is called when the action combo is used on post action page

		public static function doChangeDummy($core, dcPostsActionsPage $ap, $post) {
			switch ($ap->getAction()) {
				case 'dummify' : $dummy = 1;
					break;
				case 'undummify' : $dummy = 0;
					break;
			}
			$posts_ids = $ap->getIDs();
			if (empty($posts_ids)) {
				throw new Exception(__('No entry selected'));
			}
			global $eventhandler;
			$cur_event = $core->con->openCursor($core->prefix . 'eventhandler');
			$cur_post = $core->con->openCursor($core->prefix . 'post');
			$cur_event->dummy = $dummy;
			$cur_event->update('WHERE post_id ' . $core->con->in($posts_ids));
			dcPage::addSuccessNotice(sprintf(
							__('%d entry has been successfully %sdummified', '%d entries have been successfully %sdummified', count($posts_ids)
							), count($posts_ids), ($dummy == 1) ? '' : 'un')
			);
			$ap->redirect(true);
		}

		/* This behavior inserts some content just below the eventhandler specific part on event creation/edition page
		 *  $post parameter is not null when on an edition page
		 */

		public static function adminEventHandlerForm($post) {
			$dummy = isset($post) && $post->dummy ? $post->dummy : false;
			?>
		<div id="ehdummy">
			<label class="classic">
		<?php echo form::checkbox('dummy', 1, $dummy) . ' ' . __('Dummy'); ?>
			</label>
		</div>
		<?php
	}

	/* This behavior inserts some content just below the eventhandler specific part on event creation/edition page sidebar
	 *  $post parameter is not null when on an edition page
	 */

	public static function adminEventHandlerFormSidebar($post) {
		
	}

	/* This behavior inserts a new tab on event creation/edition page
	 *  $post parameter is not null when on an edition page
	 */

	public static function adminEventHandlerTab($post) {
		
	}

	/* this behavior is used to perform some specific actions before event creation */

	public static function adminBeforeEventHandlerCreate($cur_post, $cur_event) {
		$dummy = isset($_POST['dummy']) ? (integer) $_POST['dummy'] : null;
		$cur_event->dummy = $dummy;
	}

	/* this behavior is used to perform some specific actions after event creation */

	public static function adminAfterEventHandlerCreate($cur_post, $cur_event, $post_id) {
		
	}

	/* this behavior is used to perform some specific actions before event update */

	public static function adminBeforeEventHandlerUpdate($cur_post, $cur_event, $post_id) {
		$dummy = isset($_POST['dummy']) ? (integer) $_POST['dummy'] : null;
		$cur_event->dummy = $dummy;
	}

	/* this behavior is used to perform some specific actions after event update */

	public static function adminAfterEventHandlerUpdate($cur_post, $cur_event, $post_id) {
		
	}

	/* this behavior is used to perform some specific actions before event deletion */

	public static function adminBeforeEventHandlerDelete($post_id) {
		global $dummy;
		$dummy = isset($_POST['dummy']) ? (integer) $_POST['dummy'] : null;
		$cur_event->dummy = $dummy;
	}

	/* this behavior is used to manipulate the actions combo for the index_events.php page */

	public static function adminEventHandlerActionsCombo($combo_actions) {
		$combo_actions[0][__('Dummy')] = array(
			__('Set dummy') => 'dummify',
			__('Set not dummy') => 'undummify'
		);
	}

	/* this behavior is the place for events actions management */

	public static function adminEventHandlerActionsManage(eventHandler $eh, $action) {
		if ($action != 'dummify' && $action != 'undummify')
			return;
		global $p_url, $core;
		try {
			$redir = $core->getPostAdminURL($from_post->post_type, $from_post->post_id);
			if (isset($_POST['redir']) && strpos($_POST['redir'], '://') === false) {
				$redir = $_POST['redir'];
			} elseif (!$redir) {
				$redir = $p_url . '&part=events';
			}
			$entries = $_POST['entries'];

			$cur_event = $core->con->openCursor($core->prefix . 'eventhandler');
			$cur_event->dummy = ($action == 'dummify') ? 1 : 0;
			$cur_event->update('WHERE post_id ' . $core->con->in($entries));
			dcPage::addSuccessNotice(sprintf(
							__(
									'%d entry has been successfully %sdummified', '%d entries have been successfully %sdummified', count($entries)
							), count($entries), ($action == 'dummify') ? '' : 'un'
			));
			http::redirect($redir);
		} catch (Exception $e) {
			$core->error->add($e->getMessage());
		}
	}

	/* @func AdminEhDummy::adminEventHandlerCustomEventTpl
	 * This behavior can be used to include a custom tpl as event editor.
	 * Don't forget to exit or the default tpl will be loaded as well.
	 * You should probably use the behaviors to change the page instead of 
	 * setting a new one. Use only if desperate.	 
	 */

	public static function adminEventHandlerCustomEventTpl() {
		#include(dirname(__FILE__).'/tpl/custom_event.tpl');
		#exit;
	}
	

	/* @func adminEhDummy::adminEventHandlerCustomEventsTpl
	 * This behavior can be used to include a custom tpl for events list.
	 * Don't forget to exit or the default tpl will be loaded as well.
	 * You should probably use the behaviors to change the page instead of 
	 * setting a new one. Use only if desperate.
	 */
	public static function adminEventHandlerCustomEventsTpl(){
		#include(dirname(__FILE__).'/tpl/custom_events.tpl');
		#exit;		
	}


}
