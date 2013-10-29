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

namespace enorm\pdo\mysql;

require_once("enorm/pdo/SqlBuilder.php");
use enorm\pdo\SqlBuilder as Builder;
use enorm\dbmodel as model;

class SqlBuilder extends Builder {

    public function createTableStmt(model\Table $table)
    {
        return "";
    }

}