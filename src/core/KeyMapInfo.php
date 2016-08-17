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

/**
 * Class KeyMapInfo
 *
 * Holds info on dependent (=target) tables
 * @package enorm\core
 */
class KeyMapInfo
{
    public $sourceTabName;
    public $sourceFieldName;
    public $targetFieldName;

    public static function create($targetField, $sourceTab, $sourceField)
    {
        $keyMap = new KeyMapInfo();
        $keyMap->targetFieldName = $targetField;
        $keyMap->sourceTabName = $sourceTab;
        $keyMap->sourceFieldName = $sourceField;

        return $keyMap;
    }
}
