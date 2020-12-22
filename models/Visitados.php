<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visitados".
 *
 * @property int $id_visitados
 * @property int $user_idUtilizador
 * @property int $pt_idPontoTuristico
 */
class Visitados extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_idUtilizador', 'pt_idPontoTuristico'], 'required'],
            [['user_idUtilizador', 'pt_idPontoTuristico'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_visitados' => 'Id Visitados',
            'user_idUtilizador' => 'User Id Utilizador',
            'pt_idPontoTuristico' => 'Pt Id Ponto Turistico',
        ];
    }
}
