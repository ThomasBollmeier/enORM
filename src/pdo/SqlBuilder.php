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

namespace tbollmeier\enorm\pdo;

use tbollmeier\enorm\dbapi as api;
use tbollmeier\enorm\dbmodel as model;


class SqlBuilder
{

    public function createTableStmt(model\Table $table)
    {

        throw new \Exception("Table creation not supported!");

    }

    public function createStmt(model\Table $table, model\Record $record)
    {

        $columnNames = "";
        $values = "";

        foreach ($table->getFields() as $item) {

            $columnName = $item[0]->getName();
            $value = $record->getValue($columnName);
            if ($value === null) {
                continue;
            }

            if (strlen($columnNames) > 0) {
                $columnNames .= ", ";
            }
            $columnNames .= $columnName;

            if (strlen($values) > 0) {
                $values .= ", ";
            }
            $values .= $this->valueStr($value);

        }

        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)",
            $table->getName(),
            $columnNames,
            $values
        );

        return $sql;

    }

    public function updateStmt(model\Table $table,
                               $fieldValues,
                               api\Condition $condition)
    {

        $sql = "UPDATE " . $table->getName() . " SET\n";
        $changes = "";
        foreach ($fieldValues as $fval) {
            $line = "\t" . $fval->field->getName() . " = ";
            $line .= $this->valueStr($fval->value) . "\n";
            if (strlen($changes) > 0) {
                $changes .= $line;
            } else {
                $changes = $line;
            }
        }
        $sql .= $changes . "WHERE " . $this->conditionStr($condition);

        return $sql;

    }

    public function selectStmt(model\Source $source,
                               $targets = array(),
                               api\Condition $condition = null)
    {
        if (!$source instanceof model\Table) {
            throw new \Exception("Select only supported for tables");
        }

        if ($targets === null || count($targets) == 0) {
            $targetsStr = "*";
        } else {
            $targetsStr = "";
            foreach ($targets as $target) {

                if (strlen($targetsStr) > 0) {
                    $targetsStr .= ", ";
                }

                if ($target instanceof api\ReadTargetField) {
                    $alias = $target->getAlias();
                    $field = $target->getField();
                    $targetsStr .= $field->getName();
                    if (strlen($alias) > 0) {
                        $targetsStr .= " AS ".$alias;
                    }
                } else {
                    throw new \Exception("Aggregates are currently not supported");
                }
            }
        }

        $sql = sprintf("SELECT %s FROM %s", $targetsStr, $source->getName());

        if ($condition) {
            $sql .= " WHERE ".$this->conditionStr($condition);
        }

        return $sql;
    }

    public function deleteStmt(model\Table $table, api\Condition $condition = null)
    {
        $sql = "DELETE FROM ".$table->getName();
        if ($condition) {
            $sql .= " WHERE ".$this->conditionStr($condition);
        }

        return $sql;
    }

    public function conditionStr(api\Condition $condition)
    {

        $condStr = "";

        if ($condition instanceof api\Conjunction) {
            // a AND b ...
            foreach ($condition->getElements() as $elem) {
                if (strlen($condStr) > 0) {
                    $condStr .= " AND ";
                }
                $condStr .= "(" . $this->conditionStr($elem) . ")";
            }
        } else if ($condition instanceof api\Disjunction) {
            // a OR b ...
            foreach ($condition->getElements() as $elem) {
                if (strlen($condStr) > 0) {
                    $condStr .= " OR ";
                }
                $condStr .= "(" . $this->conditionStr($elem) . ")";
            }
        } else if ($condition instanceof api\Negation) {

            $condStr = "NOT (" . $this->conditionStr($condition) . ")";

        } else if ($condition instanceof api\FieldCondition) {

            $condStr = $this->fieldCondStr($condition);

        } else if ($condition instanceof api\FieldParameterCondition) {

            throw new \Exception("Unsupported condition type"); //  TODO: implementieren

        } else {

            throw new \Exception("Unsupported condition type");

        }

        return $condStr;
    }

    public function valueStr(model\Value $value)
    {

        $valstr = "";
        $category = $value->getType()->getCategory();

        switch ($category) {
            case model\Type::BOOLEAN:
                $valstr = $value->getContent() ? "1" : "0";
                break;
            case model\Type::VARCHAR:
            case model\Type::STRING:
                $valstr = "'" . $value->getContent() . "'";
                break;
            case model\Type::INTEGER:
                $valstr = sprintf("%d", $value->getContent());
                break;
            case model\Type::DECIMAL:
                $valstr = sprintf("%f", $value->getContent());
                break;
            case model\Type::DATE:
                $valstr = sprintf("'%04d-%02d-%02d'",
                    $value->getYear(),
                    $value->getMonth(),
                    $value->getDay()
                );
                break;
            case model\Type::TIME:
                $valstr = sprintf("'%02d:%02d:%02d'",
                    $value->getHour(),
                    $value->getMinute(),
                    $value->getSecond()
                );
                break;
        }

        return $valstr;

    }

    public function valueFromStr($type, $valueStr)
    {

        switch ($type->getCategory()) {
            case model\Type::BOOLEAN:
                $value = new model\BooleanValue($valueStr ? TRUE : FALSE);
                break;
            case model\Type::DATE:
                $value = new model\DateValue();
                $value->setContent($valueStr);
                break;
            case model\Type::DECIMAL:
                $value = new model\DecimalValue($type, doubleval($valueStr));
                break;
            case model\Type::INTEGER:
                $value = new model\IntegerValue(intval($valueStr));
                break;
            case model\Type::STRING:
                $value = new model\StringValue($valueStr);
                break;
            case model\Type::TIME:
                $value = new model\TimeValue();
                $value->setContent($valueStr);
                break;
            case model\Type::VARCHAR:
                $value = new model\VarCharValue($type, $valueStr);
                break;
            default:
                $value = null;
        }

        return $value;

    }

    protected function fieldCondStr($fieldCond)
    {
        $condStr = $fieldCond->getField()->getName();
        $op = $fieldCond->getOperator();

        switch ($op) {
            case api\FieldOperator::EQ:
                $condStr .= " = ";
                break;
            case api\FieldOperator::NE:
                $condStr .= " <> ";
                break;
            case api\FieldOperator::GT:
                $condStr .= " > ";
                break;
            case api\FieldOperator::GE:
                $condStr .= " >= ";
                break;
            case api\FieldOperator::LT:
                $condStr .= " < ";
                break;
            case api\FieldOperator::LE:
                $condStr .= " <= ";
                break;
            default:
                throw new \Exception("Unknown operator in field condition");
        }

        $condStr .= $this->valueStr($fieldCond->getValue());

        return $condStr;
    }

}