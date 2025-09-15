<?php

namespace App\Generators;

use App\Interfaces\ReportGeneratorInterface;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelReportGenerator implements ReportGeneratorInterface
{
	public function generate(string $action, array $data = []): void
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$header = ['ID', 'Title', 'Body', 'Date'];
		$sheet->fromArray($header, null, 'A1');
		$rowNumber = 2;
		foreach ($data as $row) {
			$sheet->setCellValue('A' . $rowNumber, $row['id']);
			$sheet->setCellValue('B' . $rowNumber, $row['title']);
			$sheet->setCellValue('C' . $rowNumber, $row['body']);
			$date = new DateTime($row['created_at']);
			$sheet->setCellValue('D' . $rowNumber, $date->format('d.m.Y H:i:s'));
			$rowNumber++;
		}
		foreach (range('A', 'D') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}
		$sheet->getStyle('A1:D1')->applyFromArray([
			'font' => ['bolt' => true],
			'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
		]);

		$validActions = ['View', 'Download'];
		$action = filter_input(INPUT_GET, 'action', FILTER_UNSAFE_RAW);
		if (!$action || !in_array($action, $validActions)) {
			http_response_code(400);
			die('Invalid request');
		}

		$filename = 'excel-report.xlsx';
		if ($action === 'View') {
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: inline; filename="' . $filename . '"');
		} elseif ($action === 'Download') {
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
		}

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		if ($action === 'View') {
			$writer->save('php://output');
		} else if ($action === 'Download') {
			ob_start();
			$writer->save('php://output');
			ob_end_flush();
		} else {
			echo 'Wrong action!';
		}
	}
}
