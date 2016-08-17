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


interface PersistentObject
{

    /**
     * Get the header name and list(s) of header key fields that define
     * the persistent instance.
     *
     * @return array of table name and array of key field names
     *
     * e.g. array("persons", array("id"))
     */
    public static function getHeaderTabInfo();

    /**
     * Get key mapping info for dependent tables
     *
     * @return array array of TableDepKeyMaps
     */
    public static function getKeyMapping();

    /**
     * Set the instance's attributes from the
     * database content
     *
     * @param $rowsPerTable : array of rows per table (name)
     */
    public function setAttrsFromDbData($rowsPerTable);

    /**
     * Prepare save operation by returning
     * the row data that have to be saved for
     * this instance
     *
     * @return array of rows per database table (name)
     */
    public function prepareSave();

}
