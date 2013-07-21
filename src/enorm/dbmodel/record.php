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

require_once "values.php";

class Record implements \Iterator
{

    public function __construct($components)
    {
        $this->components = $components;

        $this->compmap = array();
        $this->values = array();
        $this->valuemap = array();

        $valueIdx = 0;

        foreach ($this->components as $comp) {

            $this->compmap[$comp->name] = $comp;

            $value = !$comp->isNullAllowed ? ValueFactory::createInitValue($comp->type) : null;
            array_push($this->values, $value);

            $this->valuemap[$comp->name] = array($value, $valueIdx);
            $valueIdx++;

        }

    }

    public function setBoolean($componentName, $boolval)
    {
        $value = $this->_getValue($componentName, TRUE);
        $value->setContent($boolval);
    }

    public function setInteger($componentName, $intval)
    {
        $value = $this->_getValue($componentName, TRUE);
        $value->setContent($intval);
    }

    public function setDecimal($componentName, $decval)
    {
        $value = $this->_getValue($componentName, TRUE);
        $value->setContent($decval);
    }

    public function setString($componentName, $strval)
    {
        $value = $this->_getValue($componentName, TRUE);
        $value->setContent($strval);
    }

    public function setVarchar($componentName, $varcharval)
    {
        $value = $this->_getValue($componentName, TRUE);
        $value->setContent($varcharval);
    }

    public function setDate($componentName, $year, $month, $day)
    {
        $value = $this->_getValue($componentName, TRUE);
        $value->setYear($year)
            ->setMonth($month)
            ->setDay($day);
    }

    public function setTime($componentName, $hour, $minute, $second)
    {
        $value = $this->_getValue($componentName, TRUE);
        $value->setHour($hour)
            ->setMinute($minute)
            ->setSecond($second);
    }

    public function getValue($componentName)
    {

        return $this->_getValue($componentName, FALSE);

    }

    public function __get($componentName)
    {
        $value = $this->_getValue($componentName, FALSE);

        if ($value !== null) {

            $category = $value->getType()->getCategory();

            switch ($category) {
                case Type::DATE:
                case Type::TIME:
                    return $value;
                default:
                    return $value->getContent();
            }

        } else {
            return null;
        }

    }

    public function __set($componentName, $content)
    {

        $value = $this->_getValue($componentName, TRUE);
        $value->setContent($content);

    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->values[$this->iterpos];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->iterpos++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        if ($this->iterpos >= count($this->components)) return null;

        return $this->components[$this->iterpos]->name;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->iterpos < count($this->components);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->iterpos = 0;
    }

    private $components;
    private $compmap;
    private $values;
    private $valuemap;
    private $iterpos = 0;

    private function _getValue($componentName, $createIfNull = FALSE)
    {
        $valueInfo = $this->valuemap[$componentName];
        $value = $valueInfo[0];

        if ($createIfNull && $value === null) {

            $comp = $this->compmap[$componentName];
            $value = ValueFactory::createInitValue($comp->type);

            $valueIdx = $valueInfo[1];
            $this->values[$valueIdx] = $value;
            $this->valuemap[$componentName] = array($value, $valueIdx);

        }

        return $value;

    }

}

class Component
{
    public $name;
    public $type;
    public $isNullAllowed;

    public function __construct($name, $type, $isNullAllowed = FALSE)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isNullAllowed = $isNullAllowed;
    }

}