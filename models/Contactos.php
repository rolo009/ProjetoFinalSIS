<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contactos".
 *
 * @property int $idContactos
 * @property string $nome
 * @property string $email
 * @property string $assunto
 * @property string $mensagem
 * @property string|null $dataEnvioMensagem
 * @property string|null $dataResposta
 * @property int $status
 */
class Contactos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contactos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'email', 'assunto', 'mensagem', 'status'], 'required'],
            [['dataEnvioMensagem', 'dataResposta'], 'safe'],
            [['status'], 'integer'],
            [['nome', 'email'], 'string', 'max' => 255],
            [['assunto'], 'string', 'max' => 60],
            [['mensagem'], 'string', 'max' => 6000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idContactos' => 'Id Contactos',
            'nome' => 'Nome',
            'email' => 'Email',
            'assunto' => 'Assunto',
            'mensagem' => 'Mensagem',
            'dataEnvioMensagem' => 'Data Envio Mensagem',
            'dataResposta' => 'Data Resposta',
            'status' => 'Status',
        ];
    }
}
