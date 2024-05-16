<div class="ui segment vertical">
<div class="row">                    
    <h3  class="ui rojo header"><?php echo lang('edit_user_heading');?></h3>
</div>                
<p><?php echo lang('edit_user_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

  
<?php echo form_open(uri_string(),array(
    'class' => 'ui form' ));?>

    <div class="four fields">
        <div class=" field ">
            <?php echo lang('edit_user_fname_label', 'first_name');?> <br />
            <?php echo form_input( $first_name  ) ;?>                
       </div>   
        <div class=" field ">
            <?php echo lang('edit_user_lname_label', 'last_name');?> <br />
            <div class="ui icon input readonly">                
                <?php echo form_input($last_name);?>
            </div> 
        </div>   
        <div class=" field ">
            <?php echo lang('create_user_name_label', 'user_name');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($user_name);?>
            </div> 
        </div>   

      </div>

        <div class="three fields">      
            <div class=" field ">
                <?php echo lang('edit_user_phone_label', 'phone');?> <br />
                <div class="ui icon input">                
                <?php echo form_input($phone);?>
                </div> 
            </div>   

            <div class=" field ">
                <?php echo lang('edit_user_password_label', 'password');?> <br />
                <div class="ui icon input">                
                <?php echo form_input($password);?>
                </div> 
            </div>   

            <div class=" field ">
                <?php echo lang('edit_user_password_confirm_label', 'password_confirm');?><br />
                <div class="ui icon input">                
                <?php echo form_input($password_confirm);?>
                </div> 
            </div>   

      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>
    </div>

    <div class="row four fields">
        <div class="ui vertical segment right aligned">
           <div class="field">    
              <input type="submit" class="ui submit bottom primary basic button" name="submit" value="Guardar Usuario">
           </div>
        </div>
    </div>

<?php echo form_close();?>

</div>