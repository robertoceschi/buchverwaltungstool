<?php !defined('BASEPATH') and exit('No direct script access allowed');


class Nutzer_Model extends MY_Model {



    /**
     * name:        validate_form
     *
     * validate form data of a new nutzer
     *
     * param    string  $passwort   yes = validate passwort
     *                              no  = don't validate passwort
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function validate_form($passwort = 'yes'){
        $this->form_validation->set_rules('vorname', 'Vorname', 'trim|required|xss_clean');
        $this->form_validation->set_rules('nachname', 'Nachname', 'trim|required|xss_clean');
        $this->form_validation->set_rules('emailadresse', 'Email Addresse', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('benutzername', 'Benutzername', 'trim|required|min_length[4]|xss_clean', 'is_unique');
        if($passwort == 'yes'){
            $this->form_validation->set_rules('passwort', 'Passwort', 'trim|required|min_length[4]|max_length[32]|xss_clean');
            $this->form_validation->set_rules('passwort2', 'Passwort bestätigen', 'trim|required|matches[passwort]|xss_clean');
            $this->form_validation->set_message('matches', 'Bitte Passwort überprüfen.');
        }
        $this->form_validation->set_message('required', 'Bitte das Feld ausfüllen.');
        $this->form_validation->set_message('valid_email', 'Bitte eine gültige Emailadresse eingeben.');
        $this->form_validation->set_message('min_length', '%s muss aus mindestens 4 Zeichen bestehen.');
        $this->form_validation->set_error_delimiters('<span class="fehler">', '</span>');

        if ($this->form_validation->run() != false) {
            return true;
        }
        return false;
    }







    }

/* End of file nutzer_model.php */
/* Location: ./buchverwaltung/application/models/redaktion/nutzer_model.php */
