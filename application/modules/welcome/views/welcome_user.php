<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('apple-icon-57x57.png');?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url('apple-icon-60x60.png');?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('apple-icon-72x72.png');?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('apple-icon-76x76.png');?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('apple-icon-114x114.png');?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('apple-icon-120x120.png');?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('apple-icon-144x144.png');?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('apple-icon-152x152.png');?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('apple-icon-180x180.png');?>">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url('android-icon-192x192.png');?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('favicon-32x32.png');?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('favicon-96x96.png');?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('favicon-16x16.png');?>">
    <link rel="manifest" href="<?php echo base_url('manifest.json');?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url('ms-icon-144x144.png');?>">
    <meta name="theme-color" content="#ffffff">



    <title>Bancomunidad</title>
    <link rel="stylesheet" href="<?php echo base_url('dist/semantic/semantic.min.css');?>">        
<!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.10/semantic.min.css">        -->
	<link rel="stylesheet" href="<?php echo base_url('dist/css/default.css');?>">
    <script type="text/JavaScript">
      var base_url = '<?php echo base_url();?>';
    </script>  
</head>
<body>

<?php  
    $esquema = strtoupper($this->session->userdata('esquema'));
    $color = ($esquema == 'FIN.'?'navy': ($esquema == 'BAN.'?'teal':  ($esquema == 'AMA.'?'yellow':'purple') ));
    $colorbtn = ($esquema == 'FIN.'?'blue': ($esquema == 'BAN.'?'purple':'grey'));
?>

 <div class="ui inverted borderless menu <?php  echo $color ;?>">
    <div class="ui container">
      <div class="ui item">
          <img class="ui circular mini image" src="<?php echo base_url('dist/img/logo.png') ?>" alt="Logo Empresa" >
      </div>  
          <?php if ($this->session->userdata('esquemaname') !=''):?>
                 <a class="ui item"> <?php echo $this->session->userdata('esquemaname');?> </a>
          <?php endif;?>    

          <span id="nomsucursal" class="ui item "></span>


        <?php if ($this->session->userdata('nomsucursal') !=''):?>
            <span class="ui item "> Sucursal: <?php echo $this->session->userdata('sucursal_id').' '.$this->session->userdata('nomsucursal');?> </span>
        <?php endif;?>    

      <div class="ui simple dropdown right item">
          <?php echo $this->ion_auth->user()->row()->email;?>
          <i class="dropdown icon"></i>
          <div class="menu">
             <a class="item" href="<?php echo base_url('auth/edit_puser/'.$this->ion_auth->user()->row()->id);?>""><i class="settings icon"></i>Perfil</a>
             <a class="item"href="<?php echo base_url('auth/logout');?>"><i class="sign out icon"></i>Cerrar sesión</a>
          </div>
        </div>  
    </div>
  </div>


  <?php if (sizeof($this->session->flashdata('sucursales')) >1  && $this->session->userdata('grupo')!='Cajera(o)'):?>
        <div class="ui standard mini modal scrolling transition hidden" >
            <div class="ui icon header"><i class="building outline icon"></i> Sucursales</div>
            <div class="center aligned content ">
                <div class="get users">
                <div class="ui form" ref="form" method="post">
                    <input id="csrf" type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                
                    <div class="field">
                        <label>Seleccione</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="sucursal" id="sucursal">
                            <i class="dropdown icon"></i>
                            <div class="default text"></div>
                            <div class="menu">
                                <?php if(!empty($this->session->flashdata('sucursales'))):?>
                                    <?php foreach ($this->session->flashdata('sucursales') as $key => $value):?>
                                        <div class="item" data-value="<?php echo $value['sucursal_id'];?>"><?php echo $value['sucursal_id'].' '. $value['nombre'];?></div>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div> 
            <div class="actions">
                <div class="ui green ok  button"><i class="checkmark icon"></i> Aceptar </div>
            </div>
        </div>



    <?php endif;?>  
  <div id="root">
  
  </div>


<div class="ui main container">
    <?php if (sizeof($this->session->flashdata('sucursales')) > 0 ):?>

          <?php if($this->ion_auth->in_group('caja')):?>
             <?php if (sizeof($this->session->flashdata('sucursales')) == 1 ):?>
                <?php if ($this->session->userdata('idcaja') !=""):?>            

                     <div class="doubling stackable ui grid container <?php  echo $this->ion_auth->in_group('cartera') && $this->ion_auth->in_group('colmena')?'three columnn':$this->ion_auth->in_group('cartera') || $this->ion_auth->in_group('colmena')?'two column':'one column';?>" >

                        <div class="column">
                            <div class=""> 
                                <h2 class="ui center aligned icon header"><i class="circular massive desktop icon grey"></i> Caja </h2>
                                <h4 class="ui center aligned header" >Administración de los créditos</h4>
                                <?php if($this->ion_auth->in_group('caja')):?>
                                    <h4 class="ui sub header center aligned"><a class="ui large label blue" href="<?php echo base_url('caja');?>">Entrar</a></h4>
                                <?php else:?>
                                    <h4 class="ui sub header center aligned"><a  class="ui grey large label" href="#">Denegado</a></h4>
                                <?php endif;?>

                            </div>
                        </div>
                        <?php if($this->ion_auth->in_group('cartera')):?>
                            <div class="column">
                                <div class=""> 
                                    <h2 class="ui center aligned icon header"><i class="circular massive calculator icon grey"></i> Cartera </h2>
                                    <h4 class="ui center aligned header" >Administración de los créditos otorgados</h4>
                                    <?php if($this->ion_auth->in_group('cartera')):?>
                                        <h4 class="ui sub header center aligned"><a class="ui large label <?php echo $colorbtn;?>" href="<?php echo base_url('cartera');?>">Entrar</a></h4>
                                    <?php else:?>
                                        <h4 class="ui sub header center aligned"><a  class="ui grey large label" href="#">Denegado</a></h4>
                                    <?php endif;?>
                                </div>
                            </div>
                        <?php endif;?>

                        <?php if($this->ion_auth->in_group('colmena')):?>
                            <div class="column">
                                <div class=""> 
                                <h2 class="ui center aligned icon header"><i class="circular massive users icon grey"></i> Colmenas </h2>
                                <h4 class="ui center aligned header" >Administración de grupos</h4>
                                    <?php if($this->ion_auth->in_group('colmenas')):?>
                                        <h4 class="ui sub header center aligned"><a class="ui large label <?php echo $colorbtn;?>" href="<?php echo base_url('colmenas');?>">Entrar</a></h4>
                                    <?php else:?>
                                        <h4 class="ui sub header center aligned"><a  class="ui grey large label" href="#">Denegado</a></h4>
                                    <?php endif;?>
                                </div>
                            </div>
                        <?php endif;?>




                    </div>

                <?php else:?>
                        <div class="ui inverted red segment"> 
                            <div class="ui header">Equipo</div>
                            <div class="ui subheader"> Asignación del equipo incorrecto!. Error X001 </div>
                            </div>    
                <?php endif;?>
            <?php else:?>
                <div class="ui inverted red segment"> 
                    <div class="ui header">Sucursal(es)</div>
                    <div class="ui subheader"> Asignación de la(s) sucursal(es) incorrecto!. Error X002 </div>
                </div>    
            <?php endif;?>


        <?php else:?>
            <div class="four column centered  doubling stackable ui grid container" >
                <?php if($this->ion_auth->in_group('contabilidad')):?>
                <div class="column">
                    <div class=""> <h2 class="ui center aligned icon header"><i class="circular massive law icon grey"></i> Contabilidad </h2>
                            <h4 class="ui center aligned header" >Control de todos los movimientos relacionados con la empresa</h4>
                            <h4 class="ui sub header center aligned"><a class="ui large label <?php echo $colorbtn;?>" href="<?php echo base_url('contabil');?>">Entrar</a></h4>
                    </div>
                </div>
               <?php endif;?>	
               <?php if($this->ion_auth->in_group('cartera')):?>
                <div class="column">
                    <div class=""> 
                        <h2 class="ui center aligned icon header"><i class="circular massive calculator icon grey"></i> Cartera </h2>
                        <h4 class="ui center aligned header" >Administración de los créditos otorgados</h4>
                            <h4 class="ui sub header center aligned"><a class="ui large label <?php echo $colorbtn;?>" href="<?php echo base_url('cartera');?>">Entrar</a></h4>
<!--                            
                            <h4 class="ui sub header center aligned"><a  class="ui grey large label" href="#">Denegado</a></h4>
               -->                            
                    </div>
                </div>
                <?php endif;?>	

                <?php if($this->ion_auth->in_group('pld')):?>
                <div class="column">
                    <div class=""> 
                        <h2 class="ui center aligned icon header"><i class="circular massive search icon grey"></i> PLD </h2>
                        <h4 class="ui center aligned header" >Prevención de lavado de dinero</h4>
                            <h4 class="ui sub header center aligned"><a class="ui large label <?php echo $colorbtn;?>" href="<?php echo base_url('pld');?>">Entrar</a></h4>
                    </div>
                </div>
                <?php endif;?>

                <?php if($this->ion_auth->in_group('colmenas')):?>
                <div class="column">
                    <div class=""> 
                    <h2 class="ui center aligned icon header"><i class="circular massive users icon grey"></i> Colmenas </h2>
                    <h4 class="ui center aligned header" >Administración de grupos</h4>
                            <h4 class="ui sub header center aligned"><a class="ui large label <?php echo $colorbtn;?>" href="<?php echo base_url('colmenas');?>">Entrar</a></h4>
                    </div>
                </div>
                <?php endif;?>


                <?php if($this->ion_auth->in_group('bancos')):?>
                <div class="column">
                    <div class=""> 
                    <h2 class="ui center aligned icon header"><i class="circular massive building icon grey"></i> Bancos </h2>
                    <h4 class="ui center aligned header" >Control de bancos</h4>
                            <h4 class="ui sub header center aligned"><a class="ui large label <?php echo $colorbtn;?>" href="<?php echo base_url('bancos');?>">Entrar</a></h4>
                    </div>
                </div>
                <?php endif;?>


                <?php if($this->ion_auth->in_group('gerencial')):?>
                <div class="column">
                    <div class=""> 
                    <h2 class="ui center aligned icon header"><i class="circular line chart icon grey"></i> Gerencial </h2>
                    <h4 class="ui center aligned header" >Toma de decisiones</h4>
                            <h4 class="ui sub header center aligned"><a class="ui large label <?php echo $colorbtn;?>" href="<?php echo base_url('gerencial');?>">Entrar</a></h4>
                    </div>
                </div>
                <?php endif;?>
  

            </div>
        <?php endif;?>
  <?php else:?>
        <div class="ui inverted red segment"> 
             <div class="ui header">Sucursal(es)</div>
             <div class="ui subheader"> Solicite al administrador la asignación de la(s) sucursal(es) a su usuario. Error X003 </div>
        </div>     
  <?php endif;?>

</div>


<script type="text/javascript" src="<?php echo base_url('dist/js/jquery-3.1.1.min.js');?>"></script>    
<script src="<?php echo base_url('dist/semantic/semantic.min.js');?>"></script>
<!--<script src="https://cdn.jsdelivr.net/semantic-ui/2.2.10/semantic.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url('dist/js/welcome_user.min.js');?>"></script>    

</body>
</html>