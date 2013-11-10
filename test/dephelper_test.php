<?php
/*
 * Copyright 2013 Thomas Bollmeier <tbollmeier@web.de>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
set_include_path("../src" . PATH_SEPARATOR . get_include_path());

require_once("enorm/core/PersistentObject.php");
use \enorm\core\TableDependencyHelper;
use \enorm\core\KeyMapInfo;

class DepHelperTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
    }

    public function testDependencies()
    {
        $keyMapsPerTable = array();

        $keyMaps = array();
        $keyMap = new KeyMapInfo();
        $keyMap->sourceTabName = "order";
        $keyMap->sourceFieldName = "id";
        $keyMap->targetFieldName = "parent_id";
        array_push($keyMaps, $keyMap);

        $keyMapsPerTable["items"] = $keyMaps;

        $keyMaps = array();
        $keyMap = new KeyMapInfo();
        $keyMap->sourceTabName = "items";
        $keyMap->sourceFieldName = "id";
        $keyMap->targetFieldName = "item_id";
        array_push($keyMaps, $keyMap);

        $keyMapsPerTable["item_details"] = $keyMaps;

        $helper = new TableDependencyHelper($keyMapsPerTable);

        $this->assertEquals(
            0,
            $helper->getLevel("order")
        );

        $this->assertEquals(
            1,
            $helper->getLevel("items")
        );

        $this->assertEquals(
            2,
            $helper->getLevel("item_details")
        );

        $this->assertEquals(
            array("items", "item_details"),
            $helper->getTablesSortedByLevel()
        );

    }

}
