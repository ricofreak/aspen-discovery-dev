<?php

$enableKoha = strtolower(getenv('ENABLE_KOHA'));

if ($enableKoha !== 'yes') {
	echo "%    --> Koha was not enabled\n";
	die(0);
}

//Load Koha's Database

$variables = [
	'sitename' => getenv('SITE_NAME'),
	'ilsUrl' => getenv('KOHA_OPAC_URL'),
	'databaseUser' => getenv('DATABASE_USER'),
	'databasePassword' => getenv('DATABASE_PASSWORD'),
	'databaseName' => getenv('DATABASE_NAME'),
	'databaseHost' => getenv('DATABASE_HOST') ?? 'localhost',
	'databasePort' => getenv('DATABASE_PORT') ?? 3306,
	'ilsDatabaseHost' => getenv('KOHA_DATABASE_HOST'),
	'ilsDatabasePort' => getenv('KOHA_DATABASE_PORT'),
	'ilsDatabaseUser' => getenv('KOHA_DATABASE_USER'),
	'ilsDatabasePassword' => getenv('KOHA_DATABASE_PASSWORD'),
	'ilsDatabaseName' => getenv('KOHA_DATABASE_NAME'),
	'ilsDatabaseTimezone' => getenv('KOHA_DATABASE_TIMEZONE') ?? 'US/Central',
	'ilsClientId' => getenv('KOHA_CLIENT_ID'),
	'ilsClientSecret' => getenv('KOHA_CLIENT_SECRET'),
];

$databaseName = $variables['databaseName'];
$databasePort = $variables['databasePort'];
$databaseHost = $variables['databaseHost'];
$databaseUser = $variables['databaseUser'];
$databasePassword = $variables['databasePassword'];
$databaseDsn = "mysql:host=$databaseHost;port=$databasePort;dbname=$databaseName";

$aspenDir = '/usr/local/aspen-discovery';

// Capture any error as ErrorException
set_error_handler("customErrorHandler");

// Prepare test statement
try {
	$statement = "SELECT driver FROM account_profiles WHERE driver = 'Koha';";
	$aspenDatabase = new PDO($databaseDsn, $databaseUser, $databasePassword);
	$updateUserStmt = $aspenDatabase->prepare($statement);
} catch (PDOException $e) {
	echo "%   ERROR MESSAGE : " . $e->getMessage() . "\n";
	echo "%   IN : " . $e->getFile() . ":" . $e->getLine() . "\n";
	die(1);
}

// Check if ils tables have already been initialized

try {
	$updateUserStmt->execute();
	$result = $updateUserStmt->fetchAll();
	if (empty($result)) {
		throw new PDOException();
	}
	echo "%    --> Ils tables has already been initialized!\n";
	die(0);
} catch (PDOException $e) {
	// Ils table is still empty
}

try {
// Attempt to get the system's temp directory
	$tmp_dir = rtrim(sys_get_temp_dir(), "/");
	echo("%    --> Loading Koha information to database...\r\n");
	copy("$aspenDir/install/koha_connection.sql", "$tmp_dir/koha_connection_{$variables['sitename']}.sql");
	replaceVariables("$tmp_dir/koha_connection_{$variables['sitename']}.sql", $variables);
	exec("mysql -u{$variables['databaseUser']} -p\"{$variables['databasePassword']}\" -h{$variables['databaseHost']} -P{$variables['databasePort']} {$variables['databaseName']} < $tmp_dir/koha_connection_{$variables['sitename']}.sql");
} catch (Exception $e) {
	echo "%   ERROR MESSAGE : " . $e->getMessage() . "\n";
	echo "%   IN : " . $e->getFile() . ":" . $e->getLine() . "\n";
	die(1);
}

echo "%    --> Koha link established successfully\n";




function customErrorHandler(int $errno, string $errstr, string $errfile, int $errline): void {
	if (!(error_reporting() & $errno)) {
		// This error code is not included in error_reporting.
		return;
	}
	if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED) {
		// Do not throw an Exception for deprecation warnings as new or unexpected
		// deprecations would break the application.
		return;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
function replaceVariables($filename, $variables): void {
	$contents = file($filename);
	$fHnd = fopen($filename, 'w');
	foreach ($contents as $line) {
		foreach ($variables as $name => $value) {
			$line = str_replace('{' . $name . '}', $value, $line);
		}
		fwrite($fHnd, $line);
	}
	fclose($fHnd);
}