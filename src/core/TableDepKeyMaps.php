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
namespace tbollmeier\enorm\core;


class TableDepKeyMaps
{
    private $parentTab;
    private $fieldMap;

    public function __construct($parentTab)
    {
        $this->parentTab = $parentTab;
        $this->fieldMap = [];
    }

    public function addMap($depTabField, $parentField)
    {
        $this->fieldMap[$depTabField] = $parentField;
        return $this;
    }

    public function getParentTable()
    {
        return $this->parentTab;
    }

    public function getKeyFieldNames()
    {
        return array_keys($this->fieldMap);
    }

    public function getParentField($keyField)
    {
        return $this->fieldMap[$keyField];
    }

}