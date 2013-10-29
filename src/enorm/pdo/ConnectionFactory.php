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

namespace enorm\pdo;

require_once("enorm/pdo/mysql/SqlBuilder.php");
require_once("enorm/pdo/Connection.php");

class ConnectionFactory {

    public function connectMySql(
        $host,
        $database,
        $user,
        $password,
        $port = -1
    )
    {
        $builder = new mysql\SqlBuilder();
        if ($port > 0) {
            $dsn = sprintf("mysql:host=%s;port=%d;dbname=%s", $host, $port, $database);
        } else {
            $dsn = sprintf("mysql:host=%s;dbname=%s", $host, $database);
        }
        return new Connection($builder, $dsn, $user, $password);
    }

} 