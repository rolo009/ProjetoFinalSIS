<?php

namespace app\controllers;

use app\models\Estiloconstrucao;
use app\models\Favoritos;
use app\models\Localidade;
use app\models\LoginForm;
use app\models\Pontosturisticos;
use app\models\Tipomonumento;
use app\models\User;
use app\models\Userprofile;
use app\models\SignupForm;
use Yii;
use yii\helpers\VarDumper;
use yii\rest\ActiveController;


/**
 * UserprofileController implements the CRUD actions for Userprofile model.
 */
class UserprofileController extends Activecontroller
{
    public $modelClass = 'app\models\Userprofile';
    public $modelClassUser = 'app\models\User';

    public function actionApagaruser($token)
    {
        $statusBan = 1;

        $user = User::findOne(['verification_token' => $token]);

        if ($user != null) {
            $user->status = $statusBan;
            $user->save(false);
        } else {
            throw new \yii\web\NotFoundHttpException("Utilizador não encontrado");
        }
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        $model->email = \Yii::$app->request->post('email');
        $model->password = \Yii::$app->request->post('password');
        $modelUser = User::find()->where(['email' => $model->email])->one();

        if ($modelUser->status != 10) {
            throw new \yii\web\NotFoundHttpException("Esta conta não possui os requisitos para que possa ser acedida!");

        } else {
            if ($model->login()) {
                return $modelUser;
            } else {
                return null;
            }
        }
    }

    public function actionInfo($id)
    {
        $user = User::findOne(['id' => $id]);
        $userProfile = Userprofile::find()->where(['id_user_rbac' => $user->id])->one();

        if ($user != null && $userProfile != null) {

            return [
                'ID Utilizador' => $userProfile->id_userProfile,
                'Nome' => $userProfile->primeiroNome . " " . $userProfile->ultimoNome,
                'Nome de Utilizador' => $user->username,
                'Email' => $user->email,
                'Data de Nascimento' => $userProfile->dtaNascimento,
                'Morada' => $userProfile->morada,
                'Localidade' => $userProfile->localidade,
                'Sexo' => $userProfile->sexo,
            ];
        } else {
            return "Utilizador não encontrado";

        }
    }

    public function actionRegisto()
    {
        $user = new User();
        $userProfile = new Userprofile();

        $email = User::find()->where(['email' => \Yii::$app->request->post('email')])->one();
        $username = User::find()->where(['username' => \Yii::$app->request->post('username')])->one();


        if ($username != null) {
            $erroUsername = 1;
            return $erroUsername;
        }
        if ($email != null) {
            $erroEmail = 0;
            return $erroEmail;
        }

        $user->username = \Yii::$app->request->post('username');;
        $user->email = \Yii::$app->request->post('email');;
        $user->setPassword(\Yii::$app->request->post('password'));
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $userProfile->primeiroNome = \Yii::$app->request->post('primeiroNome');
        $userProfile->ultimoNome = \Yii::$app->request->post('ultimoNome');
        $userProfile->dtaNascimento = \Yii::$app->request->post('dtaNascimento');
        $userProfile->morada = \Yii::$app->request->post('morada');
        $userProfile->localidade = \Yii::$app->request->post('localidade');
        $userProfile->distrito = \Yii::$app->request->post('distrito');
        $userProfile->sexo = \Yii::$app->request->post('sexo');
        $user->save(false);
        $userProfile->id_user_rbac = $user->getId();

        $userProfile->save(false);

        if ($user->save() == true && $userProfile->save() == true) {
            return true;
        } else {
            return false;
        }


    }

    public function actionEditar()
    {
        $user = User::find()->where(['verification_token' => \Yii::$app->request->post('token')])->one();

        if ($user != null) {
            $userProfile = Userprofile::find()->where(['id_user_rbac' => $user->id])->one();

            if ($user->username != \Yii::$app->request->post('username')) {
                $usernameSearch = User::find()->where(['username' => \Yii::$app->request->post('username')])->one();
                if ($usernameSearch == null) {
                    $user->username = \Yii::$app->request->post('username');
                } else {
                    return 1;
                }
            }
            if ($user->email != \Yii::$app->request->post('email')) {
                $emailSearch = User::find()->where(['email' => \Yii::$app->request->post('email')])->one();
                if ($emailSearch == null) {
                    $user->username = \Yii::$app->request->post('username');
                } else {
                    return 2;
                }
            }
            if (\Yii::$app->request->post('oldPassword') != "0") {
                if ($user->validatePassword(\Yii::$app->request->post('oldPassword')) == true) {
                    $user->setPassword(\Yii::$app->request->post('password'));
                } else {
                    return 0;
                }
            }
            $userProfile->primeiroNome = \Yii::$app->request->post('primeiroNome');
            $userProfile->ultimoNome = \Yii::$app->request->post('ultimoNome');
            $userProfile->dtaNascimento = \Yii::$app->request->post('dtaNascimento');
            $userProfile->morada = \Yii::$app->request->post('morada');
            $userProfile->localidade = \Yii::$app->request->post('localidade');
            $userProfile->distrito = \Yii::$app->request->post('distrito');
            $userProfile->sexo = \Yii::$app->request->post('sexo');
            $user->save(false);
            $userProfile->id_user_rbac = $user->getId();
            $userProfile->save(false);

            if ($user->save() == true && $userProfile->save() == true) {
                return true;
            }
        }
        return "Utilizador não encontrado/atualizado";
    }

    public function actionUser($token)
    {
        $user = User::find()->where(['verification_token' => $token])->one();

        $userprofile = Userprofile::find()->where(['id_userProfile' => $user->id])->one();

        if ($userprofile != null) {

            return [
                'primeiroNome' => $userprofile->primeiroNome,
                'ultimoNome' => $userprofile->ultimoNome,
                'username' => $user->username,
                'email' => $user->email,
                'dtaNascimento' => $userprofile->dtaNascimento,
                'morada' => $userprofile->morada,
                'localidade' => $userprofile->localidade,
                'distrito' => $userprofile->distrito,
                'sexo' => $userprofile->sexo,
            ];

        } else {
            return "Utilizador não encontrado";

        }
    }
}
