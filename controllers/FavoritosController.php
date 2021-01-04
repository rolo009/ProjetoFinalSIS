<?php

namespace app\controllers;

use app\models\Estiloconstrucao;
use app\models\Favoritos;
use app\models\Localidade;
use app\models\Pontosturisticos;
use app\models\Tipomonumento;
use app\models\User;
use yii\rest\ActiveController;

class FavoritosController extends Activecontroller
{
    public $modelClass = 'app\models\Favoritos';

    public function actionInfo($token)
    {
        $user = User::find()->where(['verification_token' => $token])->one();
        $favoritos = Favoritos::find()->where(['user_idUtilizador' => $user->id])->all();
        if ($favoritos == null) {
            return null;
        } else {
            foreach ($favoritos as $favorito) {
                $pontosTuristicosFavoritos[] = Pontosturisticos::find()->where(['id_pontoTuristico' => $favorito->pt_idPontoTuristico])->one();
            }
            foreach ($pontosTuristicosFavoritos as $pontoTuristicoFavorito) {
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
            if ($pontosTuristicosFavoritos != null) {
                return $pontosTuristicosFavoritos;

            } else {
                return null;
            }
        }

    }

    public function actionAdd()
    {
        $token = \Yii::$app->request->post('token');

        $user = User::find()->where(['verification_token' => $token])->one();
        if ($user != null) {
            $favorito = new Favoritos();

            $favorito->pt_idPontoTuristico = \Yii::$app->request->post('id_pontoTuristico');
            $favorito->user_idUtilizador = $user->id;

            $favorito->save(false);

            if($favorito->save() == true){
                return true;
            }else{
                return null;
            }
        }else{
            return "Utilizador não encontrado";
        }

    }

    public function actionRemover($id, $token)
    {
        $user = User::find()->where(['verification_token' => $token])->one();
        if ($user != null) {
            $favorito = Favoritos::find()->where(['pt_idPontoTuristico' => $id])
                ->andWhere(['user_idUtilizador' => $user->id])->one();

            if($favorito->delete() == true){
                return true;
            }else{
                return null;
            }
        }else{
            return "Utilizador não encontrado";
        }
    }

}
