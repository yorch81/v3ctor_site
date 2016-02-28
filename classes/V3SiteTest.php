<?php
require "V3Site.class.php";
require "../config.php";

/**
 * V3SiteTest
 * 
 * V3SiteTest Test Example
 *
 * Copyright 2015 Jorge Alberto Ponce Turrubiates
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
 * @category   V3SiteTest
 * @package    V3SiteTest
 * @copyright  Copyright 2016 Jorge Alberto Ponce Turrubiates
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    1.0.0, 2016-02-27
 * @author     Jorge Alberto Ponce Turrubiates (the.yorch@gmail.com)
 */
class V3SiteTest extends PHPUnit_Framework_TestCase
{
	protected $app;

	protected $dir;

	protected function setUp() 
	{
    	$admin = $GLOBALS["admin"];
    	$password = $GLOBALS["password"];
    	$this->dir = $GLOBALS["dir"];	

    	$this->app =  new V3Site($admin, $password, $this->dir);
    }

    protected function tearDown() 
    {
        unset($this->app);
    }

    public function testApplication()
    {
    	$dirApp = $this->dir . "yorchi";

    	if (file_exists($dirApp))
    		rmdir($dirApp);

		if ($this->app->checkAvailability("yorchi"))
			$this->app->makeApp("yorchi");

		$this->assertTrue(file_exists($dirApp));
    }
}
?>