<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'This email address has already been taken.')],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

    /**
     * Sends an email with a link, for verifying email.
     *
     * @return bool whether the email was send
     */
    public function sendActivationCode()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_NOT_VERIFIED,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerification-html', 'text' => 'emailVerification-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['no-reply'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject(Yii::t('app', 'Welcome to Ultimate Developers. Please confirm your email'))
            ->send();
    }
}
