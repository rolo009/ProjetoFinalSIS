<?php

namespace app\controllers;

use app\models\Contactos;
use yii\rest\ActiveController;

/**
 * ContactosController implements the CRUD actions for Contactos model.
 */
class ContactosController extends Activecontroller
{
    public $modelClass = 'app\models\Contactos';

    public function actionMensagem($id)
    {

        $climodel = new $this->modelClass;
        $mensagem = $climodel::find()->where(['idContactos' => $id])->one();
        if ($mensagem != null) {


            if ($mensagem != null && $mensagem->status == 0) {
                $mensagem->status = 1;
                $mensagem->dataResposta = date('Y-m-d H:i:s');
                $mensagem->save();
            }

            if ($mensagem->status == 0) {
                $mensagem->status = "Mensagem não Lida";
            } elseif ($mensagem->status == 1) {
                $mensagem->status = "Mensagem Lida";
            }

            return ['Mensagem' => [
                'Nome' => $mensagem->nome,
                'Email' => $mensagem->email,
                'Data de Envio' => $mensagem->dataEnvioMensagem,
                'Data de Resposta' => $mensagem->dataResposta,
                'Assunto' => $mensagem->assunto,
                'Mensagem' => $mensagem->mensagem
            ]];
        } else {
            return "A mensagem especifica não se encontra disponivel";
        }
    }

    public function actionNaolidas()
    {

        $climodel = new $this->modelClass;
        $mensagens = $climodel::find()
            ->select(['idContactos', 'email', 'assunto', 'dataEnvioMensagem'])
            ->where(['status' => 0])
            ->orderBy('dataEnvioMensagem ASC')
            ->all();

        if ($mensagens != null) {
            return $mensagens;
        } else {
            return "Não há nenhuma mensagem não lida de momento!";
        }

    }

    public function actionLidas()
    {

        $climodel = new $this->modelClass;
        $mensagens = $climodel::find()
            ->select(['idContactos', 'email', 'assunto', 'dataEnvioMensagem'])
            ->where(['status' => 1])
            ->orderBy('dataEnvioMensagem ASC')
            ->all();

        if ($mensagens != null) {
            return $mensagens;
        } else {
            return "Não há nenhuma mensagem lida de momento!";
        }

    }

    public function actionRegisto()
    {
        $contactos = new Contactos();

        $contactos->nome = \Yii::$app->request->post('nome');
        $contactos->email = \Yii::$app->request->post('email');
        $contactos->assunto = \Yii::$app->request->post('assunto');
        $contactos->mensagem = \Yii::$app->request->post('mensagem');
        $contactos->status = 0;

        $contactos->save(false);

        if ($contactos->save() == true) {
            return true;
        } else {
            return false;
        }
    }

}
