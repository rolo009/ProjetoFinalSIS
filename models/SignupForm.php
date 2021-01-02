<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirmPassword;
    public $primeiroNome;
    public $ultimoNome;
    public $dtaNascimento;
    public $morada;
    public $localidade;
    public $distrito;
    public $sexo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message'=>'O campo Nome de Utilizador não pode estar em branco!'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required', 'message'=>'O campo Email não pode estar em branco!'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este Email já se encontra registado!'],

            ['password', 'required', 'message'=>'O campo Palavra Passe não pode estar em branco!'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['confirmPassword', 'required', 'message'=>'O campo Confirmar Palavra Passe não pode estar em branco!'],
            ['confirmPassword', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['primeiroNome', 'required', 'message'=>'O campo Primeiro Nome não pode estar em branco!'],
            ['primeiroNome', 'string', 'max' => 255],

            ['ultimoNome', 'required', 'message'=>'O campo Último Nome não pode estar em branco!'],
            ['ultimoNome', 'string', 'max' => 255],

            ['dtaNascimento', 'required', 'message'=>'O campo Data de Nascimento não pode estar em branco!'],
            ['dtaNascimento', 'safe'],
            ['dtaNascimento', 'date', 'format' => 'yyyy-MM-dd'],

            ['morada', 'required', 'message'=>'O campo Morada não pode estar em branco!'],
            ['morada', 'string', 'max' => 255],

            ['localidade', 'required', 'message'=>'O campo Localidade não pode estar em branco!'],
            ['localidade', 'string', 'max' => 255],

            ['distrito', 'required', 'message'=>'O campo Distrito não pode estar em branco!'],
            ['distrito', 'string', 'max' => 255],


            ['sexo', 'required', 'message'=>'O campo Sexo não pode estar em branco!'],
            ['sexo', 'string', 'max' => 255],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $userProfile = new Userprofile();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $userProfile->primeiroNome = $this->primeiroNome;
        $userProfile->ultimoNome = $this->ultimoNome;
        $userProfile->dtaNascimento = $this->dtaNascimento;
        $userProfile->morada = $this->morada;
        $userProfile->localidade = $this->localidade;
        $userProfile->distrito = $this->distrito;
        $userProfile->sexo = $this->sexo;
        $user->save();
        $userProfile->id_user_rbac = $user->getId();

        $userProfile->save(false);

        $auth = \Yii::$app->authManager;
        $authorRole = $auth->getRole('user');
        $auth->assign($authorRole, $user->getId());

        if($user->save() &&$userProfile->save()){
            return true;
        }
        else{
            return false;
        }
    }
    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
