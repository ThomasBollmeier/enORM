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
