<?php

namespace App\Context;

use App\Interfaces\ReportGeneratorInterface;

class ReportContext
{
	private ReportGeneratorInterface $generator;

	public function __construct(ReportGeneratorInterface $generator)
	{
		$this->generator = $generator;
	}

	public function createReport(string $action, array $data = []): void
	{
		$this->generator->generate($action, $data);
	}
}