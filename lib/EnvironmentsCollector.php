<?php

/**
 * EnvironmentsCollector
 * WORK IN PROGRESS
 * Collects environment info (ttools/config.yml) from all projects stored in a certain ocation
 *
 * @author Anselm Christophersen <ac@anselm.dk>
 * @date   September 2015
 * @license MIT
 */
class EnvironmentsCollector {

	/**
	 * Log to CLI
	 * @var bool
	 */
	private $doLog = false;

	/**
	 * If subdir mode is set to true we expect sub directories inside of your git-repo directory
	 * (the place where all your git repos should be stored)
	 * If not, we expect them all to be stored in one directory
	 *
	 * @var bool
	 */
	private $subDirMode = true;

	/**
	 * @var string
	 */
	private $repoDir = '';

	/**
	 * @var string
	 */
	private $reposDir = '';

	/**
	 * @var array
	 */
	private $localConf = [];

	/**
	 * @var array
	 */
	private $collectedServers = [];

	/**
	 * @var array
	 */
	private $collectedSites = [];

	/**
	 * @var array
	 */
	private $collectedProjects = [];


	public function __construct() {

		$this->initRepoDir();
		$this->initReposDir();

		$configFile = $this->repoDir . 'ttools/config.yml';
		$this->localConf = spyc_load_file($configFile);

	}

	private function initRepoDir() {
		$this->repoDir = dirname(dirname(dirname(dirname(__FILE__)))) . '/';
		require_once $this->repoDir . 'ttools/infrastructure/thirdparty/php/Spyc.php';
	}
	private function initReposDir() {
		$this->reposDir = dirname($this->repoDir) . '/';
		if ($this->subDirMode) {
			$this->reposDir = dirname($this->reposDir) . '/';
		}
	}

	/**
	 * Collecting environments
	 * Looping through all environments to extract the info
	 */
	public function collect() {
		//Setting servers
		$this->collectedServers = $this->localConf['Environments'];

		if ($this->doLog) {
			echo "\n";
			echo "Now extracting environments:";
			echo "\n";
			echo "\n";
		}

		foreach (new DirectoryIterator($this->reposDir) as $fileInfo) {
			if($fileInfo->isDir() && !$fileInfo->isDot()) {
				//echo $fileInfo->getFilename() . ' (' . $fileInfo->getPathname() . ")\n";

				if ($this->subDirMode) {
					foreach (new DirectoryIterator($fileInfo->getPathname()) as $fileInfo) {
						if ($fileInfo->isDir() && !$fileInfo->isDot()) {
							//echo '  ' . $fileInfo->getFilename() . "\n";
							$this->extractEnvironments($fileInfo->getPathname());
						}
					}
				} else {
					$this->extractEnvironments($fileInfo->getPathname());
				}
			}
		}
	}

	/**
	 * Extracting the ttools/config.yml file for a specific directory
	 * @param $dir
	 */
	private function extractEnvironments($dir) {

		if ($this->doLog) {
			echo "Extracting environments for $dir ... \n";
		}

		$configFile = $dir . '/ttools/config.yml';
		if (file_exists($configFile)) {
			if ($this->doLog) {
				echo "config.yml found \n";
			}
			$conf = spyc_load_file($configFile);
			$this->collectedProjects[] = $conf;
			foreach ($conf['Environments'] as $env => $data) {

				if (isset($data['Domain']) && strlen($data['Domain']) > 0) {
					$domain = $data['Domain'];

					$data['Environment'] = $env;
					$data['Project'] = $conf['Projectname'];

					$this->collectedSites[$domain] = $data;
				}

			}
		}
		if ($this->doLog) {
			echo "\n";
		}
	}


	/**
	 * @param $bol
	 */
	public function setSubDirMode($bol) {
		$this->subDirMode = $bol;
	}

	/**
	 * @param $bol
	 */
	public function setDoLog($bol) {
		$this->doLog = $bol;
	}

	/**
	 * @return array
	 */
	public function getCollectedSites() {
		return $this->collectedSites;
	}

	/**
	 * @return array
	 */
	public function getCollectedServers() {
		return $this->collectedServers;
	}

	/**
	 * @return array
	 */
	public function getCollectedProjects() {
		return $this->collectedProjects;
	}


}




