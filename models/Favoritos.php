<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "favoritos".
 *
 * @property int $id_favoritos
 * @property int $pt_idPontoTuristico
 * @property int $user_idUtilizador
 */
class Favoritos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favoritos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pt_idPontoTuristico', 'user_idUtilizador'], 'required'],
            [['pt_idPontoTuristico', 'user_idUtilizador'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_favoritos' => 'Id Favoritos',
            'pt_idPontoTuristico' => 'Pt Id Ponto Turistico',
            'user_idUtilizador' => 'User Id Utilizador',
        ];
    }
}
