<?php  !defined('BASEPATH') and exit('No direct script access allowed');


require_once APPPATH . 'controllers/redaktion/adminpage_controller.php';

class Medium extends Adminpage_controller {

    protected $sControllerName = '';
    protected $sTable = 'medium';
    protected $sUriUebersicht = 'redaktion/medienuebersicht/';

    public function __construct () {
        //Controller Name in Kleinbuchstaben
        $this->sControllerName = strtolower(__CLASS__);
        parent::__construct($this->sControllerName, true, true);

        // h1 in header.php
        $this->headerData['heading'] = 'Medium bearbeiten';
        $this->lang->load('upload', 'german');
    }


    /**
     * name:        index
     *
     * shows a blank form
     *
     *
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function index () {

        //--------------------------------------//
        // Leeres Formular ausgeben
        //--------------------------------------//
        $data['medium']      = '';
        $data['heading']     = 'Neues Medium:';
        $data['back_link']   = base_url() . $this->sUriUebersicht;
        $data['back_txt']    = 'zurück';
        $data['submit_text'] = 'Sichern';
        $data['format_idarr']= array();

        // Wenn url in der Session ist, dann als back_link ändern
        if($this->session->userdata('auflagedata')){
            $buchdata            = $this->session->userdata('auflagedata');
            $data['back_link']   = base_url() . $buchdata['url'];
        }
        //print_r($this->session->all_userdata());


        //--------------------------------------//
        // erstellt ein neues Medium
        //--------------------------------------//

        if ($this->input->post('speichern', true)) {

            //Check ob Form-Validierung korrekt
            if ($this->Model->validate_form() == false) {

                //Wenn Validierung nicht okay, wird nochmals Formular wird nochmals angezeigt
                parent::__renderAll($this->sControllerName, $data);
            }
            //Falls Validierung korrekt, wird der Eintrag in die DB geschrieben
            else {
                // Daten auslesen
                $aDataMedium = array('medium' => $this->input->post('medium', true));

                // Datensatz eintragen
                if ($medium_id = $this->Model->saveRecord($this->sTable, $aDataMedium)) {

                    $anz = (count($_POST)-2)/2;
                    for($i=0;$i<$anz;$i++){
                        $format = $this->input->post('format' . $i, true);
                        if($format !=''){
                            $this->Model->saveRecord('format', array('format' =>$format, 'medium_id' => $medium_id));
                        }
                    }


                    // wenn Buch-Daten in der Session sind diese ergänzen und zurückleiten
                    if($this->session->userdata('auflagedata')){
                        $auflagedata                = $this->session->userdata('auflagedata');
                        $auflagedata['medium_id']   = $medium_id;
                        $this->session->set_userdata('auflagedata', $auflagedata);
                        $auflagedata = $this->session->userdata('auflagedata');
                        redirect(base_url() . $auflagedata['url']);
                    }
                    else{
                        redirect(base_url() . 'redaktion/medienuebersicht');
                    }
                }

                // Falls der Eintrag bereits besteht wird nochmals das Formular angezeigt
                // und eine Fehlermeldung ausgegeben
                else {
                    $data['message'] = 'Eintrag existiert bereits.';
                    parent::__renderAll($this->sControllerName, $data);
                }
            }
        }
        // Formatfelder hinzufügen
        elseif ($this->input->post('formathinzu', true) && $this->Model->validate_form() == true) {
            $data['medium'] = $this->input->post('medium');
            $anz_formate = (count($this->input->post()) -2)/2;


            for($i=0;$i<$anz_formate+1;$i++){

                $data['formatarr'][$i] = $this->input->post('format' . $i, true);
                $data['format_idarr'][$i] = $this->input->post('format' . $i, true);
            }

            parent::__renderAll($this->sControllerName, $data);
        }
        // Formatfelder entfernen
        elseif(($this->input->post('formatweg', true) || $this->input->post('formatweg', true) == 0) && $this->Model->validate_form() == true){
            $data['medium'] = $this->input->post('medium');
            $format_nr = $this->input->post('formatweg', true);
            $anz_formate = (count($this->input->post()) -2)/2;

            for($i=0;$i<$anz_formate;$i++){

                if($i != $format_nr){
                    $data['formatarr'][] = $this->input->post('format' . $i, true);
                    $data['format_idarr'][] = $this->input->post('format' . $i, true);
                }

            }
            parent::__renderAll($this->sControllerName, $data);
        }
        // leeres Formular zurückgeben
        else {
            parent::__renderAll($this->sControllerName, $data);
        }
    }


    /**
     * name:        edit
     *
     * shows a form with editable row-entries
     *
     * @param       integer  $medium_id      medium_id
     * @param       integer  $current_page   of the overview pagination
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function edit ($medium_id = -1, $current_page = 0) {

        //----------------------------------------------//
        // gespeicherten Eintrag auslesen und editieren
        //----------------------------------------------//

        // Eintrag wird herausgelesen

        if ($medium_id != -1) {

            if(!($_POST)){
                // passende Einträge holen und aufbereiten
                $where_array        = array('medium_id'=> $medium_id);
                $data               = $this->Model->getRow($this->sTable, '', $where_array);
                $format     = $this->Model->getRows('format', 'format, format_id', $where_array);
                foreach($format AS $v){
                    $data['formatarr'][]    = $v['format'];
                    $data['format_idarr'][] = $v['format_id'];
                }
            }
            $data['heading']     = '<span class="add-on"><i class="icon-edit"></i></span> Medium editieren';
            $data['submit_text'] = 'Änderung speichern';
            $data['back_txt']    = 'zurück';
            $data['back_link']   = base_url() . $this->sUriUebersicht . 'index/' . $current_page;

            // Einträge speichern
            if ($this->input->post('speichern', true) && $this->Model->validate_form($medium_id) != false) {

                // Medium speichern
                $aData = array('medium' => $this->input->post('medium', true));
                $this->Model->saveRecord($this->sTable, $aData, $medium_id, 'medium_id');

                // Formate speichern
                $this->Model->deleteRecord('format', $medium_id, 'medium_id');
                $anz_formate = (count($_POST)-2)/2;
                for($i=0;$i<$anz_formate;$i++){
                    $format     = $this->input->post('format' . $i, true);
                    $format_id  = $this->input->post('format_id' . $i, true);
                    if($format != ''){
                        if($format_id>0){
                            $this->Model->saveRecord('format', array('format' => $format, 'format_id' => $format_id, 'medium_id' => $medium_id));
                        }
                        else{
                            $this->Model->saveRecord('format', array('format' => $format, 'medium_id' => $medium_id));
                        }
                    }
                }

                //Eintrag anzeigen
                redirect(base_url() . $this->sUriUebersicht . 'index/' . $current_page);

            }
            // Formatfelder hinzufügen
            if ($this->input->post('formathinzu', true) && $this->Model->validate_form($medium_id) != false) {
                $data['medium'] = $this->input->post('medium', true);
                $anz_formate    = (count($this->input->post()) -2)/2;


                for($i=0;$i<$anz_formate;$i++){
                    $data['formatarr'][$i]      = $this->input->post('format' . $i, true);
                    $data['format_idarr'][$i]   = $this->input->post('format_id' . $i, true);
                }
                $data['formatarr'][$i]      = '';
                $data['format_idarr'][$i]   = -$i;

                parent::__renderAll($this->sControllerName, $data);
            }
            // Formatfelder entfernen
            if(($this->input->post('formatweg', true) || $this->input->post('formatweg', true) == 0) && $this->Model->validate_form($medium_id) != false){
                $data['medium'] = $this->input->post('medium', true);
                $format_nr = $this->input->post('formatweg', true);
                $anz_formate = (count($this->input->post()) -2)/2;
                for($i=0;$i<$anz_formate;$i++){

                    if($i != $format_nr){
                        $data['formatarr'][]    = $this->input->post('format' . $i, true);
                        $data['format_idarr'][] = $this->input->post('format_id' . $i, true);
                    }
                }
                parent::__renderAll($this->sControllerName, $data);
            }
            // Formular mit Values anzeigen
            else {

                parent::__renderAll($this->sControllerName, $data);
            }
        } // wenn keine ID gewählt ist
        else {
            $this->index();
        }


    }




    /**
     * name:        delete
     *
     * deletes the current row-entries
     *
     * @param       integer  $id            User id
     * @param       integer  $current_page  of the overview pagination
     * @param
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function delete ($id = '', $current_page = '') {

        //--------------------------------------//
        // zeigt Löschen Formular an
        //--------------------------------------//

        if ($id != '') {

            // Wenn Posteintrag vorhanden
            if($this->input->post('objekt', true) && $this->Model->validate_form($id, 2) == true){

                if ($this->Model->deleteRecord($this->sTable, $id, $this->sTable . '_id') === true) {
                    $this->Model->deleteRecord('format', $id, $this->sTable . '_id');
                    $data['heading'] = '';
                    $data['msg']      = 'Medium gelöscht';
                    $data['link']     = base_url() . $this->sUriUebersicht;
                    $data['link_txt'] = 'zurück';


                    $this->headerData['meta'] = array('<meta http-equiv="refresh" content="1; URL=' .
                        base_url() . $this->sUriUebersicht . 'index/' . $current_page . '">');
                    parent::__renderAll('message', $data);
                }
            }
            else{
                $bucharr = $this->Model->getRows('buch', 'buchtitel, buch.buch_id', array('medium_id' => $id), array('auflage' => 'auflage.buch_id = buch.buch_id'));
                if(count($bucharr)>0){

                    $data['description'] = 'Folgender Eintrag kann momentan nicht gelöscht werden: ';
                    $data['back_txt']    = 'abbrechen';
                    $data['back_link']   = base_url() . $this->sUriUebersicht;
                    $data['delete']      = 'delete';
                    $data['objekt']      = $this->Model->getOne('medium', 'medium', 'medium_id =' . $id);
                    $data['description2']= 'Löschen oder ändern Sie zuerst folgende Bücher:';

                    $data['bucharr'] = $bucharr;

                }
                else{

                    $data['submit_text'] = 'Eintrag löschen';
                    $data['submit_link'] = '';
                    $data['description'] = 'Folgender Eintrag löschen: ';
                    $data['back_txt']    = 'abbrechen';
                    $data['back_link']   = base_url() . $this->sUriUebersicht;
                    $data['delete']      = 'delete';
                    $data['objekt']      = $this->Model->getOne('medium', 'medium', 'medium_id =' . $id);
                }

                $data['heading']     = '<span class="add-on"><i class="icon-trash" alt="löschen" title="löschen"></i></span> Medium löschen';
                parent::__renderAll('loeschen', $data);
            }
        }
        else {
            // keine id ausgewählt -> Fehlermeldung anzeigen
            $data['heading']  = 'Medium löschen:';
            $data['msg']      = 'kein Medium ausgewählt';
            $data['linktxt'] = 'zurück';
            $data['link']     = base_url() . $this->sUriUebersicht;
            parent::__renderAll('message', $data);
        }
    }
}

/* End of file medium.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/medium.php */