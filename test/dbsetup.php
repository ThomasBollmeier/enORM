<?php

use tbollmeier\enorm\dbmodel as model;


function createTestDatabase() {

    $db = new model\Database("enormtest");

    $table = new model\Table($db, "persons");
    $table->addKeyField("id", model\IntegerType::get());
    $table->addDataField("name", new model\VarCharType(50));
    $table->addDataField("first_name", new model\VarCharType(50));
    $table->addDataField("birthday", model\DateType::get());
    $table->addDataField("is_developer", model\BooleanType::get());

    $table = new model\Table($db, "hobbies");
    $table->addKeyField("person_id", model\IntegerType::get());
    $table->addKeyField("hobby_num", model\IntegerType::get());
    $table->addDataField("name", new model\VarCharType(50));

    return $db;

}