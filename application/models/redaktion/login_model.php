<?php !defined('BASEPATH') and exit('No direct script access allowed');


class Login_Model extends MY_Model {

    /**
     * name:        validate
     *
     * validate username and passwort of the login form
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function validate () {
        $this->db->where('benutzername', $this->input->post('benutzername'));
        $this->db->where('passwort', md5($this->input->post('passwort')));
        //checkt ob der Benutzer nicht auf inaktiv('psv') gesetzt wurde
        $this->db->where('status !=',  'psv');
        //Daten werden der Tabelle bvs_nutzer übergeben und verglichen
        $query = $this->db->get('bvs_nutzer');

        //Falls korrekt und 1 Zeile auch wirklich so wird ein true zurückgeliefert
        if ($query->num_rows == 1) {

            return true;
        }


    }

    //ajax validierung//
    /*public function check_user_exist ($usr) {

        $this->db->where('benutzername', $usr);
        $query = $this->db->get('bvs_nutzer');
        if ($query->num_rows() > 0) {
            //wird an controller / methode check_user zurückgegeben
            return true;
        } else {
            return false;

        }
    }*/







    }



/* End of file login_model.php */
/* Location: ./buchverwaltung/application/models/redaktion/login_model.php */
