<?php !defined('BASEPATH') and exit('No direct script access allowed');


    class Autor_Model extends MY_Model {


        /**
         * name:        validate_form
         *
         * validate form data of a new autor
         *
         *
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function validate_form ($part = '') {
            if($part== ''){
                $this->form_validation->set_rules('vorname', 'Vorname', 'trim|required|xss_clean');
                $this->form_validation->set_rules('nachname', 'Nachname', 'trim|required|xss_clean');
                $this->form_validation->set_rules('biografie', 'Biografie', 'trim|required|xss_clean');
                $this->form_validation->set_rules('website', 'Website', 'trim |xss_clean');
                $this->form_validation->set_message('required', 'Bitte das Feld ausfüllen.');
                $this->form_validation->set_error_delimiters('<span class="fehler">', '</span>');
            }
            if($part== 1){
                $this->form_validation->set_rules('objekt', '', 'required|trim|xss_clean');
            }

            if ($this->form_validation->run() != false) {
                return true;
            }
            return false;
        }


    }

/* End of file autor_model.php */
/* Location: ./buchverwaltung/application/models/redaktion/autor_model.php */

