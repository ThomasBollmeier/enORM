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
 
namespace enorm\dbmodel;

class Database {
	 
	 public $name; 
	 
	 public function __construct($name) {
		 
		 $this->name = $name;
		 
	 }
	 
	 public function addTable($table) {
		 
		 if ($table->getDb() != null) {
			 return; // todo: throw exception
		 }

         $tableName = $table->getName();

		 if ($tableName == "") {
			 return; // todo: throw exception
		 }
		 
		 $this->tables[$tableName] = $table;
		
	 }
	 
	 public function getTable($name) {
		 
		 return $this->tables[$name];
		 
	 }
	 
	 private $tables = array();
	 	 
}

