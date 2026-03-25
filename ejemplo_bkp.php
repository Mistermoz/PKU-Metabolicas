<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt  LGPL
 * @version    1.7.9, 2013-06-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
  die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'phptoexcel/Classes/PHPExcel.php';
require_once '../../../wp-blog-header.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
               ->setLastModifiedBy("Maarten Balliauw")
               ->setTitle("Office 2007 XLSX Test Document")
               ->setSubject("Office 2007 XLSX Test Document")
               ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
               ->setKeywords("office 2007 openxml php")
               ->setCategory("Test result file");

// Style Cells
$styleArray = array(
    'font' => array(
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array('argb' => 'FF000000'),
        )
      ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'FFC4C4',
        ),
        'endcolor' => array(
            'argb' => 'FFDDDD',
        ),
    ),
);

// Rellenos con datos

$nom_paciente = $_GET['nom'];
$fecha_paciente = $_GET['fecha'];
global $dbh;
if($nom_paciente)
{
  $query = "SELECT * FROM paciente WHERE Nombre='".$nom_paciente."' ORDER BY F_lectura DESC";
  $content = $dbh->get_results( $query );
  $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Fenil')
            ->setCellValue('B1', 'Tir')
            ->setCellValue('C1', 'MSUD')
            ->setCellValue('D1', 'F.Lectura')
            ->setCellValue('E1', 'Estado')
            ->setCellValue('F1', 'P.Lectura')
            ->setCellValue('G1', 'F.Leche')
            ->setCellValue('H1', 'F.Muestra')
            ->setCellValue('I1', 'F.Control');
  $i=2;
  if ( count($content) > 0 ) {
      foreach ( $content as $row ) {
        $objPHPExcel->getActiveSheet(0)
            ->setCellValue('A'.$i, $row->Fenil)
            ->setCellValue('B'.$i, $row->Tir)
            ->setCellValue('C'.$i, $row->MSUD)
            ->setCellValue('D'.$i, $row->Fecha_lectura)
            ->setCellValue('E'.$i, $row->Estado)
            ->setCellValue('F'.$i, $row->prox_lectura)
            ->setCellValue('G'.$i, $row->fecha_entrega_leche)
            ->setCellValue('H'.$i, $row->fecha_toma_muestra)
            ->setCellValue('I'.$i, $row->fecha_control);
        $objPHPExcel->getActiveSheet(0)->getStyle('A1:I1')->applyFromArray($styleArray);
        $i++;
      }
  }
  $hoy = date("d-m-y");
  $nom_archivo = 'Ficha-'.$nom_paciente.'-'.$hoy;
}else {
  $query = "SELECT * FROM paciente WHERE F_lectura='".$fecha_paciente."' ORDER BY Nombre ASC";
  $content = $dbh->get_results( $query );
  $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nombre')
            ->setCellValue('B1', 'Fecha Lectura')
            ->setCellValue('C1', 'Fenil')
            ->setCellValue('D1', 'Tir')
            ->setCellValue('E1', 'MSUD')
            ->setCellValue('F1', 'Estado')
            ->setCellValue('G1', 'P.Lectura')
            ->setCellValue('H1', 'F.Leche')
            ->setCellValue('I1', 'F.Muestra')
            ->setCellValue('J1', 'F.Control');
  $i=2;
  if ( count($content) > 0 ) {
      foreach ( $content as $row ) {
        $objPHPExcel->getActiveSheet(0)
            ->setCellValue('A'.$i, $row->Nombre)
            ->setCellValue('B'.$i, $row->Fecha_lectura)
            ->setCellValue('C'.$i, $row->Fenil)
            ->setCellValue('D'.$i, $row->Tir)
            ->setCellValue('E'.$i, $row->MSUD)
            ->setCellValue('F'.$i, $row->Estado)
            ->setCellValue('G'.$i, $row->prox_lectura)
            ->setCellValue('H'.$i, $row->fecha_entrega_leche)
            ->setCellValue('I'.$i, $row->fecha_toma_muestra)
            ->setCellValue('J'.$i, $row->fecha_control);
            $objPHPExcel->getActiveSheet(0)->getStyle('A1:J1')->applyFromArray($styleArray);
        $i++;
      }
  }
  $nom_archivo = 'Ficha-'.$fecha_paciente.'';
}
// Add some data

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nom_archivo.'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;