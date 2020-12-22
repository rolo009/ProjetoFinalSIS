<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "localidade".
 *
 * @property int $id_localidade
 * @property string $nomeLocalidade
 * @property string $foto
 */
class Localidade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'localidade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomeLocalidade', 'foto'], 'required'],
            [['nomeLocalidade', 'foto'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_localidade' => 'Id Localidade',
            'nomeLocalidade' => 'Nome Localidade',
            'foto' => 'Foto',
        ];
    }
}
