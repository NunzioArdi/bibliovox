<?php


namespace bibliovox\models;


use Exception;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'idU';
    protected $table = 'utilisateur';

    static function getID($char)
    {
        $res[] = null;
        $tmp = Utilisateur::where("nom", "=", "$char")->get();
        foreach ($tmp as $r) {
            if (!is_null($r))
                array_push($res, $r->idU);
        }

        $tmp = Utilisateur::where("prenom", "=", "$char")->get();
        foreach ($tmp as $r) {
            if (!is_null($r))
                array_push($res, $r->idU);
        }

        $tmp = Utilisateur::where("mail", "=", "$char")->get();
        foreach ($tmp as $r) {
            if (!is_null($r))
                array_push($res, $r->idU);
        }

        $tmp = Utilisateur::where("idU", "=", "$char")->get();
        foreach ($tmp as $r) {
            if (!is_null($r))
                array_push($res, $r->idU);
        }

        return $res;
    }

    /**
     * Ajoute un nouvelle utilisateur
     * @param string $firstname le prénom
     * @param string $lastname le nom
     * @param string $pwdhash le mot de passe hasher
     * @param int $grade le grade
     * @param string $mail l'e-mail
     * @param mixed $pp la photo de profile
     * @return Utilisateur
     * @throws Exception
     */
    static function createUser(string $firstname, string $lastname, string $pwdhash, int $grade, string $mail, $pp = null){

        $res = false;
        $newUtilisateur = new Utilisateur();
        $newUtilisateur->nom = $firstname;
        $newUtilisateur->prenom = $lastname;
        $newUtilisateur->password = $pwdhash;
        $newUtilisateur->mail = $mail;
        $newUtilisateur->idG = $grade;
        //TODO photo de profil

        try {
            $res = $newUtilisateur->save();
        } catch (\PDOException $e) {
            echo $e;
        }

        if ($res == false) {
            throw new \Exception('L\'utilisateur n\'a pu être créé');
        }
        return $newUtilisateur;
    }

    /**
     * Vérifie les identifiants de connection
     * @param $firstname
     * @param $lastname
     * @param $password
     * @return array idU et grade si identifiant correct, sinon [-1]
     * @todo je sais c'est pas obti
     */
    static function login($email, $password)
    {
        $tmp = Utilisateur::where('mail', 'like', $email)->get();

        foreach ($tmp as $r) {
            if (!is_null($r)){
                if (password_verify($password, $r->password))
                    return [$r->idU, $r->idG];
            }
        }
        return [-1];
    }
}