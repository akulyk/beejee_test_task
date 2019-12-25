<?php
use Illuminate\Database\Schema\Blueprint;
use database\migrations\MigrationAbstract;
use app\models\User;

class CreateUserTable extends MigrationAbstract
{
    public function up()
    {
        $this->schema->create((new User())->getTable(), function (Blueprint $table) {
            $table->increments(User::FIELD_ID);
            $table->string(User::FIELD_USERNAME)->unique();
            $table->string(User::FIELD_PASSWORD);
            $table->string(User::FIELD_TOKEN);
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop((new User())->getTable());
    }
}
