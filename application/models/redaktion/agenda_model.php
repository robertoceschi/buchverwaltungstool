<?php !defined('BASEPATH') and exit('No direct script access allowed');


    class Agenda_Model extends MY_Model {


        /**
         * name:        validate_form
         *
         * validate form data for a new agenda entry
         *
         * param    string  $passwort   yes = validate passwort
         *                              no  = don't validate passwort
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function validate_form () {
            $this->form_validation->set_rules('datum', 'Datum', 'trim|required|xss_clean');
            $this->form_validation->set_rules('zeit', 'Zeit', 'trim|required|xss_clean');
            $this->form_validation->set_rules('titel', 'Titel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ort', 'Ort', 'trim|required|xss_clean');
            $this->form_validation->set_rules('beschreibung', 'Beschreibung', 'trim|xss_clean');
            $this->form_validation->set_rules('website', 'Webseite', 'trim|xss_clean');
            $this->form_validation->set_message('required', 'Bitte das Feld ausfüllen.');
            $this->form_validation->set_error_delimiters('<span class="fehler">', '</span>');

            if ($this->form_validation->run() != false) {
                return true;
            }
            return false;
        }

    }

/* End of file agenda_model.php */
/* Location: ./buchverwaltung/application/models/redaktion/agenda_model.php */
