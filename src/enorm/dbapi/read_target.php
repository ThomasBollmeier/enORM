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

require_once 'enorm/dbmodel/field.php';
use \enorm\dbmodel\Field;

abstract class ReadTarget {

    // Target categories:
    const FIELD = 1;
    const AGGREGATE = 2;

    public function __construct($alias="")
    {
        $this->alias = $alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    protected $alias;

}

class ReadTargetField extends ReadTarget {

    public function __construct(Field $field, $alias="") {

        parent::__construct($alias);

        $this->field = $field;

    }

    public function getField()
    {
        return $this->field;
    }

    private $field;

}