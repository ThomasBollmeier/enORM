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

// edit-section namespace {

namespace enorm\codegen;

// } edit-section-end

require_once 'Bovinus/parser.php';

// edit-section init {

// } edit-section-end

class Enorm_Parser extends Bovinus_Parser {

	public function __construct() {
		
		parent::__construct(new _Enorm_Grammar());
		
	}
}

// ========== Private section ==========

$all_token_types = array();

$VARNAME = new Bovinus_Word('[_a-zA-Z][_a-zA-Z0-9]*');
array_push($all_token_types, $VARNAME);

$CURLY_LBRACE = new Bovinus_Separator('{', TRUE, TRUE);
array_push($all_token_types, $CURLY_LBRACE);

$CURLY_RBRACE = new Bovinus_Separator('}', TRUE, TRUE);
array_push($all_token_types, $CURLY_RBRACE);

$KEY_1 = new Bovinus_Keyword('persistentobject', TRUE);
array_push($all_token_types, $KEY_1);

class _Persobjdef_Rule extends Bovinus_Rule {

	public function __construct($identifier="") {
	
		parent::__construct('persobjdef', $identifier);
		
	}
	
	public function expand($start, $end, $context) {
		
		$start->connect($this->_sub_1())->connect($end);
		
	}
	
	public function transform($astNode) {
		
		// edit-section persobjdef-transform {
		
		return $astNode;
		
		// } edit-section-end
		
	}
	
	private function _sub_1() {
		
		$elements = array();
		array_push($elements, $this->_sub_1_1());
		array_push($elements, $this->_sub_1_2());
		array_push($elements, $this->_sub_1_3());
		array_push($elements, $this->_sub_1_4());
		
		return new Bovinus_Sequence($elements);
		
	}
	
	private function _sub_1_1() {
		
		global $KEY_1;
		
		return bovinus_tokenNode($KEY_1);
		
	}
	
	private function _sub_1_2() {
		
		global $VARNAME;
		
		return bovinus_tokenNode($VARNAME, 'objname');
		
	}
	
	private function _sub_1_3() {
		
		global $CURLY_LBRACE;
		
		return bovinus_tokenNode($CURLY_LBRACE);
		
	}
	
	private function _sub_1_4() {
		
		global $CURLY_RBRACE;
		
		return bovinus_tokenNode($CURLY_RBRACE);
		
	}
	
	// edit-section persobjdef-further-private-methods {
	
	// add your methods here...
	
	// } edit-section-end
	
}

class _Enorm_Grammar extends Bovinus_Grammar {

	public function __construct() {
	
		global $all_token_types;
		
		parent::__construct('_Enorm_Grammar', $all_token_types);
		
	}
	
	public function expand($start, $end, $context) {
		
		$start->connect($this->_sub_1())->connect($end);
		
	}
	
	public function transform($astNode) {
		
		// edit-section enorm-transform {
		
		return $astNode;
		
		// } edit-section-end
		
	}
	
	private function _sub_1() {
		
		return $this->_sub_1_1();
		
	}
	
	private function _sub_1_1() {
		
		return bovinus_zero_to_many(new _Persobjdef_Rule());
		
	}
	
	// edit-section enorm-further-private-methods {
	
	// add your methods here...
	
	// } edit-section-end
	
}

?>
