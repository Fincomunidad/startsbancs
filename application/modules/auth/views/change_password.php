

<div class="ui segment vertical">

<div class="row">                    
    <a href="<?php echo base_url('/auth');?>" class="item"><i class="users icon"></i> Usuarios </a>
</div> 
<br>               

<div class="row">                    
<h3  class="ui rojo header"><?php echo lang('change_password_heading');?></h3>
</div>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/change_password",array(
    'class' => 'ui form' ));?>


    <div class="two fields">
        <div class=" field ">
            <?php echo lang('change_password_old_password_label', 'old_password');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($old_password);?>
             </div>
         </div>
    </div>         

    <div class="two fields">
        <div class=" field ">
            <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label> <br />
            <div class="ui icon input">                
            <?php echo form_input($new_password);?>
             </div>
         </div>
    </div>         
    <div class="two fields">
        <div class=" field ">
            <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($new_password_confirm);?>
             </div>
         </div>
    </div>         
      

      <?php echo form_input($user_id);?>
      <div class="ui vertical segment left aligned">
        <div class="field">    
            <input type="submit" class="ui submit bottom primary basic button" name="submit" value="Cambiar">
      </div>
      </div>
<!--      <p><?php echo form_submit('submit', lang('change_password_submit_btn'));?></p>-->

<?php echo form_close();?>
