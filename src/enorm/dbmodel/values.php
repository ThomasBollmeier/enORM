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

require_once 'types.php';

abstract class Value
{
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    abstract public function setContent($content);
    abstract public function getContent();

    protected $type;

}

class BooleanValue extends Value
{

    public function __construct($boolval=FALSE)
    {
        parent::__construct(BooleanType::get());

        $this->boolval = $boolval;

    }

    public function setContent($content)
    {

        if (!is_bool($content)) {
            throw new \Exception("Cast error!");
        }

        $this->boolval = $content;

    }

    public function getContent()
    {
        return $this->boolval;
    }

    private $boolval;

}

class IntegerValue extends Value
{

    public function __construct($intval=0)
    {
        parent::__construct(IntegerType::get());

        $this->intval = $intval;

    }

    public function setContent($content)
    {

        if (!is_integer($content)) {
            throw new \Exception("Cast error!");
        }

        $this->intval = $content;

    }

    public function getContent()
    {
        return $this->intval;
    }

    private $intval;

}

class DecimalValue extends Value
{

    public function __construct($dectype, $decval=0.0)
    {
        parent::__construct($dectype);

        $this->decval = $decval;

    }

    public function setContent($content)
    {

        if (!is_double($content)) {
            throw new \Exception("Cast error!");
        }

        $this->decval = $content;

    }

    public function getContent()
    {
        return $this->decval;
    }

    private $decval;

}

class VarCharValue extends Value
{

    public function __construct($varcharType, $text="")
    {
        parent::__construct($varcharType);

        $this->setContent($text);

    }

    public function setContent($content)
    {
        if (!is_string($content)) {
            throw new \Exception("Cast error!");
        }

        $maxlen = $this->type->getLength();
        $this->varcharval = substr($content, 0, $maxlen);

    }

    public function getContent()
    {
        return $this->varcharval;
    }

    private $varcharval;

}

class StringValue extends Value
{

    public function __construct($text="")
    {
        parent::__construct(StringType::get());

        $this->strval = $text;

    }

    public function setContent($content)
    {
        if (!is_string($content)) {
            throw new \Exception("Cast error!");
        }

        $this->strval = $content;

    }

    public function getContent()
    {
        return $this->strval;
    }

    private $strval;

}

class DateValue extends Value
{

    public function __construct($year=0, $month=0, $day=0)
    {
        parent::__construct(DateType::get());

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;

    }

    public function setContent($content)
    {

        if ($content instanceof DateValue) {

            $this->year = $content->year;
            $this->month = $content->month;
            $this->day = $content->day;

            return;
        }

        if (!is_string($content)) {
            throw new \Exception("Cast error!");
        }

        $datePattern = '/^(\d{4,})-(\d{2})-(\d{2})$/';

        if (preg_match($datePattern, $content, $matches)) {
            $this->year = intval($matches[1]);
            $this->month = intval($matches[2]);
            $this->day = intval($matches[3]);
        }

    }

    public function getContent()
    {

        return sprintf("%04d-%02d-%02d", $this->year, $this->month, $this->day);

    }

    public function setYear($year)
    {

        $this->year = $year;
        return $this;

    }

    public function getYear()
    {

        return $this->year;

    }

    public function setMonth($month)
    {

        $this->month = $month;
        return $this;

    }

    public function getMonth()
    {

        return $this->month;

    }

    public function setDay($day)
    {

        $this->day = $day;
        return $this;

    }

    public function getDay()
    {

        return $this->day;

    }

    private $year;
    private $month;
    private $day;

}

class TimeValue extends Value
{

    public function __construct($hour=0, $minute=0, $second=0)
    {
        parent::__construct(TimeType::get());

        $this->hour = $hour;
        $this->min = $minute;
        $this->sec = $second;

    }

    public function setContent($content)
    {

        if ($content instanceof TimeValue) {

            $this->hour = $content->hour;
            $this->min = $content->min;
            $this->sec = $content->sec;

            return;
        }

        if (!is_string($content)) {
            throw new \Exception("Cast error!");
        }

        $timePattern = '/^(\d{1,}):(\d{2}):(\d{2})$/';

        if (preg_match($timePattern, $content, $matches)) {
            $this->hour = intval($matches[1]);
            $this->min = intval($matches[2]);
            $this->sec = intval($matches[3]);
        }

    }

    public function getContent()
    {

        return sprintf("%02d:%02d:%02d", $this->hour, $this->min, $this->sec);

    }

    public function setHour($hour)
    {

        $this->hour = $hour;
        return $this;

    }

    public function getHour()
    {

        return $this->hour;

    }

    public function setMinute($minute)
    {

        $this->min = $minute;
        return $this;

    }

    public function getMinute()
    {

        return $this->min;

    }

    public function setSecond($second)
    {

        $this->sec = $second;
        return $this;

    }

    public function getSecond()
    {

        return $this->sec;

    }

    public function getType()
    {

        return TimeType::get();

    }

    private $hour;
    private $min;
    private $sec;

}

class ValueFactory
{

    public static function createInitValue(Type $type)
    {
        $category = $type->getCategory();

        switch ($category) {
            case Type::INTEGER:
                return new IntegerValue();
            case Type::BOOLEAN:
                return new BooleanValue();
            case Type::DECIMAL:
                return new DecimalValue($type);
            case Type::VARCHAR:
                return new VarCharValue($type);
            case Type::STRING:
                return new StringValue();
            case Type::DATE:
                return new DateValue();
            case Type::TIME:
                return new TimeValue();
            default:
                return null;
        }

    }

}
