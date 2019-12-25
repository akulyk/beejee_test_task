<?php
use Illuminate\Database\Schema\Blueprint;
use database\migrations\MigrationAbstract;
use app\models\todo\Todo;

class CreateTodoTable extends MigrationAbstract
{
    public function up()
    {
        $this->schema->create((new Todo())->getTable(), function (Blueprint $table) {
            $table->increments(Todo::FIELD_ID);
            $table->string(Todo::FIELD_USERNAME);
            $table->string(Todo::FIELD_EMAIL);
            $table->string(Todo::FIELD_TEXT);
            $table->string(Todo::FIELD_STATUS);
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop((new Todo())->getTable());
    }
}
