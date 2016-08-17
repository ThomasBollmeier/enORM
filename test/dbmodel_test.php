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

use tbollmeier\enorm\dbmodel as model;


class DbModelTest extends PHPUnit_Framework_TestCase
{

    public function testTypes()
    {

        $type = model\IntegerType::get();
        $this->assertTrue($type !== NULL);
        $this->assertEquals(model\Type::INTEGER, $type->getCategory());
        $type2 = model\IntegerType::get();
        $this->assertTrue($type === $type2);

        $type = new model\DecimalType(5, 2);
        $this->assertTrue($type !== NULL);
        $this->assertEquals(model\Type::DECIMAL, $type->getCategory());
        $this->assertEquals(5, $type->getLength());
        $this->assertEquals(2, $type->getDigits());

        $type = model\BooleanType::get();
        $this->assertTrue($type !== NULL);
        $this->assertEquals(model\Type::BOOLEAN, $type->getCategory());
        $type2 = model\BooleanType::get();
        $this->assertTrue($type === $type2);

        $type = model\StringType::get();
        $this->assertTrue($type !== NULL);
        $this->assertEquals(model\Type::STRING, $type->getCategory());
        $type2 = model\StringType::get();
        $this->assertTrue($type === $type2);

        $type = new model\VarCharType(255);
        $this->assertTrue($type !== NULL);
        $this->assertEquals(model\Type::VARCHAR, $type->getCategory());
        $this->assertEquals(255, $type->getLength());

        $type = model\DateType::get();
        $this->assertTrue($type !== NULL);
        $this->assertEquals(model\Type::DATE, $type->getCategory());
        $type2 = model\DateType::get();
        $this->assertTrue($type === $type2);

        $type = model\TimeType::get();
        $this->assertTrue($type !== NULL);
        $this->assertEquals(model\Type::TIME, $type->getCategory());
        $type2 = model\TimeType::get();
        $this->assertTrue($type === $type2);

    }

    public function testValues()
    {

        $value = new model\BooleanValue(TRUE);
        $this->assertTrue($value !== NULL);
        $this->assertEquals(model\Type::BOOLEAN, $value->getType()->getCategory());
        $this->assertEquals(TRUE, $value->getContent());
        $value->setContent(FALSE);
        $this->assertEquals(FALSE, $value->getContent());

        $value = new model\IntegerValue(42);
        $this->assertTrue($value !== NULL);
        $this->assertEquals(model\Type::INTEGER, $value->getType()->getCategory());
        $this->assertEquals(42, $value->getContent());
        $value->setContent(666);
        $this->assertEquals(666, $value->getContent());

        $value = new model\StringValue("Hallo Welt");
        $this->assertTrue($value !== NULL);
        $this->assertEquals(model\Type::STRING, $value->getType()->getCategory());
        $this->assertEquals("Hallo Welt", $value->getContent());

        $value = new model\VarCharValue(new model\VarCharType(5), "Hallo Welt");
        $this->assertTrue($value !== NULL);
        $this->assertEquals(model\Type::VARCHAR, $value->getType()->getCategory());
        $this->assertEquals("Hallo", $value->getContent());
        $value->setContent("ABCDEFG");
        $this->assertEquals("ABCDE", $value->getContent());

        $value = new model\DateValue(2013, 12, 31);
        $this->assertTrue($value !== NULL);
        $this->assertEquals(model\Type::DATE, $value->getType()->getCategory());
        $this->assertEquals(2013, $value->getYear());
        $this->assertEquals(12, $value->getMonth());
        $this->assertEquals(31, $value->getDay());

        $value = new model\DateValue();
        $this->assertTrue($value !== NULL);
        $this->assertEquals(model\Type::DATE, $value->getType()->getCategory());
        $value->setContent('2013-07-21');
        $this->assertEquals(2013, $value->getYear());
        $this->assertEquals(7, $value->getMonth());
        $this->assertEquals(21, $value->getDay());

        $value = new model\TimeValue(12, 30, 45);
        $this->assertTrue($value !== NULL);
        $this->assertEquals(model\Type::TIME, $value->getType()->getCategory());
        $this->assertEquals(12, $value->getHour());
        $this->assertEquals(30, $value->getMinute());
        $this->assertEquals(45, $value->getSecond());

        $value = new model\TimeValue();
        $this->assertTrue($value !== NULL);
        $this->assertEquals(model\Type::TIME, $value->getType()->getCategory());
        $value->setContent('15:50:00');
        $this->assertEquals(15, $value->getHour());
        $this->assertEquals(50, $value->getMinute());
        $this->assertEquals(0, $value->getSecond());

    }

    public function testTable()
    {

        $db = new model\Database("demo");

        $table = new model\Table($db, "persons");

        $table->addKeyField("id", model\IntegerType::get());
        try {
            $table->addKeyField("id", model\IntegerType::get());
            $excOccurred = FALSE;
        } catch (\Exception $error) {
            $excOccurred = TRUE;
        }
        $this->assertTrue($excOccurred);

        $table->addDataField("name", new model\VarCharType(50));
        try {
            $table->addDataField("name", new model\VarCharType(50));
            $excOccurred = FALSE;
        } catch (\Exception $error) {
            $excOccurred = TRUE;
        }
        $this->assertTrue($excOccurred);

        $table->addDataField("first_name", new model\VarCharType(50), TRUE);
        $table->addDataField("birth_day", model\DateType::get(), TRUE);

        $this->assertEquals(4, count($table->getFields()));

        $record = $table->createRecord();

        $fieldnames = array("id", "name", "first_name", "birth_day");
        $i = 0;

        foreach ($record as $name => $value) {
            $this->assertEquals($fieldnames[$i++], $name);
        }

        $record->id = 4711;
        $record->name = "Mustermann";
        $record->first_name = "Herbert";

        $this->assertEquals(4711, $record->id);
        $this->assertEquals("Mustermann", $record->name);
        $this->assertEquals("Herbert", $record->first_name);

        $this->assertEquals(null, $record->birth_day);
        $record->birth_day = '1949-05-23';
        $this->assertEquals(1949, $record->birth_day->getYear());

    }

    public function testRecord()
    {

        $components = array(
            new model\Component(model\StringType::get(), "name"),
            new model\Component(model\IntegerType::get(), "", TRUE)
        );

        $record = new model\Record($components);

        $this->assertEquals(2, $record->getNumComponents());

        $this->assertEquals("", $record->name);
        $this->assertEquals(null, $record->getContent(1));

        $record->setContent(0, "Mustermann");
        $record->setContent(1, 42);

        $this->assertEquals("Mustermann", $record->name);
        $this->assertEquals(42, $record->getContent(1));

    }

}
