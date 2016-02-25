<?php
/**
 * V3Site 
 *
 * V3Site Class to create V3ctor WareHouse Applications
 *
 * Copyright 2016 Jorge Alberto Ponce Turrubiates
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   V3Site
 * @package    V3Site
 * @copyright  Copyright 2016 Jorge Alberto Ponce Turrubiates
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    1.0.0, 2016-02-23
 * @author     Jorge Alberto Ponce Turrubiates (the.yorch@gmail.com)
 */
class V3Site
{
	/**
	 * MongoDb Admin User
	 * @var string
	 */
	private $_admin;

	/**
	 * MongoDb Password
	 * @var string
	 */
	private $_password;

	/**
	 * V3ctor Applications Directory
	 * @var string
	 */
	private $_appDir;

	/**
	 * Generated Password
	 * @var [type]
	 */
	private $_genPassword;

	/**
	 * Constructor of class
	 * 
	 * @param string $mongoAdmin MongoDb Administrator User
	 * @param string $mongoPwd   MongoDb Password
	 * @param string $appDir     V3ctor Applications Directory
	 */
	public function __construct($mongoAdmin, $mongoPwd, $appDir)
	{
		$this->_admin = $mongoAdmin;
		$this->_password = $mongoPwd;
		$this->_appDir = $appDir;

		if (! file_exists($appDir))
			die("The Applications Directory does not exists");
	}

	/**
	 * Create V3ctor Application in Directory
	 * 
	 * @param  string $appName Application Name
	 * @return boolean          
	 */
	public function makeApp($appName)
	{
		$retValue = $this->createDir($appName);

		if ($retValue){
			$this->createCfgFile($appName);
			$this->createIndexFile($appName);
			$this->createCfgJsFile($appName);
			$this->createComposerFile($appName);
			$this->createHtFile($appName);
		}

		return $retValue;
	}

	/**
	 * Create Directory of Application
	 * 
	 * @param  string $appName Application Name
	 * @return boolean
	 */
	private function createDir($appName)
	{
		if ($this->isReservedWord($appName))
			return false;

		$appDir = $this->_appDir . $appName;

		if (file_exists($appDir))
			return false;
		else{
			mkdir($appDir);

			chmod($appDir, 0755);

			return true;
		}
	}

	/**
	 * Check if Application Name is Reserved Word
	 * 
	 * @param  string $appName Application Name
	 * @return boolean
	 */
	private function isReservedWord($appName)
	{
		$reserved = array('admin', 'v3ctor', 'demo', 'yorch');

		if (in_array($appName, $reserved))
			return true;
		else
			return false;
	}

	/**
	 * Create config.php File
	 * 
	 * @param  string $appName Application Name
	 */
	private function createCfgFile($appName)
	{
		$appDir = $this->_appDir . $appName;
		$cfgFile = $appDir . "/config.php";

		$cfgPhp = fopen($cfgFile, "w") or die("Unable to open config.php file");

		// Generate Password
		$this->_genPassword = uniqid();
		$genKey = uniqid("", true); 

		$txt = '<?php' . "\n";
		fwrite($cfgPhp, $txt);

		$txt = '$hostname = "localhost";' . "\n";
		fwrite($cfgPhp, $txt);

		$txt = '$username = "' .  $appName . '";' . "\n";
		fwrite($cfgPhp, $txt);

		$txt = '$password = "' .  $this->_genPassword . '";' . "\n";
		fwrite($cfgPhp, $txt);

		$txt = '$dbname = "' .  $appName . '";' . "\n";
		fwrite($cfgPhp, $txt);

		$txt = '$port = 27017;' . "\n";
		fwrite($cfgPhp, $txt);

		$txt = '$key = "' . $genKey . '";' . "\n";
		fwrite($cfgPhp, $txt);

		$txt = '?>' . "\n";
		fwrite($cfgPhp, $txt);

		fclose($cfgPhp);
	}

	/**
	 * Create index.php File
	 * 
	 * @param  string $appName Application Name
	 */
	private function createIndexFile($appName)
	{
		$appDir = $this->_appDir . $appName;
		$indexFile = $appDir . "/index.php";

		$index = fopen($indexFile, "w") or die("Unable to open index.php file");

		$txt = '<?php' . "\n";
		fwrite($index, $txt);

		$txt = 'require "config.php";' . "\n";
		fwrite($index, $txt);

		$txt = 'require "vendor/autoload.php";' . "\n\n";
		fwrite($index, $txt);

		$txt = 'V3WareHouse::getInstance("v3Mongo", $hostname, $username, $password, $dbname, $port);' . "\n";
		fwrite($index, $txt);

		$txt = '$app = new V3Application($dbname, $key);' . "\n";
		fwrite($index, $txt);

		$txt = '$app->start();' . "\n";
		fwrite($index, $txt);

		$txt = '?>' . "\n";
		fwrite($index, $txt);

		fclose($index);
	}

	/**
	 * Create config.js File
	 * 
	 * @param  string $appName Application Name
	 */
	private function createCfgJsFile($appName)
	{
		$appDir = $this->_appDir . $appName;
		$cfgFile = $appDir . "/config.js";

		$cfgJs = fopen($cfgFile, "w") or die("Unable to open config.js file");

		$txt = 'use ' .  $appName . ";\n";
		fwrite($cfgJs, $txt);

		$txt = 'db.addUser("' .  $appName . '","' . $this->_genPassword . '");' . "\n";
		fwrite($cfgJs, $txt);

		fclose($cfgJs);
	}

	/**
	 * Create composer.json File
	 * 
	 * @param  string $appName Application Name
	 */
	private function createComposerFile($appName)
	{
		$appDir = $this->_appDir . $appName;
		$compFile = $appDir . "/composer.json";

		$composer = fopen($compFile, "w") or die("Unable to open composer.json file");

		$txt = '{"require": {' . "\n";
		fwrite($composer, $txt);

		$txt = '"slim/slim": "2.*",' . "\n";
		fwrite($composer, $txt);

		$txt = '"yorch/v3wh" : "dev-master",' . "\n";
		fwrite($composer, $txt);

		$txt = '"yorch/v3application" : "dev-master",' . "\n";
		fwrite($composer, $txt);

		$txt = '"monolog/monolog": "1.13.1",' . "\n";
		fwrite($composer, $txt);

		$txt = '"catfan/medoo": "dev-master"' . "\n";
		fwrite($composer, $txt);

		$txt = '}}' . "\n";
		fwrite($composer, $txt);

		fclose($composer);
	}

	/**
	 * Create .htaccess File
	 *
	 * @param  string $appName Application Name
	 */
	private function createHtFile($appName)
	{
		$appDir = $this->_appDir . $appName;
		$htFile = $appDir . "/.htaccess";

		$htaccess = fopen($htFile, "w") or die("Unable to open .htaccess file");

		$txt = 'RewriteEngine On' . "\n";
		fwrite($htaccess, $txt);

		$txt = 'RewriteCond %{REQUEST_FILENAME} !-d' . "\n";
		fwrite($htaccess, $txt);

		$txt = 'RewriteCond %{REQUEST_FILENAME} !-f' . "\n";
		fwrite($htaccess, $txt);

		$txt = 'RewriteRule ^ index.php [QSA,L]' . "\n";
		fwrite($htaccess, $txt);

		fclose($htaccess);
	}
}
?>