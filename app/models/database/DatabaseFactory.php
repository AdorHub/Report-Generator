<?php

namespace App\Models\Database;

use App\Models\Database\MysqlDatabase;
use App\Interfaces\DatabaseInterface;

class DatabaseFactory
{
	public static function create(): DatabaseInterface
	{
		switch($_ENV['DB_DRIVER']) {
			case 'mysql':
				return MysqlDatabase::getInstance();
				break;
			default:
				throw new \Exception("Тип базы данных не поддерживается.");
		}
	}
}