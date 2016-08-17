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

namespace tbollmeier\enorm\dbmodel;


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
