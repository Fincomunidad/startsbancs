<div class="row">                    
    <a href="<?php echo base_url('/auth');?>" class="item"><i class="users icon"></i> Usuarios </a>
</div> 
<br>               
<div class="row">                    
    <h3  class="ui rojo header"><?php echo lang('deactivate_heading');?></h3>
</div>                

<p><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p>

<?php echo form_open("auth/deactivate/".$user->id);?>

  <p>
  	<?php echo lang('deactivate_confirm_y_label', 'confirm');?>
    <input type="radio" name="confirm" value="yes" checked="checked" />
    <?php echo lang('deactivate_confirm_n_label', 'confirm');?>
    <input type="radio" name="confirm" value="no" />
  </p>

  <?php echo form_hidden($csrf); ?>
  <?php echo form_hidden(array('id'=>$user->id)); ?>

<div class="ui vertical segment left aligned">
        <div class="field">    
            <input type="submit" class="ui submit bottom primary basic button" name="submit" value="Enviar">
      </div>
      </div>


<?php echo form_close();?>