<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;

/**
 * Signin form 2
 *
 * @property integer $id
 * @property UploadedFile $avatar
 * @property string $bio
 * @property string $phone
 * @property string $address
 *
 */
class SigninForm2 extends Model
{
    public $id;
    public $avatar;
    public $bio;
    public $phone;
    public $address;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'address'], 'required'],
            ['avatar', 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024*1024],
            ['bio', 'string', 'max' => 200],
            ['phone', 'string', 'length' => 13],
            ['address', 'string', 'max' => 100],
        ];
    }

    /**
     * Signs user in step 2.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signin()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = User::findOne(['id' => $this->id]);

        $user->bio = $this->bio;
        $user->phone = $this->phone;
        $user->address = $this->address;
        if ($this->avatar !== null) {
            $this->avatar->saveAs('profile-images/' . $user->username . '.' . $this->avatar->extension);
            $user->avatar = $user->username . '.' . $this->avatar->extension;
        }
        $user->complete = 1;

        $auth = \Yii::$app->authManager;
        $userRole = $auth->getRole('user');
        $auth->assign($userRole, $user->id);

        return $user->save() ? $user : null;
    }
}
