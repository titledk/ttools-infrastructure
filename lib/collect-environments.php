<?php
/**
 * Collect Environments
 * CLI script
 * WORK IN PROGRESS
 *
 * Run like this:
 * php ./ttools/infrastructure/lib/collect-environments.php
 *
 * @copyright September 2015 Anselm Christophersen / Title Web Solutions
 * @license MIT
 */


//are repositories placed in sub directories?
$subDirs = true;

$repoDir = dirname(dirname(dirname(dirname(__FILE__)))) . '/';

require_once $repoDir . 'ttools/infrastructure/thirdparty/php/Spyc.php';

$reposDir = dirname($repoDir) . '/';
if ($subDirs) {
	$reposDir = dirname($reposDir) . '/';
}

$configFile = $repoDir . 'ttools/config.yml';
$conf = spyc_load_file($configFile);


$servers = $conf['Environments'];
$sites = [];


function extractEnvironments($dir) {
	global $sites;

	echo "Extracting environments for $dir ... \n";

	$configFile = $dir . '/ttools/config.yml';
	if (file_exists($configFile)) {
		echo "config.yml found \n";
		$conf = spyc_load_file($configFile);
		foreach ($conf['Environments'] as $env => $data) {

			if (isset($data['Domain']) && strlen($data['Domain']) > 0) {
				$domain = $data['Domain'];

				$data['Environment'] = $env;
				$data['Project'] = $conf['Projectname'];

				$sites[$domain] = $data;
			}

		}
	}

	echo "\n";
}


echo "\n";
echo "Now extracting environments:";
echo "\n";
echo "\n";


foreach (new DirectoryIterator($reposDir) as $fileInfo) {
	if($fileInfo->isDir() && !$fileInfo->isDot()) {
		//echo $fileInfo->getFilename() . ' (' . $fileInfo->getPathname() . ")\n";

		if ($subDirs) {
			foreach (new DirectoryIterator($fileInfo->getPathname()) as $fileInfo) {
				if ($fileInfo->isDir() && !$fileInfo->isDot()) {
					//echo '  ' . $fileInfo->getFilename() . "\n";
					extractEnvironments($fileInfo->getPathname());
				}
			}
		} else {
			extractEnvironments($fileInfo->getPathname());
		}
	}
}



echo "Servers:";
echo "\n";

//foreach ($servers as $identifier => $data) {
//	echo $identifier . "\n";
//}
echo "\n";

var_dump($servers);


echo "Sites:";
echo "\n";

var_dump($sites);