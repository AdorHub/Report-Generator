<?php

namespace App\Controllers;

use App\Context\ReportContext;
use App\Generators\ExcelReportGenerator;
use App\Generators\PdfReportGenerator;
use App\Models\Database\DatabaseFactory;
use Exception;

class IndexController 
{
	public function handleRequest(string $type, string $action)
	{
		if (empty($type) && empty($action)) {
			include __DIR__ . '/../views/index.php';
			return;
		}
		if ($type === 'excel') {
			$db = DatabaseFactory::create();
			$data = $db->getAll('posts');
			$generator = new ReportContext(new ExcelReportGenerator);
			$generator->createReport($action, $data);
		} else if ($type === 'pdf') {
			$db = DatabaseFactory::create();
			$data = $db->getAll('posts');
			$generator = new ReportContext(new PdfReportGenerator);
			$generator->createReport($action, $data);
		} else {
			throw new Exception('Неподдерживаемый тип отчёта');
		}
	}
}