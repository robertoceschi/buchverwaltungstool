<?php !defined('BASEPATH') and exit('No direct script access allowed');


class Themenkreis_Model extends MY_Model {

    /**
     * name:        validate_form
     *
     * validate form data of a new nutzer
     *
     * @param   $id     id of the entry
     *
     * @author          parobri.ch
     * @date            20120719
     */
    public function validate_form($id=-1, $part=1){

        // Validierung
        if($part == 1){

            $anz = count($this->Model->getRows('themenkreis', 'themenkreis', array('themenkreis'=> $this->input->post('themenkreis', true), 'themenkreis_id !=' =>$id)));
            if($anz <1 && $id!=-1){
                $this->form_validation->set_rules('themenkreis', '', 'required|trim|xss_clean');
            }
            else{
                $this->form_validation->set_rules('themenkreis', '', 'required|trim|xss_clean|is_unique[themenkreis.themenkreis]');
            }
        }
        if($part == 2){
            $this->form_validation->set_rules('objekt', '', 'required|trim|xss_clean');
        }


        // Fehlermeldungen
        $this->form_validation->set_message('required', 'bitte das Feld ausfüllen');
        $this->form_validation->set_message('is_unique', 'Eintrag ist bereits vorhanden. Keine doppelten Einträge möglich.');
        $this->form_validation->set_error_delimiters('<span class="fehler">', '</span>');

        if ($this->form_validation->run() != false) {
            return true;
        }
        return false;
    }

}

/* End of file themenkreis_model.php */
/* Location: ./buchverwaltung/application/models/redaktion/themenkreis_model.php */
