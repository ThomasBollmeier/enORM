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

require_once '../src/enorm/dbmodel/types.php';
require_once '../src/enorm/dbmodel/values.php';
require_once '../src/enorm/dbmodel/table.php';
require_once '../src/enorm/dbmodel/database.php';
use enorm\dbmodel as db;

class DbModelTest extends PHPUnit_Framework_TestCase {

	public function testTypes() {

		$type = db\IntegerType::get();
		$this->assertTrue($type !== NULL);
		$this->assertEquals(db\Type::INTEGER, $type->getCategory());
		$type2 = db\IntegerType::get();
		$this->assertTrue($type === $type2);

		$type = new db\DecimalType(5, 2);
		$this->assertTrue($type !== NULL);
		$this->assertEquals(db\Type::DECIMAL, $type->getCategory());
		$this->assertEquals(5, $type->getLength());
		$this->assertEquals(2, $type->getDigits());

		$type = db\BooleanType::get();
		$this->assertTrue($type !== NULL);
		$this->assertEquals(db\Type::BOOLEAN, $type->getCategory());
		$type2 = db\BooleanType::get();
		$this->assertTrue($type === $type2);

		$type = db\StringType::get();
		$this->assertTrue($type !== NULL);
		$this->assertEquals(db\Type::STRING, $type->getCategory());
		$type2 = db\StringType::get();
		$this->assertTrue($type === $type2);

		$type = new db\VarCharType(255);
		$this->assertTrue($type !== NULL);
		$this->assertEquals(db\Type::VARCHAR, $type->getCategory());
		$this->assertEquals(255, $type->getLength());

		$type = db\DateType::get();
		$this->assertTrue($type !== NULL);
		$this->assertEquals(db\Type::DATE, $type->getCategory());
		$type2 = db\DateType::get();
		$this->assertTrue($type === $type2);

		$type = db\TimeType::get();
		$this->assertTrue($type !== NULL);
		$this->assertEquals(db\Type::TIME, $type->getCategory());
		$type2 = db\TimeType::get();
		$this->assertTrue($type === $type2);

	}

	public function testValues() {

		$value = db\ValueFactory::createBoolean();
		$this->assertTrue($value !== NULL);
		$this->assertEquals(db\Type::BOOLEAN, $value->getType()->getCategory());
		$this->assertEquals(TRUE, $value->getContent());

		$value = db\ValueFactory::createInteger(42);
		$this->assertTrue($value !== NULL);
		$this->assertEquals(db\Type::INTEGER, $value->getType()->getCategory());
		$this->assertEquals(42, $value->getContent());

		$value = db\ValueFactory::createText("Hallo Welt");
		$this->assertTrue($value !== NULL);
		$this->assertEquals(db\Type::STRING, $value->getType()->getCategory());
		$this->assertEquals("Hallo Welt", $value->getContent());

		$value = db\ValueFactory::createText("Hallo Welt", 5);
		$this->assertTrue($value !== NULL);
		$this->assertEquals(db\Type::VARCHAR, $value->getType()->getCategory());
		$this->assertEquals("Hallo", $value->getContent());

        $value = db\ValueFactory::createDate(2013, 12, 31);
        $this->assertTrue($value !== NULL);
        $this->assertEquals(db\Type::DATE, $value->getType()->getCategory());
        $this->assertEquals(2013, $value->getYear());
        $this->assertEquals(12, $value->getMonth());
        $this->assertEquals(31, $value->getDay());

        $value = db\ValueFactory::createTime(12, 30, 45);
        $this->assertTrue($value !== NULL);
        $this->assertEquals(db\Type::TIME, $value->getType()->getCategory());
        $this->assertEquals(12, $value->getHour());
        $this->assertEquals(30, $value->getMinute());
        $this->assertEquals(45, $value->getSecond());

	}

    public function testTable() {

        $db = new db\Database("demo");

        $table = new db\Table($db, "persons");

        $table->addKeyField("id", db\IntegerType::get());
        try {
            $table->addKeyField("id", db\IntegerType::get());
            $excOccurred = FALSE;
        } catch (\Exception $error) {
            $excOccurred = TRUE;
        }
        $this->assertTrue($excOccurred);

        $table->addDataField("name", new db\VarCharType(50));
        try {
            $table->addDataField("name", new db\VarCharType(50));
            $excOccurred = FALSE;
        } catch (\Exception $error) {
            $excOccurred = TRUE;
        }
        $this->assertTrue($excOccurred);

        $table->addDataField("first_name", new db\VarCharType(50), TRUE);

        $this->assertEquals(3, count($table->getFields()));

        $record = $table->createRecord();

        $fieldnames = array("id", "name", "first_name");
        $i = 0;

        foreach ($record as $name => $value) {
            $this->assertEquals($fieldnames[$i++], $name);
        }

    }

}

