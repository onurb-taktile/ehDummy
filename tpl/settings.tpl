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
?>
<?php if ($active):?>

<div class="multi-part" id="ehDummy" title="<?php echo  __('Eh Dummy');?>">
<h3><?php echo __('ehDummy activation');?></h3>
  <p>
    <label class="classic">
      <?php echo form::checkbox(array('ehdummy_active'),'1',$ehdummy_active).' '.__('Enable ehDummy Addon');?>
    </label>
 </p>
</div>
<?php endif;?>

