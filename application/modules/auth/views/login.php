 <style type="text/css">
    body {
      background-color: #283668; //#DADADA;
    }
    body > .grid {
      height: 100%;
      margin: 0!important;
    }
    .column {
      max-width: 450px;
    }
    .login {
      width: 450px;
    }
    
    .login-div{
      box-shadow: 0px 25px 30px -13px rgba(40,40,40,0.4);
      border: 1px solid rgba(0,0,0,0.125);
      border-radius: 1%;
      background: white;
    }
    .ui.segment{
      border: none!important;
      box-shadow: none!important;
    }
  </style>



<div class="ui middle aligned center aligned grid">
  <div class="column login-div">
    <h2 class="ui blue image header">
      <div>
        <img class="ui centered circular image" src="<?php echo base_url('dist/img/logo.png') ?>" alt="Logo Empresa" style="width:100px;height:100px;">
      </div>
      <div class="content">
         <?php echo lang('login_heading');?>
      </div>
    </h2>
    <?php $attributes = array('class' => 'ui large form');  
      echo form_open("auth/login", $attributes);?>          
      <div class="ui stacked segment">
      <div id="infoMessage" ><?php echo $message;?></div>
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
              <?php echo form_input($identity,"",array("placeholder"=>"Correo electrónico"));?>
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
              <?php echo form_input($password,"",array("placeholder"=>"Contraseña"));?>
          </div>
        </div>

        <div class="field">
          <div class="ui left icon input">
            <i class="database icon"></i>
              <?php echo form_input($base,"",array("placeholder"=>"Base"));?>
          </div>
        </div>
        
          <p class="right-align"><?php echo form_submit('submit', lang('login_submit_btn'));?>Ingresar</p>
      </div>
      


    </form>

    <div class="ui segment">
          <p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
    </div>
  </div>
</div>