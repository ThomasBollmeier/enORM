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


class Cursor implements api\Cursor {

    public function __construct(
        $sqlbuilder,
        $statement,
        $source,
        $targets)
    {
        $this->sqlbuilder = $sqlbuilder;
        $this->stmt = $statement;
        $this->components = $this->createComponents($source, $targets);
    }

    /**
     * @return model\Record
     */
    public function getNextRecord()
    {
        $row = $this->stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row !== FALSE) {

            $record = new model\Record($this->components);
            foreach ($this->components as $comp) {
                $valueStr = $row[$comp->name];
                if ($valueStr === null) {
                    continue;
                }
                $value = $this->sqlbuilder->valueFromStr($comp->type, $valueStr);
                if ($value !== null) {
                    $record->setContent($comp->name, $value->getContent());
                }
            }

            return $record;

        } else {

            return null;

        }
    }

    private $sqlbuilder;
    private $stmt;
    private $components;

    private function createComponents($source, $targets)
    {
        if (!$source instanceof model\Table){
            throw new \Exception("Unsupported source type");
        }

        if (count($targets) === 0) {
            return $source->getComponents();
        } else {
            $comps = array();
            foreach ($targets as $target) {
                if ($target instanceof api\ReadTargetField) {
                    $field = $target->getField();
                    $alias = $target->getAlias();
                    $compName = strlen($alias) == 0 ?
                        $field->getName() :
                        $alias;
                    $comp = new model\Component(
                        $field->getType(),
                        $compName,
                        $field->isNullAllowed()
                    );
                    array_push($comps, $comp);
                } else {
                    throw new \Exception("Unsupported target category");
                }
            }
            return $comps;
        }
    }

}