<div class="ui segment vertical">

<div class="row">                    
    <a href="<?php echo base_url('/auth');?>" class="item"><i class="users icon"></i> Usuarios </a>
</div> 
<br>               
<div class="row">                    
    <h3  class="ui rojo header"><?php echo lang('create_user_heading');?></h3>
</div>                

<p><?php echo lang('create_user_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/create_user" ,array(
    'class' => 'ui form' ));?>

    <div class="four fields">
        <div class=" field ">
            <?php echo lang('create_user_fname_label', 'first_name');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($first_name);?>
            </div> 
        </div>   
        <div class=" field ">
            <?php echo lang('create_user_lname_label', 'last_name');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($last_name);?>
            </div> 
        </div>   
        <div class=" field ">
            <?php echo lang('create_user_name_label', 'user_name');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($user_name);?>
            </div> 
        </div>   
      <?php
      if($identity_column!=='email') {
          echo '<p>';
          echo lang('create_user_identity_label', 'identity');
          echo '<br />';
          echo form_error('identity');
          echo form_input($identity);
          echo '</p>';
      }
      ?>

        <div class=" field ">
            <?php echo lang('create_user_company_label', 'company');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($company);?>
            </div> 
        </div>   
     </div>

    <div class="four fields">
        <div class=" field ">
            <?php echo lang('create_user_email_label', 'email');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($email);?>
            </div> 
        </div>   
        <div class=" field ">
            <?php echo lang('create_user_phone_label', 'phone');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($phone);?>
            </div> 
        </div>   
        <div class=" field ">
            <?php echo lang('create_user_password_label', 'password');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($password);?>
            </div> 
        </div>   
        <div class=" field ">
            <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($password_confirm);?>
            </div> 
        </div>   
      </div>  
 	<?php
		$checked= ' checked="checked"';
	?>

		<div class=" field disabled">
			<div class="ui checkbox">              
				<input type="checkbox" name="changepwd" value="<?php echo 'true';?>"<?php echo $checked;?>/>
				<label class="checkbox">
				<?php echo htmlspecialchars("Cambio de comtraseña",ENT_QUOTES,'UTF-8');?>
				</label>
			</div>
		</div>
    </div>   


      <h3><?php echo lang('edit_user_groups_heading');?></h3>

<p>
       <?php $iconteo =0 ;?>     
       <?php foreach ($group as $groups):?>
              <?php
                  $gID=$groups[0];
                  $checked = null;
                  $item = null;
              ?>
                <div class="ui checkbox" style="margin-right: 15px">              

              <input type="checkbox" name="groups[]" value="<?php echo $iconteo;?>"<?php echo $checked;?>>
              <label class="checkbox">
              <?php echo htmlspecialchars($groups,ENT_QUOTES,'UTF-8'); $iconteo++;?>
              </label>
              </div>

          <?php endforeach?>
</p>

<h3>Empresas - Sucursales</h3>

<p>Fincomunidad</p>
<p>
          <?php foreach ($sucursal as $sucursales):?>
              <div class="ui checkbox" style="margin-right: 15px">              

              <input type="checkbox" name="sucursal[]" value="<?php echo $sucursales['id'];?>">
              <label class="checkbox">
              <?php echo htmlspecialchars($sucursales['nombre'],ENT_QUOTES,'UTF-8');?>
              </label>
            </div>
          <?php endforeach?>
</p>

<p>Bancomunidad</p>
<p>
          <?php foreach ($sucursal as $sucursales):?>
              <div class="ui checkbox" style="margin-right: 15px">              

              <input type="checkbox" name="sucursalban[]" value="<?php echo $sucursales['id'];?>">
              <label class="checkbox">
              <?php echo htmlspecialchars($sucursales['nombre'],ENT_QUOTES,'UTF-8');?>
              </label>
            </div>
          <?php endforeach?>
</p>


<p>AMA</p>
<p>
          <?php foreach ($sucursal as $sucursales):?>
              <div class="ui checkbox" style="margin-right: 15px">              

              <input type="checkbox" name="sucursalama[]" value="<?php echo $sucursales['id'];?>">
              <label class="checkbox">
              <?php echo htmlspecialchars($sucursales['nombre'],ENT_QUOTES,'UTF-8');?>
              </label>
            </div>
          <?php endforeach?>
</p>


<p>IMPULSO</p>
<p>
          <?php foreach ($sucursal as $sucursales):?>
              <div class="ui checkbox" style="margin-right: 15px">              

              <input type="checkbox" name="sucursalimp[]" value="<?php echo $sucursales['id'];?>">
              <label class="checkbox">
              <?php echo htmlspecialchars($sucursales['nombre'],ENT_QUOTES,'UTF-8');?>
              </label>
            </div>
          <?php endforeach?>
</p>






   <div class="ui vertical segment right aligned">
        <div class="field">    
            <input type="submit" class="ui submit bottom primary basic button" name="submit" value="Crear Usuario">
      </div>
      </div>



<?php echo form_close();?>
</div>