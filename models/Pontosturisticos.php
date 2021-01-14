<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pontosturisticos".
 *
 * @property int $id_pontoTuristico
 * @property string $nome
 * @property string|null $anoConstrucao
 * @property string $descricao
 * @property string $foto
 * @property int|null $tm_idTipoMonumento
 * @property int|null $ec_idEstiloConstrucao
 * @property int|null $localidade_idLocalidade
 * @property string|null $horario
 * @property string|null $morada
 * @property string|null $telefone
 * @property int $status
 * @property string $latitude
 * @property string $longitude
 *
 * @property Favoritos[] $favoritos
 * @property Estiloconstrucao $ecIdEstiloConstrucao
 * @property Tipomonumento $tmIdTipoMonumento
 * @property Localidade $localidadeIdLocalidade
 * @property Ratings[] $ratings
 * @property Visitados[] $visitados
 */
class Pontosturisticos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pontosturisticos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'descricao', 'foto', 'status', 'latitude', 'longitude'], 'required'],
            [['tm_idTipoMonumento', 'ec_idEstiloConstrucao', 'localidade_idLocalidade', 'status'], 'integer'],
            [['nome', 'anoConstrucao', 'foto', 'horario', 'morada', 'telefone', 'latitude', 'longitude'], 'string', 'max' => 255],
            [['descricao'], 'string', 'max' => 6000],
            [['ec_idEstiloConstrucao'], 'exist', 'skipOnError' => true, 'targetClass' => Estiloconstrucao::className(), 'targetAttribute' => ['ec_idEstiloConstrucao' => 'idEstiloConstrucao']],
            [['tm_idTipoMonumento'], 'exist', 'skipOnError' => true, 'targetClass' => Tipomonumento::className(), 'targetAttribute' => ['tm_idTipoMonumento' => 'idTipoMonumento']],
            [['localidade_idLocalidade'], 'exist', 'skipOnError' => true, 'targetClass' => Localidade::className(), 'targetAttribute' => ['localidade_idLocalidade' => 'id_localidade']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pontoTuristico' => 'Id Ponto Turistico',
            'nome' => 'Nome',
            'anoConstrucao' => 'Ano Construcao',
            'descricao' => 'Descricao',
            'foto' => 'Foto',
            'tm_idTipoMonumento' => 'Tm Id Tipo Monumento',
            'ec_idEstiloConstrucao' => 'Ec Id Estilo Construcao',
            'localidade_idLocalidade' => 'Localidade Id Localidade',
            'horario' => 'Horario',
            'morada' => 'Morada',
            'telefone' => 'Telefone',
            'status' => 'Status',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    /**
     * Gets query for [[Favoritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritos()
    {
        return $this->hasMany(Favoritos::className(), ['pt_idPontoTuristico' => 'id_pontoTuristico']);
    }

    /**
     * Gets query for [[EcIdEstiloConstrucao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEcIdEstiloConstrucao()
    {
        return $this->hasOne(Estiloconstrucao::className(), ['idEstiloConstrucao' => 'ec_idEstiloConstrucao']);
    }

    /**
     * Gets query for [[TmIdTipoMonumento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTmIdTipoMonumento()
    {
        return $this->hasOne(Tipomonumento::className(), ['idTipoMonumento' => 'tm_idTipoMonumento']);
    }

    /**
     * Gets query for [[LocalidadeIdLocalidade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidadeIdLocalidade()
    {
        return $this->hasOne(Localidade::className(), ['id_localidade' => 'localidade_idLocalidade']);
    }

    /**
     * Gets query for [[Ratings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(Ratings::className(), ['pt_idPontoTuristico' => 'id_pontoTuristico']);
    }

    /**
     * Gets query for [[Visitados]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitados()
    {
        return $this->hasMany(Visitados::className(), ['pt_idPontoTuristico' => 'id_pontoTuristico']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $id = $this->id_pontoTuristico;
        $nome = $this->nome;
        $anoConstrucao = $this->anoConstrucao;
        $descricao = $this->descricao;
        $foto = $this->foto;
        $tm_idTipoMonumento = $this->tm_idTipoMonumento;
        $ec_idEstiloConstrucao = $this->ec_idEstiloConstrucao;
        $localidade_idLocalidade = $this->localidade_idLocalidade;
        $horario = $this->horario;
        $morada = $this->morada;
        $telefone = $this->telefone;
        $latitude = $this->latitude;
        $longitude = $this->morada;

        $myObj = new \stdClass();
        $myObj->id = $id;
        $myObj->nome = $nome;
        $myObj->anoConstrucao = $anoConstrucao;
        $myObj->descricao = $descricao;
        $myObj->foto = $foto;
        $myObj->tm_idTipoMonumento = $tm_idTipoMonumento;
        $myObj->ec_idEstiloConstrucao = $ec_idEstiloConstrucao;
        $myObj->localidade_idLocalidade = $localidade_idLocalidade;
        $myObj->horario = $horario;
        $myObj->morada = $morada;
        $myObj->telefone = $telefone;
        $myObj->latitude = $latitude;
        $myObj->longitude = $longitude;

        $myJSON = json_encode($myObj);
        if ($insert)
            $this->FazPublish("INSERT", $myJSON);
        else
            $this->FazPublish("UPDATE", $myJSON);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $prod_id = $this->id_pontoTuristico;
        $prod_nome = $this->nome;
        $myObj = new \stdClass();
        $myObj->id = $prod_id;
        $myObj->nome = $prod_nome;
        $myJSON = json_encode($myObj);
        $this->FazPublish("DELETE", $myJSON);
    }

    public function FazPublish($canal, $msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = "ServerCultravel";
        $password = "";
        $client_id = "user-cultravel";

        $mqtt = new \app\mosquitto\phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug.output", "Time out!");
        }
    }
}
