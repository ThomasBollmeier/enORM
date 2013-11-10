<?php

require_once("enorm/dbmodel/database.php");
require_once("enorm/dbmodel/table.php");
use enorm\dbmodel as db;

function createTestDatabase() {

    $db = new enorm\dbmodel\Database("test");

    $table = new enorm\dbmodel\Table($db, "persons");
    $table->addKeyField("id", db\IntegerType::get());
    $table->addDataField("name", new db\VarCharType(50));
    $table->addDataField("first_name", new db\VarCharType(50));
    $table->addDataField("birthday", db\DateType::get());
    $table->addDataField("is_developer", db\BooleanType::get());

    $table = new enorm\dbmodel\Table($db, "hobbies");
    $table->addKeyField("person_id", db\IntegerType::get());
    $table->addKeyField("hobby_num", db\IntegerType::get());
    $table->addDataField("name", new db\VarCharType(50));

    return $db;

}