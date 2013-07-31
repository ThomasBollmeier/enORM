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
require_once '../dbmodel/field.php';
require_once '../dbmodel/value.php';

use enorm\dbmodel as model;

class FieldCondition extends Condition
{

    public function __construct(model\Field $field,
                                FieldOperator $operator,
                                model\Value $value
    )
    {

        parent::__construct();

        $this->field = $field;
        $this->op = $operator;
        $this->value = $value;

    }

    public function getField()
    {
        return $this->field;
    }

    public function getOperator()
    {
        return $this->op;
    }

    public function getValue()
    {
        return $this->value;
    }

    private $field;
    private $op;
    private $value;

}

class FieldParameterCondition extends Condition
{

    public function __construct(model\Field $field,
                                FieldOperator $operator,
                                Parameter $parameter
    )
    {

        parent::__construct();

        $this->field = $field;
        $this->op = $operator;
        $this->param = $parameter;

    }

    public function getField()
    {
        return $this->field;
    }

    public function getOperator()
    {
        return $this->op;
    }

    public function getValue()
    {
        return $this->param->getValue();
    }

    private $field;
    private $op;
    private $param;

}

abstract class FieldOperator
{

    const EQ = 1; // equal
    const NE = 2; // not equal
    const GT = 3; // greater than
    const GE = 4; // greater or equal
    const LT = 5; // less than
    const LE = 6; // less or equal
    const CP = 7; // contains pattern
    const NP = 8; // does not contain pattern

}