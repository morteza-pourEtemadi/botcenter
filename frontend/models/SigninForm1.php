<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signin form 1
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 *
 */
class SigninForm1 extends Model
{
    public $id;
    public $username;
    public $password;
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'trim'],
            [['username', 'password'], 'required'],
            ['username', 'string', 'min' => 4],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'This username has already been taken.')],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Signs user in step 1.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signin()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = User::findOne(['id' => $this->id]);
        $user->username = $this->username;
        $user->password = $this->password;
        $user->setPassword($this->password);

        return $user->save() ? $user : null;
    }
}
