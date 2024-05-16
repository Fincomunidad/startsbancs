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
	<link rel="stylesheet" href="<?php echo base_url('dist/css/default.css');?>">

	<link rel="stylesheet" href="<?php echo base_url('dist/semantic/semantic.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('dist/css/calendar.min.css');?>">

<!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.10/semantic.min.css">    
    <link href="https://cdn.rawgit.com/mdehoog/Semantic-UI-Calendar/76959c6f7d33a527b49be76789e984a0a407350b/dist/calendar.min.css" rel="stylesheet" type="text/css" />    
    -->
   
    <script type="text/JavaScript">
      var base_url = '<?php echo base_url();?>';
      var site_url = '<?php echo site_url();?>';
      var esquema = '<?php echo $this->session->userdata('esquema');?>';
    </script>  
</head>
<body style>
    <!--  Creación de menu dinamico  -->
    <?php if($this->ion_auth->logged_in()):?>
        <div class="ui top attached demo menu">
            <div class="ui inverted borderless fixed  menu <?php  echo (strtoupper($this->session->userdata('esquema')) =='FIN.'?'navy': (strtoupper($this->session->userdata('esquema')) =='BAN.'?'teal': (strtoupper($this->session->userdata('esquema')) =='AMA.'?'yellow':'purple'))) ;?>"  >
                <div class="ui container">
                    <?php if ($this->session->userdata('grupo') == 'Cajera(o)' ):?>
                        <a class="ui item" href="<?php echo base_url('/');?>"> <?php echo  $this->session->userdata('esquemaname');?> </a>
                        <a class="ui item "> Caja: <?php echo $this->session->userdata('idcaja');?></a>
                        <span class="ui item "> Sucursal: <?php echo $this->session->userdata('sucursal_id').' '.$this->session->userdata('nomsucursal');?> </span>
                    <?php else:?>
                        <a id ="menuleft" class="item"><i class="sidebar icon"></i> Menu </a>
                        <a href="<?php echo base_url();?>" class="ui item"><i class="home large icon"></i> <?php echo  $this->session->userdata('esquemaname');?> </a>
                        <span class="ui item "> Sucursal: <?php echo $this->session->userdata('sucursal_id').' '.$this->session->userdata('nomsucursal');?> </span>
                    <?php endif;?>                

            <!-- Menu de usuario activo, sistemas y cerrar sesión-->   
                    <div class="ui simple dropdown right item">
                        <?php echo $this->ion_auth->user()->row()->email;?>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?php if ($this->session->userdata('grupo') == 'Cajera(o)' ):?>
                            <?php else:?>
                                <?php if($this->ion_auth->in_group('contabilidad')  && $this->session->userdata('opcion') !='contabil'):?>
                                    <a class="item" href="<?php echo base_url('contabil');?>"><i class="law icon"></i>Contabilidad</a>
                                <?php endif;?>		        
                                <?php if($this->ion_auth->in_group('cartera') && $this->session->userdata('opcion') !='cartera'):?>
                                    <a class="item" href="<?php echo base_url('cartera');?>"><i class="calculator icon"></i>Cartera</a>
                                <?php endif;?>		        
                                <?php if($this->ion_auth->in_group('pld') && $this->session->userdata('opcion') !='pld'):?>
                                    <a class="item" href="<?php echo base_url('pld');?>"><i class="search icon"></i>PLD</a>
                                <?php endif;?>		        
                                <?php if($this->ion_auth->in_group('colmenas') && $this->session->userdata('opcion') !='colmenas'):?>
                                    <a class="item" href="<?php echo base_url('colmenas');?>"><i class="users icon"></i>Colmenas</a>
                                <?php endif;?>
                                <?php if($this->ion_auth->in_group('bancos') && $this->session->userdata('opcion') !='bancos'):?>
                                    <a class="item" href="<?php echo base_url('bancos');?>"><i class="building icon"></i>Bancos</a>
                                <?php endif;?>
                                <?php if($this->ion_auth->in_group('gerencial') && $this->session->userdata('opcion') !='gerencial'):?>
                                    <a class="item" href="<?php echo base_url('gerencial');?>"><i class="line chart icon"></i>Gerencial</a>
                                <?php endif;?>
                                
                                <div class="ui divider"></div>
                            <?php endif;?>
								<a class="item" href="<?php echo base_url('auth/edit_puser/'.$this->ion_auth->user()->row()->id);?>""><i class="settings icon"></i>Perfil</a>
								<a class="item" href="<?php echo base_url('auth/logout');?>"><i class="sign out icon"></i>Cerrar sesión</a>
                        </div>
                    </div>  
                </div>
            </div>  
        </div>
       <?php if ($this->session->userdata('grupo') != 'Cajera(o)' ):?>
            <div class="ui bottom top attached pushablenew">
                <div id="mainleft" class="ui sidebar labeled icon left inline vertical  menu overlay animating ">
                    <div class="ui vertical following fluid accordion text menu ancho">
                        <?php $primero =1; $menus = $this->session->userdata('menus');?>
                            <?php if(!empty($menus)):?>
                                <?php foreach ($this->session->userdata('menus') as $key => $value):?>
                                    <?php if($value['option'] === "M" && $value['level'] === "1"):?>
                                        <?php if($primero != 1):?>
                                            </div>
                                            </div>
                                        <?php endif;?>

                                        <div class="item">
                                            <a class="title">
                                        <?php $primero = 2?>
                                            <i class="dropdown icon"></i>  
                                            <b><?php echo $value['name'];?></b>
                                            </a>
                                        <div class="content menu">                                            
                                    <?php else :?>
                                            <?php if($value['option'] === "O" && $value['level'] != "1"):?>   
                                                <a class="item" href="<?php echo base_url($value['link']);?>"><?php echo $value['name'];?></a>
                                            <?php else :?>         
                                                <a class="item" href="<?php echo base_url($value['link']);?>"><?php echo $value['name'];?></a> 
                                            <?php endif;?>
                                    <?php endif;?>
                                <?php endforeach;?>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                </div>

                <div id="pusher" class="pusher ">
                    <div class="ui main container ">
                        <?php echo $content;?>
                    </div>
                </div>
            </div>
        <?php else:?>
                <div id="pusher" class="pusher ">
                    <div class="ui main">
                        <?php echo $content;?>
                    </div>
                </div>
        <?php endif;?>


    <?php else:?>
         <?php echo $content;?>
    <?php endif;?>




    <script type="text/javascript" src="<?php echo base_url('dist/js/jquery-3.1.1.min.js');?>"></script>    

<!--
    <script src="https://unpkg.com/react@15/dist/react.min.js"></script>
    <script src="https://unpkg.com/react-dom@15/dist/react-dom.min.js"></script>
	-->


    <script src="<?php echo base_url('dist/react/react.min.js');?>"></script>
    <script src="<?php echo base_url('dist/react/react-dom.min.js');?>"></script>


    <script src="<?php echo base_url('dist/semantic/semantic.min.js');?>"></script>
    <script src="<?php echo base_url('dist/js/calendar.min.js');?>"></script>


<!--    <script src="https://cdn.jsdelivr.net/semantic-ui/2.2.10/semantic.min.js"></script>
    <script src="https://cdn.rawgit.com/mdehoog/Semantic-UI-Calendar/76959c6f7d33a527b49be76789e984a0a407350b/dist/calendar.min.js"></script>-->


    <script src="<?php echo base_url('dist/js/numeral.min.js');?>"></script>

    <script type="text/javascript" src="<?php echo base_url('dist/js/iniajax.js') ;?>"> </script>
    <?php if(!empty($js)):?>
    <?php foreach ($js as $row):?>
    <script type="text/javascript" src="<?php echo base_url('dist/js/'.$row) ;?>"> </script>
    <?php endforeach;?>
    <?php endif;?>
    <script type="text/javascript" src="<?php echo base_url('dist/js/inicio.js') ;?>"> </script>


       <?php if (base_url($this->session->userdata('opcion'))  == current_url() ):?>
            <script>
                $("#mainleft").addClass("visible");
                $("#pusher").addClass("dimmednew");
        </script>
       <?php endif;?>


</body>
</html>
