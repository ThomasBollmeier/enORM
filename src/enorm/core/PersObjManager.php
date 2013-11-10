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
namespace enorm\core;

require_once("enorm/dbapi/conjunction.php");
require_once("enorm/dbapi/field_condition.php");
require_once("enorm/dbmodel/values.php");
require_once("enorm/core/PersistentObject.php");

use enorm\dbapi\Disjunction;
use \enorm\dbapi\FieldCondition;
use \enorm\dbapi\FieldOperator;
use \enorm\dbapi\Conjunction;
use \enorm\dbmodel\Value;
use \enorm\dbmodel\ValueFactory;

class PersObjManager {

    public static function getManager($absoluteClassName)
    {
        if (!array_key_exists($absoluteClassName, self::$instances)) {
            self::$instances[$absoluteClassName] = new PersObjManager($absoluteClassName);
        }

        return self::$instances[$absoluteClassName];
    }

    /**
     * Load a single instance from database
     *
     * @param $conn : connection to database
     * @param $db : database
     * @param $keys : array of key value pairs - e.g. array("id" => 42)
     * @param $cbOnLoaded : callback to be executed when the instance has been loaded
     */
    public function load($conn, $db, $keys, $cbOnLoaded) {

        $headerTabName = $this->getHeaderTableName();
        $headerTab = $db->getTable($headerTabName);
        $conditions = array();

        foreach ($keys as $name => $val) {

            if ($val instanceof Value) {
                $value = $val;
            } else {
                $fieldType = $headerTab->$name->getType();
                $value = ValueFactory::createInitValue($fieldType);
                $value->setContent($val);
            }

            array_push(
                $conditions,
                new FieldCondition(
                    $headerTab->$name,
                    FieldOperator::EQ,
                    $value
                )
            );
        }

        $rowsPerTable = array(
            $headerTabName => array()
        );
        $cursor = $conn->read($headerTab, array(), Conjunction::create($conditions));
        while ($record = $cursor->getNextRecord()) {
            array_push($rowsPerTable[$headerTabName], $record);
        }

        $this->readDependentTables($conn, $db, $rowsPerTable);

        $object = new $this->className();
        $object->setAttrsFromDbData($rowsPerTable);

        if (!is_array($cbOnLoaded)) {
            $cbOnLoaded($object);
        } else {
            call_user_func($cbOnLoaded, $object);
        }

    }

    public function getHeaderTableName()
    {
        return $this->headerTabInfo[0];
    }

    public function getHeaderKeyFields()
    {
        return $this->headerTabInfo[1];
    }

    private static $instances = array();
    private $className; // absolute class name - e.g. \enorm\core\PersObjManager
    private $headerTabInfo;
    private $keyMapping;

    private function __construct($className)
    {
        $this->className = $className;
        $this->headerTabInfo = forward_static_call(array($className, "getHeaderTabInfo"));
        $this->keyMapping = forward_static_call(array($className, "getKeyMapping"));
    }

    private function readDependentTables($conn, $db, &$rowsPerTable)
    {
        $helper = new TableDependencyHelper($this->keyMapping);
        $depTableNames = $helper->getTablesSortedByLevel();

        foreach ($depTableNames as $tableName) {

            $keyMaps = $this->keyMapping[$tableName];
            $depTable = $db->getTable($tableName);
            $conditions = array();

            foreach ($keyMaps as $keyMap) {
                array_push(
                    $conditions,
                    $this->createConditionFromKeyMap(
                        $keyMap,
                        $depTable,
                        $rowsPerTable
                    )
                );
            }

            // Now select...
            $rowsPerTable[$tableName] = array();
            $cursor = $conn->read($depTable, array(), Conjunction::create($conditions));
            while ($record = $cursor->getNextRecord()) {
                array_push($rowsPerTable[$tableName], $record);
            }

        }

    }

    private function createConditionFromKeyMap($keyMap, $depTable, $rowsPerTable)
    {

        $fieldName = $keyMap->targetFieldName;
        $rows = $rowsPerTable[$keyMap->sourceTabName];
        $conditions = array();

        foreach ($rows as $row) {
            array_push(
                $conditions,
                new FieldCondition(
                    $depTable->$fieldName,
                    FieldOperator::EQ,
                    $row->getValue($keyMap->sourceFieldName)
                )
            );
        }

        return Disjunction::create($conditions);

    }

}
