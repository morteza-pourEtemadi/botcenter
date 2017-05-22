<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\InvalidParamException;
use frontend\models\SignupForm;
use frontend\models\SigninForm1;
use frontend\models\SigninForm2;
use frontend\models\ContactForm;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use common\models\bot\Subscribers;
use common\models\LoginForm;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'profile'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['profile'],
                        'allow' => true,
                        'roles' => ['user', 'expert', 'admin', 'master'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = User::findOne(['id' => Yii::$app->user->id]);
            if ($user->complete == 1) {
                return $this->goBack();
            } else {
                $param[] = $user->id;
                $param[] = 2;
                $params = base64_encode(Json::encode($param));
                return $this->redirect(['signin', 'p' => $params]);
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        $sub = new Subscribers([
            'user_id' => 123,
            'bot_id' => 12,
            'memberString' => ''
        ]);
        $sub->save();
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if ($model->sendActivationCode()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'an activation code is sent to {mail}. 
                    Please follow the instructions in your mail.', ['mail' => $user->email]));
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'there was a problem to send email verification code. please try later.'));
                    return $this->render('signup', [
                        'model' => $model,
                    ]);
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionEmailVerify($verification)
    {
        $user = User::findByVerifier($verification);
        if ($user == null) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'The verification code is not correct. Please try again with a valid url.'));
            return $this->goHome();
        }

        $user->status = User::STATUS_VERIFIED_ACTIVE;
        $user->save();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Your email address is verified successfully. Please complete your profile data now!'));
        $param[] = $user->id;
        $param[] = 1;
        $params = base64_encode(Json::encode($param));
        return $this->redirect(['signin', 'p' => $params]);
    }

    public function actionSignin($p)
    {
        $params = Json::decode(base64_decode($p));
        $uid = $params[0];
        $step = $params[1];

        if ($step == 1) {
            $model = new SigninForm1();
            $model->id = $uid;
            if ($model->load(Yii::$app->request->post())) {
                if ($user = $model->signin()) {
                    $param[] = $user->id;
                    $param[] = 2;
                    $params = base64_encode(Json::encode($param));
                    Yii::$app->user->login($user);
                    return $this->redirect(['signin', 'p' => $params]);
                }
            }

            return $this->render('signin1', [
                'model' => $model,
            ]);
        } elseif ($step == 2) {
            $model = new SigninForm2();
            $model->id = $uid;
            if ($model->load(Yii::$app->request->post())) {
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                if ($user = $model->signin()) {
                    return $this->redirect(Url::to(['site/profile']));
                }
            }

            return $this->render('signin2', [
                'model' => $model,
            ]);
        }

        return $this->goHome();
    }

    public function actionProfile($username = null)
    {
        if ($username == null || $username == '') {
            $user = User::findOne(['id' => Yii::$app->user->id]);
        } else {
            if ($user = User::findByUsername($username)) {
                if (!(Yii::$app->user->can('update_profile', ['user_id' => $user->id]))) {
                    if (Yii::$app->user->can('view_profile', ['user_id' => $user->id])) {
                        // 2
                        exit('456');
                    } else {
                        return $this->render('error', [
                            'name' => 'Not Found (#404)',
                            'message' => 'Page not found'
                        ]);
                    }
                }
            } else {
                return $this->render('error', [
                    'name' => 'Not Found (#404)',
                    'message' => 'Page not found'
                ]);
            }
        }

        if ($user->complete == 1) {
            //go on personal account
            exit('123');
        } else {
            $param[] = $user->id;
            $param[] = 2;
            $params = base64_encode(Json::encode($param));
            Yii::$app->user->login($user);
            return $this->redirect(['signin', 'p' => $params]);
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * @param User $user
     */
    public function guideUser($user)
    {

    }
}
