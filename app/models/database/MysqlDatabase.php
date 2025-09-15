<?php

namespace App\Models\Database;

use App\Interfaces\DatabaseInterface;
use Exception;
use Pdo;

class MysqlDatabase implements DatabaseInterface
{
	private ?PDO $pdo = null;
	private static ?MysqlDatabase $instance = null;

	private function __construct()
	{
		global $logger;
		try {
			$dsn = sprintf(
				"%s:host=%s;dbname=%s",
				$_ENV['DB_DRIVER'],
				$_ENV['DB_HOST'],
				$_ENV['DB_NAME']
			);
			$this->pdo = new Pdo($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$logger->info('Успешное подключение к базе данных');
		} catch (\PDOException $e) {
			$logger->error($e->getMessage());
			throw new Exception('Неуспешное подключение к базе данных');
		}
	}

	public static function getInstance(): MysqlDatabase
	{
		if (!isset(self::$instance)) {
			self::$instance = new MysqlDatabase;
		}
		return self::$instance;
	}

	public function getAll(string $tableName): array
	{
		$stmt = $this->pdo->prepare("SELECT * FROM $tableName");
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
}