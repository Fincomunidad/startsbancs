<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/modules/api/controllers/CarteraV1.php');



require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExcelV1 extends CarteraV1 {
	public function __construct()
    {
		parent::__construct();
	}

   public function excelprueba_get(){


		$spreadsheet = new Spreadsheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="myfile.xls"');
header('Cache-Control: max-age=0');



		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Hello World !');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');


//		$writer = new Xlsx($spreadsheet);
//		$writer->save('hello world.xlsx');




   }



}