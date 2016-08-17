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
namespace tbollmeier\enorm\dbapi;

use tbollmeier\enorm\dbmodel as model;


class FieldParameterCondition extends Condition
{

    public function __construct(model\Field $field,
                                $operator,
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
