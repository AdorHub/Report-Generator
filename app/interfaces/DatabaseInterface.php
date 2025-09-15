<?php

namespace App\Interfaces;

interface DatabaseInterface
{
	public function getAll(string $tableName);
}
