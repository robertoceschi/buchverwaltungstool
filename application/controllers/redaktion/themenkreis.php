<?php  !defined('BASEPATH') and exit('No direct script access allowed');


require_once APPPATH . 'controllers/redaktion/adminpage_controller.php';

class Themenkreis extends Adminpage_controller {

    protected $sControllerName = '';
    protected $sTable = 'themenkreis';
    protected $sUriUebersicht = 'redaktion/themenkreisuebersicht/';

    public function __construct () {
        //Controller Name in Kleinbuchstaben
        $this->sControllerName = strtolower(__CLASS__);
        parent::__construct($this->sControllerName, true, true);

        // h1 in header.php
        $this->headerData['heading'] = 'Themenkreis bearbeiten';
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
        $data['themenkreis']       = '';

        $data['heading']     = 'Neuer Themenkreis:';
        $data['back_link']   = base_url() . $this->sUriUebersicht;
        if($this->session->userdata('buchdata')){
            $buchdata = $this->session->userdata('buchdata');
            $data['back_link']   = base_url() . $buchdata['url'];
        }
        $data['back_txt']    = 'zurück';
        $data['submit_text'] = 'Sichern';

        //--------------------------------------//
        // erstellt ein neues Genre
        //--------------------------------------//

        if ($this->input->post()) {

            //Check ob Form-Validierung korrekt
            if ($this->Model->validate_form() == false) {

                //Wenn Validierung nicht okay, wird nochmals Methode index() aufgerufen und Formular wird nochmals angezeigt
                parent::__renderAll($this->sControllerName, $data);
            }
            //Falls Validierung korrekt, wird der Eintrag in die DB geschrieben
            else {
                // Daten auslesen
                $aData = array('themenkreis' => $this->input->post('themenkreis', true));

                // Datensatz eintragen
                if ($themenkreis_id = $this->Model->saveRecord($this->sTable, $aData)) {

                    // wenn Buch-Daten in der Session sind diese ergänzen und zurückleiten
                    if($this->session->userdata('buchdata')){
                        $buchdata                   = $this->session->userdata('buchdata');
                        $buchdata['themenkreis'][]  = $themenkreis_id;
                        $this->session->set_userdata('buchdata', $buchdata);
                        $buchdata = $this->session->userdata('buchdata');
                        redirect(base_url() . $buchdata['url']);
                    }
                    else{
                        redirect(base_url() . 'redaktion/themenkreisuebersicht');
                    }

                }

                // Falls der Eintrag bereits besteht wird nochmals das Formular angezeigt
                // und eine Fehlermeldung ausgegeben
                else {
                    $data['message'] = 'Eintrag existiert bereits.';
                    parent::__renderAll($this->sControllerName, $data);
                }
            }
        } else {

            parent::__renderAll($this->sControllerName, $data);
        }
    }


    /**
     * name:        edit
     *
     * shows a form with editable row-entries
     *
     * @param       integer  $id             User id
     * @param       integer  $current_page   of the overview pagination
     * @param       string   $account        expect ('own' = Logged in User /
     *                                              'other' = Other User)
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function edit ($id = '', $current_page = '', $account = 'edit') {

        //----------------------------------------------//
        // gespeicherten Eintrag auslesen und editieren
        //----------------------------------------------//

        // Eintrag wird herausgelesen

        if ($id != '') {
            // passender Eintrag holen
            $where_array = array('themenkreis_id'=> $id);
            $data        = $this->Model->getRow($this->sTable, '', $where_array);


            $data['heading']     = '<span class="add-on"><i class="icon-edit"></i></span> Autor editieren';
            $data['submit_text'] = 'Änderung speichern';
            $data['back_txt']    = 'zurück';
            $data['back_link']   = base_url() . $this->sUriUebersicht . 'index/' . $current_page;


            if ($this->input->post('themenkreis', true) && $this->Model->validate_form($id) != false) {

                $aData = array('themenkreis' => $this->input->post('themenkreis', true));
                $this->Model->saveRecord($this->sTable, $aData, $id, 'themenkreis_id');

                //Eintrag anzeigen
                redirect(base_url() . $this->sUriUebersicht . 'index/' . $current_page);

            } // Formular mit Values anzeigen
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
                    $data['heading'] = '';
                    $data['msg']      = 'Themenkreis gelöscht';
                    $data['link']     = base_url() . $this->sUriUebersicht;
                    $data['link_txt'] = 'zurück';


                    $this->headerData['meta'] = array('<meta http-equiv="refresh" content="1; URL=' .
                        base_url() . $this->sUriUebersicht . 'index/' . $current_page . '">');
                    parent::__renderAll('message', $data);
                }
            }
            else{
                $bucharr = $this->Model->getRows('buch', 'buchtitel, buch.buch_id', array('themenkreis_id' => $id), array('buch_themenkreis' => 'buch_themenkreis.buch_id = buch.buch_id'));
                if(count($bucharr)>0){

                    $data['description'] = 'Folgender Eintrag kann momentan nicht gelöscht werden: ';
                    $data['back_txt']    = 'abbrechen';
                    $data['back_link']   = base_url() . $this->sUriUebersicht;
                    $data['delete']      = 'delete';
                    $data['objekt']      = $this->Model->getOne('themenkreis', 'themenkreis', 'themenkreis_id =' . $id);
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
                    $data['objekt']      = $this->Model->getOne('themenkreis', 'themenkreis', 'themenkreis_id =' . $id);
                }

                $data['heading']     = '<span class="add-on"><i class="icon-trash" alt="löschen" title="löschen"></i></span> Themenkreis löschen';
                parent::__renderAll('loeschen', $data);
            }
        }
        else {
            // keine id ausgewählt -> Fehlermeldung anzeigen
            $data['heading']  = 'Themenkreis löschen:';
            $data['msg']      = 'kein Themenkreis ausgewählt';
            $data['linktxt'] = 'zurück';
            $data['link']     = base_url() . $this->sUriUebersicht;
            parent::__renderAll('message', $data);
        }
    }
}

/* End of file themenkreis.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/themenkreis.php */