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
        }else{
            throw new \yii\web\NotFoundHttpException("Utilizador não encontrado");
        }
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        $model->email = \Yii::$app->request->post('email');
        $model->password = \Yii::$app->request->post('password');
        $modelUser = User::findOne(['email' => $model->email]);

        if ($modelUser->status == 1) {
            throw new \yii\web\NotFoundHttpException("Esta conta foi apagada! Para mais informação contacte o suporte.");

        } else if ($modelUser->status == 0) {
            throw new \yii\web\NotFoundHttpException("Esta conta foi banida!");

        } else if ($modelUser->status == 9) {
            throw new \yii\web\NotFoundHttpException("Esta conta está inativa!");

        } else {
            if ($model->login()) {
                return $modelUser;
            } else {
                return null;
            }
        }
    }

    public function actionFavoritos($token)
    {
        $user = User::find()->where(['verification_token' => $token])->one();
        $favoritos = Favoritos::find()->where(['user_idUtilizador' => $user->id])->all();
        if ($favoritos == null) {
            return null;
        } else {
            foreach ($favoritos as $favorito) {
                $pontosTuristicosFavoritos[] = Pontosturisticos::find()->where(['id_pontoTuristico' => $favorito->pt_idPontoTuristico])->one();
            }
            foreach ($pontosTuristicosFavoritos as $pontoTuristicoFavorito){
                if ($pontoTuristicoFavorito->tm_idTipoMonumento != null) {
                    $pontoTuristicoFavorito->tm_idTipoMonumento = Tipomonumento::find()->where(['idTipoMonumento' => $pontoTuristicoFavorito->tm_idTipoMonumento])->one()->descricao;
                }
                if ($pontoTuristicoFavorito->ec_idEstiloConstrucao != null) {
                    $pontoTuristicoFavorito->ec_idEstiloConstrucao = Estiloconstrucao::find()->where(['idEstiloConstrucao' => $pontoTuristicoFavorito->ec_idEstiloConstrucao])->one()->descricao;
                }
                if ($pontoTuristicoFavorito->localidade_idLocalidade != null) {
                    $pontoTuristicoFavorito->localidade_idLocalidade = Localidade::find()->where(['id_localidade' => $pontoTuristicoFavorito->localidade_idLocalidade])->one()->nomeLocalidade;
                }
            }
            if($pontosTuristicosFavoritos!=null){
                return $pontosTuristicosFavoritos;

            }else{
                return null;
            }
        }

    }

    public function actionInfo($id)
    {
        $user = User::findOne(['id' => $id]);
        $userProfile = Userprofile::findOne(['id_userProfile' => $id]);

        if ($user != null && $userProfile != null) {

            return ['Utilizador' => [
                'ID Utilizador' => $userProfile->id_userProfile,
                'Nome' => $userProfile->primeiroNome . " " . $userProfile->ultimoNome,
                'Nome de Utilizador' => $user->username,
                'Email' => $user->email,
                'Data de Nascimento' => $userProfile->dtaNascimento,
                'Morada' => $userProfile->morada,
                'Localidade' => $userProfile->localidade,
                'Sexo' => $userProfile->sexo,
            ]];

        } else {
            throw new \yii\web\NotFoundHttpException("Utilizador não encontrado");

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
        /*
                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole('user');
                $auth->assign($authorRole, $user->getId());
        */

    }

    public function actionEditar($token)
    {
        $user = User::find()->where(['verification_token' => $token])->one();
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
    }

    public function actionUsername($user)
    {
        $user = User::findOne(['username' => $user]);

        if ($user != null) {

            return $user;

        } else {
            throw new \yii\web\NotFoundHttpException("Utilizador não encontrado");

        }
    }

    public function actionAddfavoritos()
    {
        $token = \Yii::$app->request->post('token');

        $user = User::find()->where(['verification_token' => $token])->one();

        $favorito = new Favoritos();

        $favorito->pt_idPontoTuristico = \Yii::$app->request->post('id_pontoTuristico');
        $favorito->user_idUtilizador = $user->id;

        $favorito->save(false);

    }

    public function actionRemoverfavoritos($id, $token)
    {

        $user = User::find()->where(['verification_token' => $token])->one();

        $favorito = Favoritos::find()->where(['pt_idPontoTuristico' => $id])
            ->andWhere(['user_idUtilizador' => $user->id])->one();

        $favorito->delete();

    }
}
