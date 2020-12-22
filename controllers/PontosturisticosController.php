<?php

namespace app\controllers;

use app\models\Estiloconstrucao;
use app\models\Favoritos;
use app\models\Localidade;
use app\models\Pontosturisticos;
use app\models\Tipomonumento;
use app\models\Visitados;
use yii\helpers\VarDumper;
use yii\rest\ActiveController;

class PontosturisticosController extends Activecontroller
{
    public $modelClass = 'app\models\Pontosturisticos';

    public function actionPontoturisticodetails($id)
    {
        $pontoTuristico = Pontosturisticos::find()->where(['id_pontoTuristico' => $id])->andWhere(['status' => 1])->one();
        $tipoMonumentoPt = Tipomonumento::find()->where(['idTipoMonumento' => $pontoTuristico->tm_idTipoMonumento])->one();
        $estiloConstrucaoPt = Estiloconstrucao::find()->where(['idEstiloConstrucao' => $pontoTuristico->ec_idEstiloConstrucao])->one();
        $localidadePt = Localidade::find()->where(['id_localidade' => $pontoTuristico->localidade_idLocalidade])->one();
        return ['Detalhes' => [
            'ID Ponto Turistico' => $pontoTuristico->id_pontoTuristico,
            'Nome' => $pontoTuristico->nome,
            'Ano de Construção' => $pontoTuristico->anoConstrucao,
            'Descrição' => $pontoTuristico->descricao,
            'Foto' => $pontoTuristico->foto,
            'Tipo de Ponto Turistico' => $tipoMonumentoPt->descricao,
            'Estilo de Construção' => $estiloConstrucaoPt->descricao,
            'Localidade' => $localidadePt->nomeLocalidade,
            'Horário' => $pontoTuristico->horario,
            'Morada' => $pontoTuristico->morada,
            'Telefone' => $pontoTuristico->telefone,
            'Latitude' => $pontoTuristico->latitude,
            'Longitude' => $pontoTuristico->longitude,
        ]];
    }

    public function actionLocalidade($local)
    {
        $localidade = Localidade::findOne(['nomeLocalidade' => $local]);

        $pontosTuristicos = Pontosturisticos::find()
            ->where(['localidade_idLocalidade' => $localidade->id_localidade])
            ->all();

        return $pontosTuristicos;
    }

    public function actionEstiloconstrucao($estilo)
    {
        $estiloConstrucao = Estiloconstrucao::findOne(['descricao' => $estilo]);

        $pontosTuristicos = Pontosturisticos::find()
            ->where(['ec_idEstiloConstrucao' => $estiloConstrucao->idEstiloConstrucao])
            ->all();

        return $pontosTuristicos;
    }

    public function actionTipomonumento($tipo)
    {
        $tipoMonumento = Tipomonumento::findOne(['descricao' => $tipo]);

        $pontosTuristicos = Pontosturisticos::find()
            ->where(['tm_idTipoMonumento' => $tipoMonumento->idTipoMonumento])
            ->all();

        return $pontosTuristicos;
    }

    public function actionEstatisticas($id)
    {
        $favoritosContador = count(Favoritos::findAll(['pt_idPontoTuristico' => $id]));
        $visitadosContador = count(Visitados::findAll(['pt_idPontoTuristico' => $id]));

        $totalFavoritos = count(Favoritos::find()->all());
        $totalVisitados = count(Visitados::find()->all());

        if ($totalFavoritos != null) {
            $percentagemFavorito = ($favoritosContador / $totalFavoritos) * 100;
        } else {
            $percentagemFavorito = 0;
        }

        if ($totalVisitados != null) {
            $percentagemVisitado = ($visitadosContador / $totalVisitados) * 100;
        } else {
            $percentagemVisitado = 0;
        }

        $pontoTuristicoStat = Pontosturisticos::findOne(['id_pontoTuristico' => $id]);

        return ['Estatisticas' => [
            'ID Ponto Turistico' => $pontoTuristicoStat->id_pontoTuristico,
            'Nome' => $pontoTuristicoStat->nome,
            'Nº de Favoritos' => $favoritosContador,
            '% de Favoritos' => $percentagemFavorito,
            'Nº de Visitados' => $visitadosContador,
            '% de Visitados' => $percentagemVisitado
        ]];
    }


}
