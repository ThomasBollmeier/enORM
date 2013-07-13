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

interface Value
{

    public function getType();

}

class SimpleValue implements Value
{

    public function __construct($content, $type)
    {

        $this->content = $content;
        $this->type = $type;

    }

    public function getContent()
    {

        return $this->content;

    }

    public function getType()
    {

        return $this->type;

    }

    private $content;
    private $type;

}

class VarCharValue extends SimpleValue
{

    public function __construct($content, $maxlen)
    {

        $tmp = substr($content, 0, $maxlen);

        parent::__construct($tmp, new VarCharType($maxlen));

    }

}

class Date implements Value
{

    public function __construct($year, $month, $day)
    {

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;

    }

    public function getYear()
    {

        return $this->year;

    }

    public function getMonth()
    {

        return $this->month;

    }

    public function getDay()
    {

        return $this->day;

    }

    public function getType()
    {

        return DateType::get();

    }

    private $year;
    private $month;
    private $day;

}

class Time implements Value
{

    public function __construct($hour, $minute, $second)
    {

        $this->hour = $hour;
        $this->min = $minute;
        $this->sec = $second;

    }

    public function getHour()
    {

        return $this->hour;

    }

    public function getMinute()
    {

        return $this->min;

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
                return self::createInteger(0);
            case Type::BOOLEAN:
                return self::createBoolean(FALSE);
            case Type::DECIMAL:
                $length = $type->getLength();
                $digits = $type->getDigits();
                return self::createDecimal(0.0, $length, $digits);
            case Type::VARCHAR:
                $maxLength = $type->getLength();
                return self::createText("", $maxLength);
            case Type::STRING:
                return self::createText("");
            case Type::DATE:
                return self::createDate(0, 0, 0);
            case Type::TIME:
                return self::createTime(0, 0, 0);
            default:
                return null;
        }

    }

    public static function createInteger($ival)
    {

        return new SimpleValue($ival, IntegerType::get());

    }

    public static function createBoolean($bval = TRUE)
    {

        return new SimpleValue($bval, BooleanType::get());

    }

    public static function createDecimal($decval, $length = 0, $digits = 0)
    {

        return new SimpleValue($decval, new DecimalType($length, $digits));

    }

    public static function createText($textval, $maxlen = 0)
    {

        return !$maxlen ?
            new SimpleValue($textval, StringType::get()) :
            new VarCharValue($textval, $maxlen);

    }

    public static function createDate($year, $month, $day)
    {

        return new Date($year, $month, $day);

    }

    public static function createTime($hour, $minute, $second)
    {

        return new Time($hour, $minute, $second);

    }

}
