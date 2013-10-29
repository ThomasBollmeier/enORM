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

class CompoundCondition extends Condition
{

    public function __construct($condition_1, $condition_2)
    {

        parent::__construct();

        $this->elements = array($condition_1, $condition_2);

    }

    public function add($condition)
    {

        array_push($this->elements, $condition);

    }

    public function getElements()
    {

        return $this->elements;

    }

    protected $elements;

}