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

    public function actionEditar($token)
    {
        $user = User::find()->where(['verification_token' => $token])->one();
        if ($user != null) {
            $userProfile = Userprofile::find()->where(['id_user_rbac' => $user->id])->one();

            $user->username = \Yii::$app->request->post('username');
            $user->email = \Yii::$app->request->post('email');
            $user->setPassword(\Yii::$app->request->post('password'));
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

    public function actionUsername($user)
    {
        $user = User::find()->where(['username' => $user])->one();

        if ($user != null) {

            return $user;

        } else {
            return "Utilizador não encontrado";

        }
    }
}
