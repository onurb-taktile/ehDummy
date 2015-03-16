<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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

