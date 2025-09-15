<?php

namespace App\Interfaces;

interface ReportGeneratorInterface
{
	public function generate(string $action, array $data = []): void;
}