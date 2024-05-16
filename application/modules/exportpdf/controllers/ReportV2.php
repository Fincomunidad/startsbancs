<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once(APPPATH.'/third_party/dompdf/dompdf_config.inc.php');

class Exportpdf {
	public function index() {	
            $html ='<!DOCTYPE html>
            <html>
            <head lang="en">
                <meta charset="UTF-8">
                <title>Planeación Didáctica</title>
                <style>
                    body{
                        font-family: arial, helvetica, sans-serif;
                        color: #000;
                        background: #fff;
                        text-align: justify;
                        padding: 0px 40px;
                        line-height: 150%;
                    }
                    .titulo{
                        text-align: center;
                        padding:5px 0px;
                        font-size:18px;
                        font-weight: bold;
                    }
                    .titulo-data {
                        line-height: 50%;
                    }
                    .text-space {
                        line-height: 150% !important;                        
                    }

                    .text-title {
                        padding: 0;
                        text-align: left;
                    }

                    .subtitle {
                       font-weight: bold;
                       padding-bottom: 0px; 
                    }

                    .logo{
                        font-size: 30px;
                    }
                    .orange{
                        color:orange;
                    }
                    table, th, td {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding-left: 5px;
                        padding-right: 5px;                        
                    }
                    td {
                        text-align: justify;
                    }
                    table-coldes {
                        width: 70%;
                    }
                    .nombre{
                        border-bottom: 1px solid cornsilk;
                        font-size: 24px;
                        font-family: Courier, "Courier new", monospace;
                        font-style: italic;
                    }
                    .descripcion{
                        font-size: 24px;
                        padding: 30px 0px;
                    }
                    .image-top {
                        top: -15px;
                        position: absolute;
                        float: right;

                    }
                    .span-right {
                        padding-left: 85px;
                    }
                    .bordos {
                        border: 1px solid black;
                        border-radius: 0;
                        padding: 5px;
                    }

                    .footer {
                        position: fixed;
                        bottom: 0;
                        font-size: 11px;
                        padding-left:40px;
                        color: #73879C;

                    }
                </style>
            </head>
            <body>';
            $html = $html.'<div class="footer"><em>INSTITUTO DE ESTUDIOS DE BACHILLERATO DEL ESTADO DE OAXACA</em></div>';
            $html = $html.'<div class="titulo">PLANEACIÓN DIDÁCTICA</div>';
            $html = $html.'<div class="image-top"><img src="'.base_url("site_media/img/pd.png").'" height="50px" alt=""></div>';
            $html = $html.'<div class="titulo">1.- CONTEXTO INTERNO Y EXTERNO DE LA ESCUELA</div>';

            $html = $html.'</body>';
            $html = $html.'</html>';
        }
        
        
        
        $dompdf = new DOMPDF();
		// Load library
//		$this->load->library('dompdf_gen');
		// Convert to PDF
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->set_paper('letter', 'portrait');
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$this->dompdf->stream("file.pdf", array("Attachment" => 0));



}	