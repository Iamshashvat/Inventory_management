<?php

	require 'migrate.php';
	$file = checkProductStatus(1);

	$fp = fopen('migratedata.json', 'w');
	fwrite($fp, $file);
	fclose($fp);

	// print(getMinimumProductAmount(1));

?>