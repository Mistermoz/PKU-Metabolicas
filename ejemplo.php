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


require 'PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

/** Include PHPExcel */
require_once '../../../wp-blog-header.php';

// Set document properties
$spreadsheet->getProperties()->setCreator("Pku Movil")
               ->setLastModifiedBy("Pku Movil")
               ->setTitle("Office 2007 XLSX Historial Document")
               ->setSubject("Office 2007 XLSX Historial Document")
               ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
               ->setKeywords("office 2007 openxml php")
               ->setCategory("Result file");

// Style Cells
$styleArray = array(
    'font' => array(
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ),
    'borders' => array(
        'allborders' => array(
          'style' => Border::BORDER_THIN,
          'color' => array('argb' => 'FF000000'),
        )
      ),
    'fill' => array(
        'type' => Fill::FILL_GRADIENT_LINEAR,
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
  $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Fenil')
            ->setCellValue('B1', 'Tir')
            ->setCellValue('C1', 'Leu')
            ->setCellValue('D1', 'Val')
            ->setCellValue('E1', 'Iso')
            ->setCellValue('F1', 'Allo')
            ->setCellValue('G1', 'F.Lectura')
            ->setCellValue('H1', 'Estado')
            ->setCellValue('I1', 'P.Lectura')
            ->setCellValue('J1', 'F.Leche')
            ->setCellValue('K1', 'F.Muestra')
            ->setCellValue('L1', 'F.Control');
  $i=2;
  if ( count($content) > 0 ) {
      foreach ( $content as $row ) {
        $spreadsheet->getActiveSheet(0)
            ->setCellValue('A'.$i, $row->Fenil)
            ->setCellValue('B'.$i, $row->Tir)
            ->setCellValue('C'.$i, $row->Leu)
            ->setCellValue('D'.$i, $row->Val)
            ->setCellValue('E'.$i, $row->Iso)
            ->setCellValue('F'.$i, $row->Allo)
            ->setCellValue('G'.$i, $row->Fecha_lectura)
            ->setCellValue('H'.$i, $row->Estado)
            ->setCellValue('I'.$i, $row->prox_lectura)
            ->setCellValue('J'.$i, $row->fecha_entrega_leche)
            ->setCellValue('K'.$i, $row->fecha_toma_muestra)
            ->setCellValue('L'.$i, $row->fecha_control);
        $spreadsheet->getActiveSheet(0)->getStyle('A1:L1')->applyFromArray($styleArray);
        $i++;
      }
  }
  $hoy = date("d-m-y");
  $nom_archivo = 'Ficha-'.$nom_paciente.'-'.$hoy;
} else {
  $query = "SELECT * FROM paciente WHERE F_lectura='".$fecha_paciente."' ORDER BY Nombre ASC";
  $content = $dbh->get_results( $query );
  $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nombre')
            ->setCellValue('B1', 'Rut')
            ->setCellValue('C1', 'Fecha Lectura')
            ->setCellValue('D1', 'Fenil')
            ->setCellValue('E1', 'Tir')
            ->setCellValue('F1', 'Leu')
            ->setCellValue('G1', 'Val')
            ->setCellValue('H1', 'Iso')
            ->setCellValue('I1', 'Allo')
            ->setCellValue('J1', 'Estado')
            ->setCellValue('K1', 'P.Lectura')
            ->setCellValue('L1', 'F.Leche')
            ->setCellValue('M1', 'F.Muestra')
            ->setCellValue('N1', 'F.Control');
  $i=2;
  if ( count($content) > 0 ) {
      foreach ( $content as $row ) {
        $spreadsheet->getActiveSheet(0)
            ->setCellValue('A'.$i, $row->Nombre)
            ->setCellValue('B'.$i, $row->Rut)
            ->setCellValue('C'.$i, $row->Fecha_lectura)
            ->setCellValue('D'.$i, $row->Fenil)
            ->setCellValue('E'.$i, $row->Tir)
            ->setCellValue('F'.$i, $row->Leu)
            ->setCellValue('G'.$i, $row->Val)
            ->setCellValue('H'.$i, $row->Iso)
            ->setCellValue('I'.$i, $row->Allo)
            ->setCellValue('J'.$i, $row->Estado)
            ->setCellValue('K'.$i, $row->prox_lectura)
            ->setCellValue('L'.$i, $row->fecha_entrega_leche)
            ->setCellValue('M'.$i, $row->fecha_toma_muestra)
            ->setCellValue('N'.$i, $row->fecha_control);
            $spreadsheet->getActiveSheet(0)->getStyle('A1:N1')->applyFromArray($styleArray);
        $i++;
      }
  }
  $nom_archivo = 'Ficha-'.$fecha_paciente.'';
}
// Add some data

// Rename worksheet
$title = mb_strimwidth($nom_paciente, 0, 31, "");
$spreadsheet->getActiveSheet()->setTitle($title);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nom_archivo.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;