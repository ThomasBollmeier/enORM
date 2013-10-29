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

namespace enorm\pdo;

use enorm\dbapi\Condition;
use enorm\dbapi\PreparedReadStatement;
use enorm\dbapi\PreparedStatement;
use enorm\dbmodel as model;

require_once("enorm/dbapi/connection.php");
require_once("Cursor.php");

class Connection implements \enorm\dbapi\Connection
{

    public function __construct(SqlBuilder $sqlbuilder, $dsn, $user = "", $password = "")
    {

        $this->sqlbuilder = $sqlbuilder;

        try {
            $this->pdo = new \PDO($dsn, $user, $password);
        } catch (\PDOException $error) {
            $this->pdo = null;
        }

    }

    public function __destruct()
    {
        $this->pdo = null;
        $this->sqlbuilder = null;
    }

    public function isOK()
    {
        return $this->pdo != null;
    }

    /**
     * @param $tableName
     * @return boolean
     */
    public function existsTable($tableName)
    {
        return FALSE;
    }

    /**
     * @param model\Table $table
     * @return boolean
     */
    public function createTable(model\Table $table)
    {
        $sql = $this->sqlbuilder->createTableStmt($table);
        return $this->pdo->exec($sql) !== FALSE ? TRUE : FALSE;
    }

    /**
     * @param model\Table $table
     * @param model\Record $record
     * @return boolean
     */
    public function create(model\Table $table, model\Record $record)
    {
        $sql = $this->sqlbuilder->createStmt($table, $record);
        $numRows = $this->pdo->exec($sql);
        return ($numRows !== FALSE && $numRows !== 0) ? TRUE : FALSE;
    }

    /**
     * @param model\Source $source
     * @param array $targets
     * @param Condition $condition
     * @return Cursor
     */
    public function read(model\Source $source, $targets = array(), Condition $condition = null)
    {
        $sql = $this->sqlbuilder->selectStmt($source, $targets, $condition);
        $stmt = $this->pdo->query($sql);

        if ($stmt !== FALSE) {

            return new Cursor($this->sqlbuilder, $stmt, $source, $targets);

        } else {

            return null;

        }

    }

    /**
     * @param model\Table $table
     * @param $fieldValues
     * @param Condition $condition
     * @return boolean
     */
    public function update(model\Table $table, $fieldValues, Condition $condition)
    {
        $sql = $this->sqlbuilder->updateStmt($table, $fieldValues, $condition);
        $numRows = $this->pdo->exec($sql);
        return ($numRows !== FALSE && $numRows !== 0) ? TRUE : FALSE;
    }

    /**
     * @param model\Table $table
     * @param $condition
     * @return boolean
     */
    public function delete(model\Table $table, Condition $condition = null)
    {
        $sql = $this->sqlbuilder->deleteStmt($table, $condition);
        $numRows = $this->pdo->exec($sql);
        return ($numRows !== FALSE && $numRows !== 0) ? TRUE : FALSE;
    }

    /**
     * @param model\Table $table
     * @param array of FieldValueParams $fieldValueParams
     * @return PreparedStatement
     */
    public function prepareCreate(model\Table $table, $fieldValueParams)
    {
        // TODO: Implement prepareCreate() method.
    }

    /**
     * @param model\Source $source
     * @param array $targets
     * @param Condition $condition
     * @return PreparedReadStatement
     */
    public function prepareRead(model\Source $source, $targets = array(), Condition $condition = null)
    {
        // TODO: Implement prepareRead() method.
    }

    /**
     * @param model\Table $table
     * @param array of FieldValueParams $fieldValueParams
     * @param Condition $condition
     * @return PreparedStatement
     */
    public function prepareUpdate(model\Table $table, $fieldValueParams, Condition $condition)
    {
        // TODO: Implement prepareUpdate() method.
    }

    /**
     * @param model\Table $table
     * @param $condition
     * @return PreparedStatement
     */
    public function prepareDelete(model\Table $table, $condition)
    {
        // TODO: Implement prepareDelete() method.
    }

    private $pdo;
    private $sqlbuilder;

}