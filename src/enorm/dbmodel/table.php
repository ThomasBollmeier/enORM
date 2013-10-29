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

namespace enorm\dbmodel;

require_once 'source.php';
require_once 'field.php';
require_once 'record.php';

class Table extends Source
{

    public function __construct($db, $name)
    {

        parent::__construct($name);

        $db->addTable($this);
        $this->db = $db;

    }

    public function addDataField($name, $type, $nullAllowed = TRUE)
    {

        if ($this->existsName($name)) {
            throw new  \Exception("There is already a field with name '$name'!");
        }

        $this->datafields[] = new Field($this, $name, $type, array("nullAllowed" => $nullAllowed));

    }

    public function addKeyField($name, $type)
    {

        if ($this->existsName($name)) {
            throw new  \Exception("There is already a field with name '$name'!");
        }

        $this->keyfields[] = new Field($this, $name, $type, array("nullAllowed" => FALSE));

    }

    public function getDataFields()
    {

        return $this->datafields;

    }

    public function getDb()
    {

        return $this->db;

    }

    public function getFields()
    {

        $res = array();

        foreach ($this->keyfields as $fld) {
            $res[] = array($fld, TRUE);
        }

        foreach ($this->datafields as $fld) {
            $res[] = array($fld, FALSE);
        }

        return $res;

    }

    public function getKeyFields()
    {

        return $this->keyfields;

    }

    public function getFieldByPos($pos)
    {

        if ($pos < count($this->keyfields)) {
            return $this->keyfields[$pos];
        } else {
            $idx = $pos - count($this->keyfields);
            return $this->datafields[$idx];
        }

    }

    public function getFieldByName($name)
    {

        foreach ($this->keyfields as $field) {
            if ($field->getName() == $name) {
                return $field;
            }
        }

        foreach ($this->datafields as $field) {
            if ($field->getName() == $name) {
                return $field;
            }
        }

        return null;

    }

    public function createRecord()
    {

        return new Record($this->getComponents());

    }

    public function getComponents()
    {

        $fields = array_merge($this->keyfields, $this->datafields);
        $components = array();
        foreach ($fields as $field) {
            array_push($components, new Component($field->getType(), $field->getName(), $field->isNullAllowed()));
        }

        return $components;

    }

    private function existsName($name)
    {

        foreach ($this->keyfields as $field) {
            if ($field->getName() === $name) {
                return TRUE;
            }
        }

        foreach ($this->datafields as $field) {
            if ($field->getName() === $name) {
                return TRUE;
            }
        }

        return FALSE;

    }

    private $db = null;
    private $keyfields = array();
    private $datafields = array();

}

