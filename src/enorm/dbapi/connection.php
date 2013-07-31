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
namespace enorm\dbapi;

require_once 'condition.php';
require_once '../dbmodel/table.php';
require_once '../dbmodel/record.php';
use \enorm\dbmodel as model;

interface Connection
{

    /**
     * @param model\Table $table
     * @return boolean
     */
    public function createTable(model\Table $table);

    /**
     * @param model\Table $table
     * @param model\Record $record
     * @return boolean
     */
    public function create(model\Table $table, model\Record $record);

    /**
     * @param model\Source $source
     * @param array $targets
     * @param Condition $condition
     * @return Cursor
     */
    public function read(model\Source $source, $targets = array(), Condition $condition = null);

    /**
     * @param model\Table $table
     * @param $fieldValues
     * @param Condition $condition
     * @return boolean
     */
    public function update(model\Table $table, $fieldValues, Condition $condition);

    /**
     * @param model\Table $table
     * @param $condition
     * @return boolean
     */
    public function delete(model\Table $table, $condition);

    /**
     * @param model\Table $table
     * @param array of FieldValueParams $fieldValueParams
     * @return PreparedStatement
     */
    public function prepareCreate(model\Table $table, $fieldValueParams);

    /**
     * @param model\Source $source
     * @param array $targets
     * @param Condition $condition
     * @return PreparedReadStatement
     */
    public function prepareRead(model\Source $source, $targets = array(), Condition $condition = null);

    /**
     * @param model\Table $table
     * @param array of FieldValueParams $fieldValueParams
     * @param Condition $condition
     * @return PreparedStatement
     */
    public function prepareUpdate(model\Table $table, $fieldValueParams, Condition $condition);

    /**
     * @param model\Table $table
     * @param $condition
     * @return PreparedStatement
     */
    public function prepareDelete(model\Table $table, $condition);

}

class FieldValue
{
    public $field;
    public $value;

    public function __construct(model\Field $field, model\Value $value = null)
    {
        $this->field = $field;
        $this->value = $value;
    }

}

class FieldValueParam extends FieldValue
{

    public $parameter;

    public function __construct(model\Field $field, model\Value $value = null, Parameter $parameter)
    {
        parent::__construct($field, $value);
        $this->parameter = $parameter;
    }

}

interface PreparedStatement
{
    function bind($paramName, $value);

    function execute();
}


interface PreparedReadStatement
{
    function bind($paramName, $value);

    /**
     * @return Cursor
     */
    function read();
}

interface Cursor
{
    /**
     * @return model\Record
     */
    function getNextRecord();
}