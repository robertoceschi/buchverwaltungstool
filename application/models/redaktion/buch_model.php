<?php !defined('BASEPATH') and exit('No direct script access allowed');


class Buch_Model extends MY_Model {



    /**
     * name:        validate_form
     *
     * validate form data of a new nutzer
     *
     * param    string  $part       witch part should be controlled
     * parm     strin   $buch_id    exist a buch_id? If not: -1
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function validate_form($part, $buch_id = -1, $anz = ''){

        if($part == 0){
            $this->form_validation->set_rules('buchauswahl', 'Buchauswahl', 'trim|xss_clean|required');
        }

        if($part == 1){
            if($buch_id == -1){
                $this->form_validation->set_rules('buchtitel', 'Buchtitel*', 'trim|xss_clean|required|is_unique[buch.buchtitel]');
            }
            else{
                $this->form_validation->set_rules('buchtitel', 'Buchtitel*', 'trim|xss_clean|required');
            }
            $this->form_validation->set_rules('untertitel', 'Untertitel*', 'trim|required|xss_clean');
            $this->form_validation->set_rules('beschreibung', 'Beschreibung*', 'trim|required|xss_clean');
            $this->form_validation->set_rules('autor', 'Autor*', 'required|xss_clean');
            $this->form_validation->set_rules('themenkreis', 'Themenkreise*', 'required|xss_clean');
            $this->form_validation->set_rules('edition_id', 'Edition*', 'required|xss_clean|numeric|greater_than[0]');
            $this->form_validation->set_rules('genre_id', 'Genre*', 'required|xss_clean|numeric|greater_than[0]');
            $this->form_validation->set_rules('status', 'Status*', 'required|xss_clean|required');
            $this->form_validation->set_rules('buch_id', '', 'required|xss_clean|numeric');
            $this->form_validation->set_rules('current_page', '', 'xss_clean|trim');
            $this->form_validation->set_rules('location', '', 'trim|xss_clean');
        }

        if($part == 2 && $anz !=''){

            for($i=0;$i<$anz;$i++){
                $this->form_validation->set_rules('pressetext' . $i, 'Pressetext' . $i, 'trim|xss_clean');
                $this->form_validation->set_rules('pressetext_id' . $i, 'Pressetext_id' . $i, 'required|numeric|trim|xss_clean');
                $this->form_validation->set_rules('status' . $i, 'Status' . $i, 'required|trim|xss_clean');
            }
            $this->form_validation->set_rules('buch_id', '', 'required|xss_clean|numeric');
            $this->form_validation->set_rules('current_page', '', 'xss_clean|trim');
            $this->form_validation->set_rules('location', '', 'trim|xss_clean');
            $this->form_validation->set_rules('weiter', '', 'trim|xss_clean');
            $this->form_validation->set_rules('textfeldneu', '', 'trim|xss_clean');
        }
        if($part == 3.1){
            $this->form_validation->set_rules('auflage_id', 'Auflage', 'required|xss_clean|numeric');
        }
        if($part == 3.2){
            $this->form_validation->set_rules('medium_id', 'Medium*', 'required|xss_clean|numeric');
            $this->form_validation->set_rules('format_id', 'Format*', 'xss_clean|numeric');
            $this->form_validation->set_rules('status', 'Status*', 'required|xss_clean');
        }
        if($part == 3){
            $this->form_validation->set_rules('auflage', '', 'trim|xss_clean|numeric|required|greater_than[0]');
            $this->form_validation->set_rules('menge', 'Anzahl Artikel', 'trim|xss_clean|numeric');
            $this->form_validation->set_rules('vergriffen', 'vergriffen*', 'trim|required|xss_clean');
            $this->form_validation->set_rules('erscheinungsdatum', 'Erscheinungsdatum*', 'required|xss_clean|trim');
            $this->form_validation->set_rules('preis_sfr', 'Preis in SFR*', 'required|xss_clean|numeric|greater_than[0]');
            $this->form_validation->set_rules('preis_euro', 'Preis in Euro*', 'required|xss_clean|numeric|greater_than[0]');
            $this->form_validation->set_rules('isbn', 'ISBN*', 'required|callback_valid_isbn');
            //$this->form_validation->set_rules('bild', 'Cover hochladen', '??');
            $this->form_validation->set_rules('mediumneu', 'Neues Medium', 'trim|xss_clean');
            $this->form_validation->set_rules('medium_id', 'Medium*', 'required|xss_clean|numeric|greater_than[0]');
            $this->form_validation->set_rules('format_id', 'Format', 'xss_clean|trim|numeric');
            $this->form_validation->set_rules('status', 'Status*', 'required|xss_clean|required');

        }
        if($part == 4){
            $this->form_validation->set_rules('buch_id', 'Buchtitel*', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('buchauswahl', 'buchauswahl', 'trim|required|xss_clean');
        }
        if($part == 5){
            $this->form_validation->set_rules('buch_id', 'buch_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('auflage_id', 'auflage_id', 'trim|xss_clean');
        }

        // Fehlermeldungen
        $this->form_validation->set_message('numeric', 'bitte nur Zahlen eintragen');
        $this->form_validation->set_message('required', 'bitte das Feld ausfüllen');
        $this->form_validation->set_message('greater_than', 'bitte Option auswählen');
        $this->form_validation->set_message('is_unique', 'Eintrag ist bereits vorhanden. Keine doppelten Einträge möglich.');
        $this->form_validation->set_error_delimiters('<span class="fehler">', '</span>');

        if ($this->form_validation->run() != false) {
            return true;
        }
        return false;
    }


}

/* End of file buch_model.php */
/* Location: ./buchverwaltung/application/models/redaktion/buch_model.php */
