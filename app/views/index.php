<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<title>Report Generator PHP</title>
	<link rel="stylesheet" src="/public/assets/css/style.css">
</head>

<body>
	<h1>Export data to Excel</h1>
	<p>Select the desired action:</p>
	<form action="/public/index.php" method="GET">
		<input type="hidden" name="type" value="excel">
		<input type="submit" name="action" value="View">
		<input type="submit" name="action" value="Download">
	</form>

	<h1>Export data to PDF</h1>
	<p>Select the desired action:</p>
	<form action="/public/index.php" method="GET">
		<input type="hidden" name="type" value="pdf">
		<input type="submit" name="action" value="View">
		<input type="submit" name="action" value="Download">
	</form>
</body>

</html>