<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\validators\EmailValidator;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $avatar
 * @property string $bio
 * @property string $settings
 * @property string $phone
 * @property string $address
 * @property integer $status
 * @property integer $role
 * @property integer $department
 * @property integer $complete
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_VERIFIED = 1;
    const STATUS_VERIFIED_ACTIVE = 2;

    const ROLE_MASTER = 0;
    const ROLE_ADMIN = 1;
    const ROLE_EXPERT = 2;
    const ROLE_USER = 3;

    const USER_NOT_COMPLETED = 0;
    const USER_COMPLETED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    /**
    * @property string $address
    */
        return [
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_EXPERT, self::ROLE_USER]],
            ['status', 'default', 'value' => self::STATUS_NOT_VERIFIED],
            ['status', 'in', 'range' => [self::STATUS_VERIFIED_ACTIVE, self::STATUS_NOT_VERIFIED, self::STATUS_DELETED]],
            ['complete', 'default', 'value' => self::USER_NOT_COMPLETED],
            ['complete', 'in', 'range' => [self::USER_NOT_COMPLETED, self::USER_COMPLETED]],
            ['department', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_VERIFIED_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_VERIFIED_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_VERIFIED_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function emailVerifier()
    {
        $code1 = base64_encode($this->email);
        $code2 = base64_encode('email:' . $code1);

        return $code2;
    }

    /**
     * @param $verifier
     * @return null|static
     */
    public static function findByVerifier($verifier)
    {
        $code2 = base64_decode($verifier);
        $codes = explode(':', $code2);
        $email = base64_decode($codes[1]);
        $validator = new EmailValidator();

        if ($validator->validate($email)) {
            return static::findOne([
                'email' => $email,
                'status' => self::STATUS_NOT_VERIFIED,
            ]);
        } else {
            return null;
        }
    }
}
