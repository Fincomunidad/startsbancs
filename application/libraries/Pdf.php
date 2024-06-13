<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php'; // Incluir el autoload de Composer

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
    public function __construct()
    {
        // Configurar opciones si es necesario
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Para permitir la carga de archivos remotos (imágenes, css, etc.)

        $this->dompdf = new Dompdf($options);
    }

    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function setPaper($size, $orientation = 'portrait')
    {
        $this->dompdf->setPaper($size, $orientation);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($filename, $options = array())
    {
        $this->dompdf->stream($filename, $options);
    }
}
