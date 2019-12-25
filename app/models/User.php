<?php namespace app\models;

use app\helpers\HashHelper;
use Illuminate\Database\Eloquent\Model;


/**
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $token
 * @property string $created_at
 * @property string $updated_at
 *
 */
class User extends Model
{
    const FIELD_ID = 'id';
    const FIELD_USERNAME = 'username';
    const FIELD_PASSWORD = 'password';
    const FIELD_TOKEN = 'token';
    const FIELD_CREATED_AT = 'created_at';
    const FIELD_UPDATED_AT = 'updated_at';

    protected $table = 'user';

    protected $fillable = [
        'username',
        'password',
        'token',
    ];

    protected $visible = ['username'];

    public function setPassword(string $password): void
    {
        $this->password = HashHelper::crypt($password);
    }

    public function getToken()
    {
        return $this->token;
    }

    public function comparePassword(string $password): bool
    {
        return $this->password === HashHelper::crypt($password);
    }

    public function generateToken(): void
    {
        $this->token = HashHelper::generate();
    }

    public function eraseToken(): void
    {
        $this->token = null;
    }
}
