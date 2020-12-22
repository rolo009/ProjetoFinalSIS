<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estiloconstrucao".
 *
 * @property int $idEstiloConstrucao
 * @property string $descricao
 *
 * @property Pontosturisticos[] $pontosturisticos
 */
class Estiloconstrucao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estiloconstrucao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao'], 'required'],
            [['descricao'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idEstiloConstrucao' => 'Id Estilo Construcao',
            'descricao' => 'Descricao',
        ];
    }

    /**
     * Gets query for [[Pontosturisticos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPontosturisticos()
    {
        return $this->hasMany(Pontosturisticos::className(), ['ec_idEstiloConstrucao' => 'idEstiloConstrucao']);
    }
}
