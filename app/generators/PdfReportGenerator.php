<?php

namespace App\Generators;

use App\Interfaces\ReportGeneratorInterface;
use DateTime;

class PdfReportGenerator implements ReportGeneratorInterface
{
	public function generate(string $action, array $data = []): void
	{
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->AddPage();
		$pdf->setFont('helvetica', '', 12);
		$pdf->Cell(0, 10, 'Posts report', 0, 1, 'C');
		$pdf->Ln();

		$header = ["ID", "Title", "Body", "Date"];
		$widths = [10, 50, 60, 45];
		for ($i = 0; $i < count($header); $i++) {
			$pdf->Cell($widths[$i], 10, $header[$i], 1, 0, 'C');
		}
		$pdf->Ln();

		foreach ($data as $row) {
			$created_at = new DateTime($row['created_at']);

			$pdf->Cell($widths[0], 10, $row['id'], 1, 0, 'L');
			$pdf->Cell($widths[1], 10, $row['title'], 1, 0, 'L');
			$pdf->MultiCell($widths[2], 10, substr($row['body'], 0, 60), 1, 'L', 0, 1);
			$pdf->Cell($widths[3], 10, $created_at->format('d.m.Y H:i:s'), 1, 1, 'C');
		}

		$validActions = ['View', 'Download'];
		$action = filter_input(INPUT_GET, 'action', FILTER_UNSAFE_RAW);
		if (!$action || !in_array($action, $validActions)) {
			http_response_code(400);
			die('Invalid request');
		}

		$filename = 'report.pdf';

		if ($action === 'View') {
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="' . $filename . '"');
			$mode = 'I';
		} elseif ($action === 'Download') {
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			$mode = 'D';
		}

		$pdf->Output($filename, $mode);
	}
}