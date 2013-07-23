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
require_once '../dbmodel/table.php';
require_once '../dbmodel/record.php';
use \enorm\dbmodel as model;

interface Connection {

    public function createTable(model\Table $table);

    public function create(model\Table $table, model\Record $record);

    public function read($source, $targets=array(), Condition $condition=null);

    public function update(model\Table $table, $fields, Condition $condition);

    public function delete(model\Table $table, $condition);

}