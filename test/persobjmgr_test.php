<?php
/*
 * Copyright 2013-2016 Thomas Bollmeier <entwickler@tbollmeier.de>
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

require_once __DIR__ . '/dbsetup.php';

use tbollmeier\enorm\core\PersObjManager;
use tbollmeier\enorm\core\PersistentObject;
use tbollmeier\enorm\core\TableDepKeyMaps;
use tbollmeier\enorm\dbapi\ReadTargetField;
use tbollmeier\enorm\pdo\ConnectionFactory;


class Person implements PersistentObject {

    const HEADER_TABLE = "persons";
    const HOBBIES_TABLE = "hobbies";

    public $FirstName;
    public $FamilyName;
    public $Hobbies = [];

    public static function getHeaderTabInfo()
    {
        return array(self::HEADER_TABLE, array("id"));
    }

    public static function getKeyMapping()
    {
        $keyMapping = [];

        $keyMaps = new TableDepKeyMaps(self::HEADER_TABLE);
        $keyMaps->addMap('person_id', 'id');
        $keyMapping[self::HOBBIES_TABLE] = $keyMaps;

        return $keyMapping;
    }

    /**
     * Set the instance's attributes from the
     * database content
     *
     * @param $rowsPerTable : array of rows per table (name)
     */
    public function setAttrsFromDbData($rowsPerTable)
    {
        $headerRows = $rowsPerTable[self::HEADER_TABLE];
        $this->header = $headerRows[0];
        $this->FirstName = $this->header->first_name;
        $this->FamilyName = $this->header->name;

        $this->hobbyRows = $rowsPerTable[self::HOBBIES_TABLE];
        asort($this->hobbyRows);
        $this->Hobbies = array();
        foreach ($this->hobbyRows as $row) {
            array_push($this->Hobbies, $row->name);
        }

    }

    /**
     * Prepare save operation by returning
     * the row data that have to be saved for
     * this instance
     *
     * @return array of rows per database table (name)
     */
    public function prepareSave()
    {
        // TODO: Implement prepareSave() method.
    }

    private $header = null;
    private $hobbyRows;

}

class PersObjManagerTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        PersObjManagerTest::$currInst = $this;
        $this->testDb = createTestDatabase();
        $this->factory = new ConnectionFactory();
    }

    public function testManager()
    {

        $conn = $this->factory->connectMySql(
            "localhost",
            $this->testDb->name,
            "enormtester",
            ""
        );
        $this->assertTrue($conn->isOK());

        $persons = $this->testDb->getTable("persons");
        $hobbies = $this->testDb->getTable("hobbies");

        // Clean up
        $conn->delete($persons);
        $conn->delete($hobbies);

        // Insert test record(s)
        $record = $persons->createRecord();
        $record->first_name = "Herbert";
        $record->name = "Testmüller";

        $this->assertTrue($conn->create($persons, $record));

        // Get Id:
        $cursor = $conn->read($persons, array(new ReadTargetField($persons->id, "PersonId")));
        $id = $cursor->getNextRecord()->PersonId;

        $record = $hobbies->createRecord();
        $record->person_id = $id;
        $record->hobby_num = 1;
        $record->name = "Laufen";
        $this->assertTrue($conn->create($hobbies, $record));

        $record->hobby_num = 2;
        $record->name = "Android-Programmierung";
        $this->assertTrue($conn->create($hobbies, $record));

        // Load...
        $manager = PersObjManager::getManager("\\Person");

        $this->assertEquals(
            "persons",
            $manager->getHeaderTableName()
        );

        $this->personObj = null;

        $manager->load(
            $conn,
            $this->testDb,
            array("id" => $id),
            function ($obj) {
                $this->personObj = $obj;
            }
        );

        $this->assertEquals(
            "Herbert",
            $this->personObj->FirstName
        );
        $this->assertEquals(
            "Testmüller",
            $this->personObj->FamilyName
        );
        $this->assertEquals(
            array(
                "Laufen", "Android-Programmierung"
            ),
            $this->personObj->Hobbies
        );

        unset($conn);
    }

    public static function onPersonLoaded($obj)
    {
        PersObjManagerTest::$currInst->personObj = $obj;
    }

    static private $currInst;
    private $testDb;
    private $factory;
    private $personObj;

}
