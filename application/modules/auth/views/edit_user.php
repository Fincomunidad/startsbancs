<div class="ui segment vertical">

<div class="row">                    
    <a href="<?php echo base_url('/auth');?>" class="item"><i class="users icon"></i> Usuarios </a>
</div> 
<br>               
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
                <div class="ui icon input">                
                    <?php echo form_input($first_name);?>
                </div> 
        </div>   
        <div class=" field ">
            <?php echo lang('edit_user_lname_label', 'last_name');?> <br />
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

        <div class=" field ">
            <?php echo lang('edit_user_company_label', 'company');?> <br />
            <div class="ui icon input">                
            <?php echo form_input($company);?>
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
     </div>   
	 

	<?php
		$checked = null;
		if ($lock === 't' ) {
  	 	 $checked= ' checked="checked"';
	   }
	?>



	 <div class="three fields">      
	 	<div class=" field ">
			<div class="ui checkbox">              
				<input type="checkbox" name="lock" value="<?php echo 'true';?>"<?php echo $checked;?>/>
				<label class="checkbox">
				<?php echo htmlspecialchars("Bloqueado",ENT_QUOTES,'UTF-8');?>
				</label>
			</div>
		</div>

	<?php
			$checked = null;
			if ($changepwd == 't' ) {
				$checked= ' checked="checked"';
			}
	?>

		<div class=" field ">
			<div class="ui checkbox">              
				<input type="checkbox" name="changepwd" value="<?php echo 'true';?>"<?php echo $checked;?>/>
				<label class="checkbox">
				<?php echo htmlspecialchars("Cambio de comtraseña",ENT_QUOTES,'UTF-8');?>
				</label>
			</div>
		</div>

     </div>   
	 
	 
	 

      <?php if ($this->ion_auth->is_admin()): ?>
          <h3><?php echo lang('edit_user_groups_heading');?></h3>
          <?php foreach ($groups as $group):?>
              <?php
                  $gID=$group['id'];
                  $checked = null;
                  $item = null;
                  foreach($currentGroups as $grp) {
                      if ($gID == $grp->id) {
                          $checked= ' checked="checked"';
                      break;
                      }
                  }
              ?>
                <div class="ui checkbox">              
                <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>/>
                <label class="checkbox">
                <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
              </label>
              </div>
          <?php endforeach?>

          <h3>Empresas - Sucursales</h3>
          <p>Fincomunidad</p>
          <?php foreach ($sucursales as $sucursal):?>
              <?php
                  $gID=$sucursal['id'];
                  $checked = null;
                  $item = null;
                  foreach($currentSucursales as $suc) {
                      if ($gID == $suc->id) {
                          $checked= ' checked="checked"';
                      break;
                      }
                  }
              ?>
              <div class="ui checkbox">              
              <input type="checkbox" name="sucursal[]" value="<?php echo $sucursal['id'];?>"<?php echo $checked;?>/>
              <label class="checkbox">
              <?php echo htmlspecialchars($sucursal['nombre'],ENT_QUOTES,'UTF-8');?>
              </label>
              </div>
          <?php endforeach?>


          <p>Bancomunidad</p>
          <?php foreach ($sucursales as $sucursal):?>
              <?php
                  $gID=$sucursal['id'];
                  $checked = null;
                  $item = null;
                  foreach($currentSucursalesban as $suc) {
                      if ($gID == $suc->id) {
                          $checked= ' checked="checked"';
                      break;
                      }
                  }
              ?>
              <div class="ui checkbox">              
              <input type="checkbox" name="sucursalban[]" value="<?php echo $sucursal['id'];?>"<?php echo $checked;?>/>
              <label class="checkbox">
              <?php echo htmlspecialchars($sucursal['nombre'],ENT_QUOTES,'UTF-8');?>
              </label>
              </div>
          <?php endforeach?>

          <p>AMA</p>
          <?php foreach ($sucursales as $sucursal):?>
              <?php
                  $gID=$sucursal['id'];
                  $checked = null;
                  $item = null;
                  foreach($currentSucursalesama as $suc) {
                      if ($gID == $suc->id) {
                          $checked= ' checked="checked"';
                      break;
                      }
                  }
              ?>
              <div class="ui checkbox">              
              <input type="checkbox" name="sucursalama[]" value="<?php echo $sucursal['id'];?>"<?php echo $checked;?>/>
              <label class="checkbox">
              <?php echo htmlspecialchars($sucursal['nombre'],ENT_QUOTES,'UTF-8');?>
              </label>
              </div>
          <?php endforeach?>


          <p>IMPULSO</p>
          <?php foreach ($sucursales as $sucursal):?>
              <?php
                  $gID=$sucursal['id'];
                  $checked = null;
                  $item = null;
                  foreach($currentSucursalesimp as $suc) {
                      if ($gID == $suc->id) {
                          $checked= ' checked="checked"';
                      break;
                      }
                  }
              ?>
              <div class="ui checkbox">              
              <input type="checkbox" name="sucursalimp[]" value="<?php echo $sucursal['id'];?>"<?php echo $checked;?>/>
              <label class="checkbox">
              <?php echo htmlspecialchars($sucursal['nombre'],ENT_QUOTES,'UTF-8');?>
              </label>
              </div>
          <?php endforeach?>



      <?php endif ?>

      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>

    <div class="ui vertical segment right aligned">
        <div class="field">    
            <input type="submit" class="ui submit bottom primary basic button" name="submit" value="Guardar Usuario">
      </div>
      </div>

<?php echo form_close();?>

</div>