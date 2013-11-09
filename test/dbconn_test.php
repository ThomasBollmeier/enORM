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

require_once("dbsetup.php");
require_once("enorm/pdo/ConnectionFactory.php");
require_once("enorm/dbapi/read_target.php");
require_once("enorm/dbapi/field_condition.php");
require_once("enorm/dbmodel/values.php");

class DbConnectionTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->testDb = createTestDatabase();
        $this->factory = new \enorm\pdo\ConnectionFactory();
    }

    public function testConnection()
    {
        $conn = $this->factory->connectMySql(
            "localhost",
            $this->testDb->name,
            "root",
            ""
        );

        $this->assertTrue($conn->isOK());

        $persons = $this->testDb->getTable("persons");

        $conn->delete($persons);

        $record = $persons->createRecord();
        $record->first_name = "Herbert";
        $record->name = "Testmüller";

        $this->assertTrue($conn->create($persons, $record));

        $targets = array(
            new \enorm\dbapi\ReadTargetField(
                $persons->id,
                "PersonId"
            ),
            new \enorm\dbapi\ReadTargetField(
                $persons->name,
                "FamilyName"
            )
        );

        $cursor = $conn->read(
            $persons,
            $targets,
            new \enorm\dbapi\FieldCondition(
                $persons->first_name,
                \enorm\dbapi\FieldOperator::EQ,
                new \enorm\dbmodel\StringValue("Herbert")
            )
        );

        $numRows = 0;
        while ($record = $cursor->getNextRecord()) {
            $this->assertTrue($record->PersonId > 0);
            $this->assertEquals(
                "Testmüller",
                $record->FamilyName
            );
            $numRows++;
        }

        $this->assertEquals(1, $numRows);

        $conn->delete($persons);

        unset($conn);
    }

    private $testDb;
    private $factory;

}
