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

use tbollmeier\enorm\pdo\ConnectionFactory;
use tbollmeier\enorm\dbapi as api;
use tbollmeier\enorm\dbmodel as model;


class DbConnectionTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->testDb = createTestDatabase();
        $this->factory = new ConnectionFactory();
    }

    public function testConnection()
    {
        $conn = $this->factory->connectMySql(
            "localhost",
            $this->testDb->name,
            "enormtester",
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
            new api\ReadTargetField(
                $persons->id,
                "PersonId"
            ),
            new api\ReadTargetField(
                $persons->name,
                "FamilyName"
            )
        );

        $cursor = $conn->read(
            $persons,
            $targets,
            new api\FieldCondition(
                $persons->first_name,
                api\FieldOperator::EQ,
                new model\StringValue("Herbert")
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
