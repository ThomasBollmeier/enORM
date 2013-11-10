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

interface PersistentObject
{

    /**
     * Get the header name and list(s) of header key fields that define
     * the persistent instance.
     *
     * @return array of table name and array of key field names
     *
     * e.g. array("persons", array("id"))
     */
    public static function getHeaderTabInfo();

    /**
     * Get key mapping info for dependent tables
     *
     * @return key mapping as array(<depTabName> => array of KeyMapInfo)
     */
    public static function getKeyMapping();

    /**
     * Set the instance's attributes from the
     * database content
     *
     * @param $rowsPerTable : array of rows per table (name)
     */
    public function setAttrsFromDbData($rowsPerTable);

    /**
     * Prepare save operation by returning
     * the row data that have to be saved for
     * this instance
     *
     * @return array of rows per database table (name)
     */
    public function prepareSave();

}

/**
 * Class KeyMapInfo
 *
 * Holds info on dependent (=target) tables
 * @package enorm\core
 */
class KeyMapInfo
{
    public $sourceTabName;
    public $sourceFieldName;
    public $targetFieldName;

    public static function create($targetField, $sourceTab, $sourceField)
    {
        $keyMap = new KeyMapInfo();
        $keyMap->targetFieldName = $targetField;
        $keyMap->sourceTabName = $sourceTab;
        $keyMap->sourceFieldName = $sourceField;

        return $keyMap;
    }
}

class TableDependencyHelper
{
    public function __construct($keyMapsPerTable)
    {

        $this->deps = array();

        foreach ($keyMapsPerTable as $tableName => $keyMaps) {

            $dep = new TableDepInfo();
            $dep->tableName = $tableName;
            $dep->sourceTables = array();
            $dep->level = null; // not determined yet

            foreach ($keyMaps as $keyMap) {
                array_push($dep->sourceTables, $keyMap->sourceTabName);
            }

            $this->deps[$tableName] = $dep;

        }

    }

    public function getTablesSortedByLevel()
    {
        $tableNames = array();

        foreach ($this->deps as $tableName => $dep) {
            array_push($tableNames, $tableName);
        }

        uasort($tableNames, function ($name1, $name2) {
            $level1 = $this->getLevel($name1);
            $level2 = $this->getLevel($name2);
            if ($level1 == $level2) {
                return 0;
            }

            return $level1 > $level2 ? 1 : -1;
        });

        return $tableNames;

    }

    public function getLevel($tableName)
    {

        $this->currTables = array();

        return $this->_getLevel($tableName);

    }

    private $deps;
    private $currTables;

    private function _getLevel($tableName)
    {

        if (!array_key_exists($tableName, $this->deps)) {
            return 0;
        }

        $dep = $this->deps[$tableName];

        if ($dep->level === null) {

            if (array_key_exists($tableName, $this->currTables)) {
                throw new \Exception("Cyclic dependency found!");
            }

            $this->currTables[$tableName] = TRUE;
            $maxLevel = 0;

            foreach ($dep->sourceTables as $sourceTable) {
                $level = $this->_getLevel($sourceTable);
                if ($level > $maxLevel) {
                    $maxLevel = $level;
                }
            }

            $dep->level = $maxLevel + 1;

            unset($this->currTables[$tableName]);

        }

        return $dep->level;

    }

}

class TableDepInfo
{
    public $tableName;
    public $sourceTables;
    public $level;
}