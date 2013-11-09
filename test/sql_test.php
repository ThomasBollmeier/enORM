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

require_once 'enorm/dbmodel/types.php';
require_once 'enorm/dbmodel/values.php';
require_once 'enorm/dbmodel/table.php';
require_once 'enorm/dbmodel/database.php';
require_once 'enorm/dbmodel/record.php';
use enorm\dbmodel as db;

require_once 'enorm/dbapi/field_condition.php';
require_once 'enorm/dbapi/conjunction.php';
require_once 'enorm/dbapi/connection.php';
require_once 'enorm/dbapi/read_target.php';
use enorm\dbapi as api;

require_once 'enorm/pdo/SqlBuilder.php';

class SqlBuilderTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $table = new db\Table(new db\Database("test"), "persons");
        $table->addKeyField("id", db\IntegerType::get());
        $table->addDataField("name", new db\VarCharType(50));
        $table->addDataField("first_name", new db\VarCharType(50));
        $table->addDataField("birthday", db\DateType::get());
        $table->addDataField("is_developer", db\BooleanType::get());

        $this->table = $table;
    }

    public function testStatements()
    {

        $builder = new \enorm\pdo\SqlBuilder();

        $record = $this->table->createRecord();
        $record->id = 42;
        $record->first_name = "Theodore";
        $record->name = "Ballmiller";

        $this->assertEquals(
            "INSERT INTO persons (id, name, first_name) VALUES" .
            " (42, 'Ballmiller', 'Theodore')",
            $builder->createStmt($this->table, $record)
        );

        $record->birthday = '1966-07-11';
        $this->assertEquals(
            "INSERT INTO persons (id, name, first_name, birthday) VALUES" .
            " (42, 'Ballmiller', 'Theodore', '1966-07-11')",
            $builder->createStmt($this->table, $record)
        );

        $condFirstName = new api\FieldCondition(
            $this->table->getFieldByName("first_name"),
            api\FieldOperator::EQ,
            $record->getValue("first_name")
        );

        $condSurname = new api\FieldCondition(
            $this->table->getFieldByName("name"),
            api\FieldOperator::EQ,
            $record->getValue("name")
        );

        $record->is_developer = true;
        $condition = new api\Conjunction($condFirstName, $condSurname);

        $fieldValues = array();
        $fieldValue = new api\FieldValue(
            $this->table->getFieldByName("birthday"),
            $record->getValue("birthday")
        );
        array_push($fieldValues, $fieldValue);
        $fieldValue = new api\FieldValue(
            $this->table->getFieldByName("is_developer"),
            $record->getValue("is_developer")
        );
        array_push($fieldValues, $fieldValue);

        $this->assertEquals(
            "UPDATE persons SET\n" .
            "\tbirthday = '1966-07-11'\n" .
            "\tis_developer = 1\n" .
            "WHERE (first_name = 'Theodore') AND (name = 'Ballmiller')",
            $builder->updateStmt(
                $this->table,
                $fieldValues,
                $condition
            )
        );

        $idCondition = new api\FieldCondition(
            $this->table->getFieldByName("id"),
            api\FieldOperator::EQ,
            new db\IntegerValue(42)
        );

        $this->assertEquals(
            "DELETE FROM persons WHERE id = 42",
            $builder->deleteStmt(
                $this->table,
                $idCondition
            )
        );

        $targets = array();

        $this->assertEquals(
            "SELECT * FROM persons WHERE id = 42",
            $builder->selectStmt(
                $this->table,
                $targets,
                $idCondition
            )
        );

        array_push(
            $targets,
            new api\ReadTargetField(
                $this->table->getFieldByName("name"),
                "FamilyName"
            )
        );

        $this->assertEquals(
            "SELECT name AS FamilyName FROM persons WHERE id = 42",
            $builder->selectStmt(
                $this->table,
                $targets,
                $idCondition
            )
        );

    }

    private $table;

}

