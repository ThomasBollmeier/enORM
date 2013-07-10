<?php

namespace enorm\dbapi;

require_once 'filter.php';
require_once '../dbmodel/field.php';
require_once '../dbmodel/value.php';

use enorm\dbmodel as model;

class FieldFilter extends Filter
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