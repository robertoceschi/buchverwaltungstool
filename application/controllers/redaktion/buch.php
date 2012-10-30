<?php  !defined('BASEPATH') and exit('No direct script access allowed');


require_once APPPATH . 'controllers/redaktion/adminpage_controller.php';

class Buch extends Adminpage_controller {

    protected $sControllerName  = '';
    protected $sTable           = 'buch';
    protected $sUriUebersicht   = 'redaktion/buecheruebersicht/';

    public function __construct() {
        //Controller Name in Kleinbuchstaben
        $this->sControllerName = strtolower(__CLASS__);
        parent::__construct($this->sControllerName, true, true);

        // h1 in header.php
        $this->headerData['heading'] = 'Bücher bearbeiten';
    }



    /**
     * name:        index
     *
     * shows a blank form
     *
     * @param       string
     *
     * @author      parobri.ch
     * @date        20120909
     */
    public function index() {

        //-------------------------------------------------//
        // Auswahlmenu "neues Buch" / "neue Auflage"
        //-------------------------------------------------//

        // Initialisieren
        $data['buchauswahl'] = '';

        // wenn was im Post ist
        if($this->input->post('buchauswahl', true) && $this->Model->validate_form(0) != false){
            $buchauswahl = $this->input->post('buchauswahl', true);
            if($buchauswahl == 'na'){
                redirect(base_url() . 'redaktion/buch/show');
            }
            if($buchauswahl == 'nb'){
                redirect(base_url() . 'redaktion/buch/editb');
            }

        }
        parent::__renderAll($this->sControllerName . 0, $data);
    }




    /**
     * name:        editb
     *
     * edit or new 'buch' entries
     *
     * @param       string  buch_id ID of one book
     *
     * @author      parobri.ch
     * @date        20120909
     */
    public function editb($buch_id = -1, $current_page = '', $location = '') {

        //--------------------------------------------------------------------------------//
        // Initialisieren
        //--------------------------------------------------------------------------------//

        $data['buchtitel']      = '';
        $data['untertitel']     = '';
        $data['beschreibung']   = '';
        $data['leseprobe']      = '';
        $data['status']         = '';
        $data['buch_id']        = $buch_id;
        $data['current_page']   = $current_page;
        $data['location']       = $location;
        $data['message']        = '';
        $data['leseprobe_titel']= 'Neue Leseprobe';

        $data['heading']        = 'Neues Buch eintragen';
        $data['submit_txt']     = 'speichern und weiter';
        $data['label']          = '';
        $data['back_txt']       = 'zur Übersicht';
        $data['back_link']      = base_url() . $this->sUriUebersicht . 'index/' . $current_page;
        if($location == 'show'){
            $data['back_link']  = base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page;
            $data['back_txt']   = 'zurück';
        }
        $data['autor']          = array();
        $data['autor_arr']      = array();
        $data['edition_id']     = '';
        $data['edition']        = array();
        $data['genre_id']       = '';
        $data['genre']          = array();
        $data['themenkreis']    = array();
        $data['themenkreis_arr']= array();
        // Bildmasse und Typ werden definiert
        $data['pdfmasse'] = $aPdfUploadConfig = array(  'upload_path'   => './media/redaktion/leseprobe/',
                                                        'allowed_types' => 'pdf',
                                                        'max_size'      => '2500',
                                                        'file_name'     => time() . '.pdf'
        );


        //--------------------------------------------------------------------------------//
        // Datenbankabfrage: Existierendes Buch editieren
        //--------------------------------------------------------------------------------//

        // Wenn Session mit Buchdaten gefüllt ist, Session anzeigen
        if($this->session->userdata('buchdata')){
            foreach($this->session->userdata('buchdata') AS $k=>$v){
                $data[$k]= $v;
            }

            // Session löschen
            $this->session->unset_userdata('buchdata');
            $session_buchdata = 'session_buchdata';
        }
        elseif($buch_id != -1){
            // Bucheintrag aus der Datenbank herauslesen und anzeigen
            $data2          = $this->Model->getRow('buch', 'buchtitel, untertitel, beschreibung, edition_id, genre_id, leseprobe, status', array('buch_id' => $buch_id));
            $data           = array_merge($data, $data2);
        }

        // Wenn der Datensatz in der Tabelle existiert und nichts in der Session ist
        if($buch_id != -1 && !isset($session_buchdata)){
            //Autoreinträge aus der Datenbank herauslesen und anzeigen
            $autor_id_arr   = $this->Model->getRows('buch_autor', 'autor_id', array('buch_id' => $buch_id));
            foreach($autor_id_arr AS $v){
                $data['autor'][] = $v['autor_id'];
            }

            // Themenkreise aus der Datenbank herauslesen und anzeigen
            $themenkreis_id_arr   = $this->Model->getRows('buch_themenkreis', 'themenkreis_id', array('buch_id' => $buch_id));
            foreach($themenkreis_id_arr AS $v){
                $data['themenkreis'][] = $v['themenkreis_id'];
            }

            // Header anpassen
            $data['heading']  = '<span class="add-on"><i class="icon-edit"></i></span> Buch editieren';
        }

        //--------------------------------------------------------------------------------//
        // weitere Datenbankabfragen (Autoren, Themenkreise)
        //--------------------------------------------------------------------------------//

        // Existierende Autoren des gewählten Buches auflisten
        $autorarr = $this->Model->getRows('autor', 'autor_id, vorname, nachname', '', '', 'nachname ASC');
        if($autorarr != array()){
            $data['autor_arr'][0]  = '-- hinzufügen --';
            foreach($autorarr AS $k=>$v){
                $data['autor_arr'][$v['autor_id']] = $v['nachname'] . ' ' . $v['vorname'];
            }
        }

        // Existierende Themenkreise aus der Datenbank auflisten
        $themenkreis_arr   = $this->Model->getRows('themenkreis', 'themenkreis, themenkreis_id', '', '', 'themenkreis ASC');
        if($themenkreis_arr != array()){
            $data['themenkreis_arr'][0]  = '-- hinzufügen --';
            foreach($themenkreis_arr AS $k=>$v){
                $data['themenkreis_arr'][$v['themenkreis_id']] = $v['themenkreis'];
            }
        }


        // Existierende Editionen auflisten
        $editionarr = $this->Model->getRows('edition', 'edition_id, edition', '', '', 'edition ASC');
        if($editionarr != array()){
            $data['edition'][0] = '-- bitte wählen --';
            foreach($editionarr AS $v){
                $data['edition'][$v['edition_id']] = $v['edition'];
            }
        }

        // Existierende Genres auflisten
        $genrearr = $this->Model->getRows('genre', 'genre_id, genre', '', '', 'genre ASC');
        if($genrearr != array()){
            $data['genre'][0] = '-- bitte wählen --';
            foreach($genrearr AS $v){
                $data['genre'][$v['genre_id']] = $v['genre'];
            }
        }

        //--------------------------------------------------------------------------------//
        // Post-Einträge bearbeiten und in Datenbank speichern
        //--------------------------------------------------------------------------------//

        // Wenn eine Buchtitel im Post ist
        if($this->input->post('weiter', true)){

            if ($this->Model->validate_form(1, $buch_id) == false) {

                //Wenn Validierung nicht okay, wird nochmals Methode index() aufgerufen und Formular wird nochmals angezeigt
                $data['autor']       = $this->input->post('autor', TRUE);
                $data['themenkreis'] = $this->input->post('themenkreis', TRUE);
                $data['edition_id']     = $this->input->post('edition_id', TRUE);
                $data['genre_id']       = $this->input->post('genre_id', TRUE);
                parent::__renderAll($this->sControllerName . 1, $data);
            }

            //Falls Validierung korrekt, wird der Eintrag in die Datenbank geschrieben und die BuchID wird angezeigt
            else {

                // Daten werden in die 'buch'-Tabelle geschrieben
                $aDataBuch = array('buchtitel'      => $this->input->post('buchtitel', true),
                                   'untertitel'     => $this->input->post('untertitel', true),
                                   'beschreibung'   => $this->input->post('beschreibung', true),
                                   'edition_id'     => $this->input->post('edition_id', true),
                                   'genre_id'       => $this->input->post('genre_id', true),
                                   'status'         => $this->input->post('status', true));


                // Wenn ein neues Pdf im Upload ist
                if (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != '') {

                    // Upload durchführen: wenn ok ist Dateiname im $return_upload sonst die Fehlermeldung
                    if($buch_id != -1){
                        //$return_upload = $this->Model->doFileUpload($buch_id, 'buch_id', 'buch', 'leseprobe', './media/redaktion/leseprobe/');
                        $return_upload = $this->Model->doFileUpload($buch_id, 'buch_id', 'buch', 'leseprobe', $aPdfUploadConfig);
                    }
                    else{
                        //$return_upload = $this->Model->doFileUpload('', 'buch_id', 'buch', 'leseprobe', './media/redaktion/leseprobe/');
                        $return_upload = $this->Model->doFileUpload('', 'buch_id', 'buch', 'leseprobe', $aPdfUploadConfig);
                    }
                    // Wenn return_upload ein Fehler zurückgibt
                    if (strstr($return_upload, 'Fehler')) {
                        $data['message']    = strip_tags($this->upload->display_errors());
                        $data['uploaderror']= 'Fehler';
                        $data['edition_id'] = $this->input->post('edition_id', true);
                        $data['genre_id']   = $this->input->post('genre_id', true);
                        $data['autor']      = $this->input->post('autor', true);
                        $data['themenkreis']= $this->input->post('themenkreis', true);
                        parent::__renderAll($this->sControllerName . 1, $data);
                    }
                    // Wenn return_upload den Dateinamen zurückgibt
                    else {
                        $aDataBuch['leseprobe'] = $return_upload;
                    }
                }
                // Wenn kein Fehler angezeigt wird, dann Einträge in der Tabelle speichern
                if ($data['message'] == '') {
                    if($buch_id != -1){

                        $buch_id = $this->Model->saveRecord('buch', $aDataBuch, $buch_id, 'buch_id');
                    }
                    else{

                        $buch_id = $this->Model->saveRecord('buch', $aDataBuch);

                    }


                    // Daten werden in die 'buch_autor'-Tabelle geschrieben
                    $this->Model->deleteRecord('buch_autor',$buch_id, 'buch_id');
                    $aDataBuch_Autor = array();
                    foreach($this->input->post('autor', true) AS $v){
                        $aDataBuch_Autor['buch_id']  = $buch_id;
                        $aDataBuch_Autor['autor_id'] = $v;
                        $this->Model->saveRecord('buch_autor', $aDataBuch_Autor);
                    }

                    // Daten werden in die 'buch_themenkreis'-Tabelle geschrieben
                    $this->Model->deleteRecord('buch_themenkreis',$buch_id, 'buch_id');
                    $aDataBuch_Themenkreis = array();
                    foreach($this->input->post('themenkreis', true) AS $v){
                        $aDataBuch_Themenkreis['buch_id']         = $buch_id;
                        $aDataBuch_Themenkreis['themenkreis_id']  = $v;
                        $this->Model->saveRecord('buch_themenkreis', $aDataBuch_Themenkreis);
                    }
                    if($location == 'show'){
                        redirect(base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page . '/' . $location);
                    }
                    else {
                        redirect(base_url() . 'redaktion/buch/editp/' . $buch_id . '/' . $current_page . '/' . $location);
                    }
                }

            }
        }
        // wenn neues Genre eingetragen werden soll
        elseif($this->input->post('genreneu', true)){
            $buchdata = array('buchdata' => $this->input->post());
            $this->session->set_userdata($buchdata);
            redirect(base_url() . 'redaktion/genre/index');
        }

        // wenn neue Edition eingetragen werden soll
        elseif($this->input->post('editionneu', true)){
            $buchdata = array('buchdata' => $this->input->post());
            $this->session->set_userdata($buchdata);
            redirect(base_url() . 'redaktion/edition/index');
        }

        // wenn neuer Themenkreis eingetragen werden soll
        elseif($this->input->post('themenkreisneu', true)){
            $buchdata = array('buchdata' => $this->input->post());
            $this->session->set_userdata($buchdata);
            redirect(base_url() . 'redaktion/themenkreis/index');
        }

        // wenn neuer Autor eingetragen werden soll
        elseif($this->input->post('autorneu', true)){
            $buchdata = array('buchdata' => $this->input->post());
            $this->session->set_userdata($buchdata);
            redirect(base_url() . 'redaktion/autor/index');
        }

        // wenn ein Autor aus dem Dropdown hinzugefügt werden soll
        elseif($this->input->post('autorhinzu', true)){
            $buchdata = array('buchdata' => $this->input->post());
            $buchdata['buchdata']['autor'][] = $this->input->post('autorhinzu', true);
            $this->session->set_userdata($buchdata);
            redirect($this->uri->uri_string());
        }

        // wenn ein Autor entfernt werden soll
        elseif($this->input->post('autorweg', true)){
            $buchdata = array('buchdata' => $this->input->post());
            $autorarr = $buchdata['buchdata']['autor'];
            unset($buchdata['buchdata']['autor']);
            foreach($autorarr AS $v){
                if($v != $this->input->post('autorweg', true)){
                    $buchdata['buchdata']['autor'][] = $v;
                }
            }
            $this->session->set_userdata($buchdata);
            redirect($this->uri->uri_string());
        }

        // wenn ein Themenkreis aus dem Dropdown hinzugefügt werden soll
        elseif($this->input->post('themenkreishinzu', true)){
            $buchdata = array('buchdata' => $this->input->post());
            $buchdata['buchdata']['themenkreis'][] = $this->input->post('themenkreishinzu', true);
            $this->session->set_userdata($buchdata);
            redirect($this->uri->uri_string());
        }

        // wenn ein Themenkreis entfernt werden soll
        elseif($this->input->post('themenkreisweg', true)){
            $buchdata = array('buchdata' => $this->input->post());
            $themenkreisarr = $buchdata['buchdata']['themenkreis'];
            unset($buchdata['buchdata']['themenkreis']);
            foreach($themenkreisarr AS $v){
                if($v != $this->input->post('themenkreisweg', true)){
                    $buchdata['buchdata']['themenkreis'][] = $v;
                }
            }
            $this->session->set_userdata($buchdata);
            redirect($this->uri->uri_string());
        }
        // wenn die Leseprobe entfernt werden soll
        elseif($this->input->post('leseprobeweg', true)){
            $data['heading']     = 'Leseprobe löschen:';
            $data['auflage_id']  ='';
            $data['description'] = 'Wollen Sie folgende Leseprobe löschen?';
            $data['buchtitel']   = '<a href="' . base_url() . 'media/redaktion/leseprobe/' . $this->input->post('leseprobeweg', true) . '" target="_blank">' . $this->input->post('leseprobeweg', true) . '</a>';
            $data['back_txt']    = 'zurück';
            $data['back_link']   = base_url() . 'redaktion/buch/editb/' . $buch_id . '/' . $current_page;
            $data['submit_txt']  = 'löschen';
            $data['submit_link'] =  base_url() . 'redaktion/buch/deletefile/' . $buch_id . '/' . $current_page;
            parent::__renderAll('buch_delete', $data);
        }
        //--------------------------------------------------------------------------------//
        // Formular mit Daten ausgeben
        //--------------------------------------------------------------------------------//
        else {
            // wenn nichts im Post ist
            parent::__renderAll($this->sControllerName . 1, $data);
        }
    }





    /**
     * name:        editp
     *
     * editing form for the 'pressetext' entries
     *
     * @param       integer  $buch_id       buch_id
     * @param       string   $current_page  current pag of the overview
     * @param       string   $location      where coming from
     * @param       string   $delete        delete all entries
     *
     *
     * @author      parobri.ch
     * @date        20120909
     */
    public function editp($buch_id ='', $current_page = '', $location = '', $delete = 'no') {


        if($buch_id != '' && $delete=='no'){
            //--------------------------------------------------------------------------------//
            // Initialisieren
            //--------------------------------------------------------------------------------//

            $data['pressetext']     = array();
            $data['pressetext_id']  = array();
            $data['location']       = $location;
            $data['current_page']   = $current_page;
            $data['buch_id']        = $buch_id;
            $buchtitel              = $this->Model->getOne('buch', 'buchtitel', 'buch_id=' . $buch_id);
            $data['heading']        = '<span class="add-on"><i class="icon-edit"></i></span> ' . $buchtitel . ': Pressetext(e) eintragen';
            $data['submit_txt']     = 'speichern und weiter';
            $data['label']          = '';
            $data['back_txt']       = 'zurück';
            $data['textfeld']       = '';
            $data['back_link']      = base_url() . 'redaktion/buch/editb/' . $buch_id . '/' . $current_page . '/' . $location;
            if($location =='show'){
                $data['back_link']      = base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page . '/' . $location;
            }

            //--------------------------------------------------------------------------------//
            // Datenbankabfrage
            //--------------------------------------------------------------------------------//

            if(!$this->input->post()){
                // existierende PRESSETEXTE aus der Datenbank holen
                $pressetext_arr   = $this->Model->getRows('pressetext', 'pressetext, pressetext_id, status', array('buch_id' => $buch_id));

                foreach($pressetext_arr AS $v){
                    $data['pressetext'][]    = $v['pressetext'];
                    $data['pressetext_id'][] = $v['pressetext_id'];
                    $data['status'][]        = $v['status'];
                }

                // leeres Feld anzeigen lassen, wenn kein Eintrag vorhanden
                if(count($pressetext_arr)<1){
                    $data['pressetext'][]    = '';
                    $data['pressetext_id'][] = '-1';
                    $data['status'][]        = '';
                }
            }

            //--------------------------------------------------------------------------------//
            // Textfield Handling
            //--------------------------------------------------------------------------------//

            // Anzahl Textfelder
            $anz = (count($_POST)-4)/3;

            // zusätzliche leere Felder anzeigen
            if($this->input->post('textfeldneu', true) && $this->Model->validate_form(2,'',$anz) == true){

                for($i=0; $i<$anz;$i++){
                    $data['pressetext'][$i]     = $this->input->post('pressetext' . $i, true);
                    $data['pressetext_id'][$i]  = $this->input->post('pressetext_id' . $i, true);
                    $data['status'][$i]         = $this->input->post('status' . $i, true);
                }
                $data['pressetext'][$i]    = '';
                $data['pressetext_id'][$i] = -1-$i;
                $data['status'][$i]        = '';
            }
            else {

                // Anzahl Textfelder
                $anz = (count($_POST)-3)/3;
                $delete = -1;

                for($i=0; $i<$anz;$i++){
                    //Löschfunktion für spezifische Pressetextfelder
                    if($this->input->post('status' . $i, true) == 'e'){
                        $delete = $i;
                    }
                }
                for($i=0; $i<$anz;$i++){
                    if($i != $delete){
                        $data['pressetext'][]     = $this->input->post('pressetext' . $i, true);
                        $data['pressetext_id'][]  = $this->input->post('pressetext_id' . $i, true);
                        $data['status'][]         = $this->input->post('status' . $i, true);
                    }
                }

            }


            //--------------------------------------------------------------------------------//
            // Post-Einträge bearbeiten und in Datenbank speichern
            //--------------------------------------------------------------------------------//

            // Wenn Einträge im Post sind
            if($this->input->post('weiter', true)){

                // zählt die eingetragenen Pressetexte
                $anz_pt = (count($_POST)-4)/ 3;
                if($this->Model->validate_form(2,'',$anz_pt) != false){

                    $this->Model->deleteRecord('pressetext', $buch_id, 'buch_id');
                    for($i=0;$i<$anz_pt;$i++){
                        // wenn Felder nicht leer sind diese in die Datenbank schreiben
                        if($this->input->post('pressetext' . $i, true) !=''){
                            $this->Model->saveRecord('pressetext',
                                                     array('pressetext' => $this->input->post('pressetext' . $i, true),
                                                     'status'           => $this->input->post('status' . $i, true),
                                                     'buch_id'          => $buch_id));
                        }
                    }
                    //...wenn alles ok, dann weiterleiten
                    if($location == 'show'){
                        redirect(base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page . '/' . $location);
                    }
                    else{
                        redirect(base_url() . 'redaktion/buch/edita/' . $buch_id . '/' . $current_page);
                    }

                }
                else{
                    // wenn ein Fehler passiert, die Einträge wieder anzeigen
                    for($i=0;$i<$anz_pt;$i++){
                        $data['pressetext'][$i] = $this->input->post('pressetext' . $i, true);
                    }
                }
            }
            //--------------------------------------------------------------------------------//
            // Formular mit Daten ausgeben
            //--------------------------------------------------------------------------------//

            else {
                // wenn nichts im Post ist
                parent::__renderAll($this->sControllerName . 2, $data);
            }
        }
        // alle Einträge für dieses Buch löschen
        elseif($delete == 'delete'){
            $data['buchtitel']      = $this->Model->getOne('buch','buchtitel','buch_id=' . $buch_id);
            $data['heading']        = 'Pressetexte löschen';
            $data['buch_id']        = $buch_id;
            $data['auflage_id']     = '';
            $data['current_page']   = $current_page;
            $data['description']    = 'Alle Pressetexte von folgendem Buch löschen?';
            $data['back_link']      = base_url() . '/redaktion/buch/show/' . $buch_id . '/' . $current_page;
            $data['submit_link']    = base_url() . '/redaktion/buch/editp/' . $buch_id . '/' . $current_page . '/' . $location . '/execute';
            $data['back_txt']       = 'abbrechen';
            $data['submit_txt']     = 'löschen';

            parent::__renderAll('buch_delete', $data);
        }
        elseif($delete == 'execute'){
            $this->Model->deleteRecord('pressetext', $buch_id, 'buch_id');
            redirect(base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page . '/' . $location);
        }

        else{
            echo 'Fehler: Diese Seite kann nicht direkt aufgerufen werden<br />
           <a href="' . base_url() . 'redaktion/buecheruebersicht">zur&uuml;ck</a>' . PHP_EOL;
        }
    }




    /**
     * name:        edita
     *
     * edit or new 'auflage' entries
     *
     * @param       string  buch_id         ID of the book
     * @param       string  current_page    current_page
     * @param       string  auflage_id      ID of the auflage
     *
     * @author      parobri.ch
     * @date        20120909
     */
    public function edita($buch_id = '', $current_page = '', $auflage_id = -1, $location = '') {

        if($buch_id != ''){
            $data['auflage_id']         = $auflage_id;
            $data['auflage_arr']        = array();
            $data['auflage']            = '';
            $data['buch_id']            = $buch_id;
            $data['current_page']       = $current_page;
            $data['menge']              = -1;
            $data['vergriffen']         = '';
            $data['erscheinungsdatum']  = '';
            $data['preis_sfr']          = '';
            $data['preis_euro']         = '';
            $data['isbn']               = '';
            $data['bild']               = '';
            $data['medium_id']          = '';
            $data['format_id']          = '';
            $data['themenkreis']        = '';
            $data['buchtipp']           = '';
            $data['pressetext']         = '';
            $data['status']             = '';
            $data['location']           = $location;
            $data['buchbild']           = 'Bild hochladen';

            // Bildmasse und Typ werden definiert
            $data['bildmasse'] = $aBildUploadConfig = array('upload_path'   => './media/redaktion/images/auflage/',
                                                    'allowed_types' => 'gif|jpg|png|jpeg',
                                                    'max_size'      => '2500',
                                                    'max_width'     => '1024',
                                                    'max_height'    => '1024',
                                                    'file_name'     => time() . '.jpg'
                                                    );

        }
        // Wenn Session mit Buchdaten gefüllt ist, Session anzeigen
        if($this->session->userdata('auflagedata')){
            foreach($this->session->userdata('auflagedata') AS $k=>$v){
                $data[$k]= $v;
            }
            $location = $data['location'];
            $auflage_id = $data['auflage_id'];

            // Session löschen
            $this->session->unset_userdata('auflagedata');
            $session_buchdata = 'session_buchdata';
        }

        if($buch_id != '' || isset($session_buchdata)){

            //-------------------------------------------------------------------------------------//
            // Initialisieren
            //-------------------------------------------------------------------------------------//

            $buchtitel                  = $this->Model->getOne('buch', 'buchtitel', 'buch_id=' . $buch_id);
            $data['heading']            = '<span class="add-on"><i class="icon-edit"></i></span> ' . $buchtitel . ': Auflage(n) editieren';
            $data['message']            = '';
            $data['submit_txt']         = 'speichern und weiter';
            $data['label']              = '';
            $data['back_txt']           = 'zurück';
            $data['back_link']          = base_url() . 'redaktion/buch/editp/' . $buch_id . '/' . $current_page;
            if($location == 'show'){
                $data['back_link']      = base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page;
            }

            if($auflage_id == -2){
                $data['back_link']      = base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page;
                $data['auflage_id']     = -1;
            }


            //--------------------------------------------------------------------------------//
            // Datenbankabfrage, wenn eine Auflage gewählt ist
            //--------------------------------------------------------------------------------//

            // existierende AUFLAGEN aus der Datenbank holen
            $auflage_arr   = $this->Model->getRows('auflage', 'auflage, auflage_id', array('buch_id' => $buch_id));
            if($auflage_arr != array()){
                foreach($auflage_arr AS $v){
                    $data['auflage_arr'][$v['auflage_id']]    = $v['auflage'];
                }
            }

            // wenn eine AUFLAGE mit dropdown gewählt wird
            if($this->input->post('auflage_id', true) && $this->Model->validate_form(3.1) != false && !isset($session_buchdata)){
                $data['auflage_id'] =  $this->input->post('auflage_id', true);
            }


            // wenn eine AUFLAGE gewählt ist
            if($data['auflage_id'] !=-1){
                if(!isset($session_buchdata)){
                    $data2  = $this->Model->getRow('auflage', 'auflage, menge, vergriffen, erscheinungsdatum, preis_sfr, preis_euro, isbn, format_id, medium_id, status, bild', array('auflage_id' => $data['auflage_id']));
                    $data   = array_merge($data, $data2);
                }


                // Formatsbezeichnung holen
                $data4 = $this->Model->getRow('format', 'format',array('format_id' => $data['format_id']));
                $data   = array_merge($data, $data4);

                // Mediumsbezeichnung holen
                $data3 = $this->Model->getRow('medium', 'medium',array('medium_id' => $data['medium_id']));
                $data   = array_merge($data, $data3);

            }
            else{
                // Definiert welche AUFLAGE die neue Auflage ist
                $data['auflage'] = count($data['auflage_arr'])+1;
            }
            //--------------------------------------------------------------------------------//
            // Restliche Datenbankabfragen
            //--------------------------------------------------------------------------------//

            // Medien aus der Datenbank holen
            $medium_arr               = $this->Model->getRows('medium', 'medium, medium_id');
            $data['medium_arr'][0]    = '-- bitte wählen --';
            if($medium_arr != array()){
                foreach($medium_arr AS $v){
                    $data['medium_arr'][$v['medium_id']]    = $v['medium'];
                }
            }


            // wenn eine MEDIUM, FORMAT oder STATUS mit dropdown gewählt wird
            if($this->input->post('medium_id', true) &&
                $this->Model->validate_form(3.2) != false &&
                !$this->input->post('weiter', true) &&
                !$this->input->post('medium', true)){


                $data['medium_id']          = $this->input->post('medium_id', true);
                $data['auflage']            = $this->input->post('auflage', true);
                $data['auflage_id']         = $this->input->post('auflage_id', true);
                $data['menge']              = $this->input->post('menge', true);
                $data['vergriffen']         = $this->input->post('vergriffen', true);
                $data['erscheinungsdatum']  = '';
                if($this->input->post('erscheinungsdatum', true) !=''){
                    $array                      = explode('.', $this->input->post('erscheinungsdatum', true));
                    $erscheinungsdatum = $array[2] . '-' . $array[1] . '-' . $array[0];
                    $data['erscheinungsdatum']  = $erscheinungsdatum;
                }
                $data['preis_sfr']          = $this->input->post('preis_sfr', true);
                $data['preis_euro']         = $this->input->post('preis_euro', true);
                $data['bild']               = $this->input->post('bild', true);
                $isbn                       = str_replace('-', '', $this->input->post('isbn', true));
                $data['isbn']               = $isbn;
                $data['format_id']          = $this->input->post('format_id', true);
                $data['status']             = $this->input->post('status', true);



            }

            // Formate aus der Datenbank holen
            if($data['medium_id'] !=''){
                $format_arr         = $this->Model->getRows('format', 'format, format_id', array('medium_id' => $data['medium_id']));
                $data['format_arr'][0]    = '-- bitte wählen --';
                if($format_arr != array()){
                    foreach($format_arr AS $v){
                        $data['format_arr'][$v['format_id']]    = $v['format'];
                    }
                }
            }
            else{
                $data['format_arr'] = array();
            }

            //--------------------------------------------------------------------------------//
            // Posteinträge aufbereiten und in Datenbank speichern
            //--------------------------------------------------------------------------------//

            // wenn das Formular abgeschickt wird
            if( $this->input->post('weiter', true) && $this->Model->validate_form(3) != false){

                // Aufbereitung der Daten
                $array = explode('.', $this->input->post('erscheinungsdatum', true));
                $erscheinungsdatum = $array[2] . '-' . $array[1] . '-' . $array[0];

                $isbn = str_replace('-', '', $this->input->post('isbn', true));

                if($this->input->post('menge', true) != ''){
                    $menge = $this->input->post('menge', true);
                }
                else {
                    $menge = -1;
                }
                // Array zusammensetzen für den Tabelleneintrag
                $aData = array('auflage'            => $this->input->post('auflage', true),
                               'buch_id'            => $buch_id,
                               'menge'              => $menge,
                               'vergriffen'         => $this->input->post('vergriffen', true),
                               'erscheinungsdatum'  => $erscheinungsdatum,
                               'preis_sfr'          => $this->input->post('preis_sfr', true),
                               'preis_euro'         => $this->input->post('preis_euro', true),
                               'isbn'               => $isbn,
                               'medium_id'          => $this->input->post('medium_id', true),
                               'format_id'          => $this->input->post('format_id', true),
                               'status'             => $this->input->post('status', true)
                );

                // Wenn ein neues Bild im Upload ist
                if (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != '') {
                    // Upload durchführen: wenn ok ist Bildname im $return_upload sonst die Fehlermeldung
                    if($data['auflage_id'] != -1){
                        $return_upload = $this->Model->doImageUpload($data['auflage_id'], 'auflage_id', 'auflage', 'bild', $aBildUploadConfig);
                    }
                    else{
                        $return_upload = $this->Model->doImageUpload('', 'auflage_id', 'auflage', 'bild', $aBildUploadConfig);
                    }
                    // Wenn return_upload ein Fehler zurückgibt
                    if (strstr($return_upload, 'Fehler')) {
                        $data['message']    = strip_tags($this->upload->display_errors());
                        $data['uploaderror']='Fehler';

                    } // Wenn return_upload den Bildnamen zurückgibt
                    else {
                        $aData['bild'] = $return_upload;
                    }
                }
                // Wenn kein Fehler angezeigt wird, dann Einträge in der Tabelle speichern
                if ($data['message'] == '') {
                    if($data['auflage_id'] != -1){
                        $this->Model->saveRecord('auflage', $aData, $data['auflage_id'], 'auflage_id');
                    }
                    else{
                        $this->Model->saveRecord('auflage', $aData);
                    }
                    redirect(base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page);
                }
            }

            //--------------------------------------------------------------------------------//
            // wenn ein neues Medium hinzugefügt wird
            //--------------------------------------------------------------------------------//

            elseif($this->input->post('mediumneu', true)){

                // Aufbereitung der Daten
                $erscheinungsdatum = '';
                if($this->input->post('erscheinungsdatum', true) != ''){
                    $array = explode('.', $this->input->post('erscheinungsdatum', true));
                    $erscheinungsdatum = $array[2] . '-' . $array[1] . '-' . $array[0];
                }


                $isbn = str_replace('-', '', $this->input->post('isbn', true));

                // Array zusammensetzen für den Tabelleneintrag
                $auflagedata = $this->input->post();
                $auflagedata['erscheinungsdatum']   = $erscheinungsdatum;
                $auflagedata['isbn']                = $isbn;

                $this->session->set_userdata('auflagedata', $auflagedata);

                //print_r($this->session->all_userdata());
                redirect(base_url() . 'redaktion/medium');
            }
            //--------------------------------------------------------------------------------//
            // Formular mit Daten ausgeben
            //--------------------------------------------------------------------------------//

            parent::__renderAll($this->sControllerName . 3, $data);
        }
        else{
            echo 'Fehler: Diese Seite kann nicht direkt aufgerufen werden<br />
           <a href="' . base_url() . 'redaktion/buecheruebersicht">zur&uuml;ck</a>' . PHP_EOL;
        }
    }




     /**
     * name:        show
     *
     * shows the 'buch' entry
     *
     * @param       string  buch_id ID of one book
     *
     * @author      parobri.ch
     * @date        20120909
     */
    public function show($buch_id = '', $current_page = 0, $location = '') {

        //--------------------------------------------------------------------------------------//
        // Initialisieren
        //--------------------------------------------------------------------------------------//

        $data['current_page']   = $current_page;
        $data['buchtitel']      = '';
        $data['untertitel']     = '';
        $data['beschreibung']   = '';
        $data['leseprobe']      = '';
        if($buch_id ==''){
            $data['buch_id']        = $this->Model->getOne('buch', 'buch_id');
        }
        else {
            $data['buch_id']        = $buch_id;
        }
        $data['heading']        = '<span class="add-on"><i class="icon-eye-open" alt="anzeigen" title="anzeigen"></i></span> Buchinfos';
        $data['back_txt']       = 'zur Übersicht';
        $data['back_link']      = base_url() . $this->sUriUebersicht . 'index/' . $current_page;
        $data['autor']          = array();
        $data['edition']        = '';
        $data['edition_id']     = '';
        $data['genre']          = '';
        $data['genre_id']       = '';
        $data['format']         = '';
        $data['format_id']      = '';

        //--------------------------------------------------------------------------------//
        // Datenbankabfragen (Buch-Switcher)
        //--------------------------------------------------------------------------------//

        // existierende Buchnamen aus der Tabelle holen und assoziatives Array erstellen alphabetisch geordnet nach Buchtitel
        $buchtitel_arr = $this->Model->getAll('buch', '', 'buch_id, buchtitel','', '', '', 'buchtitel');
        foreach($buchtitel_arr AS $v){
            $data['buchliste'][$v['id']] = $v['buchtitel'];
        }

        //--------------------------------------------------------------------------------//
        // Post-Einträge prüfen (Buch-Switcher)
        //--------------------------------------------------------------------------------//

        // Wenn eine BUCH_ID im Post ist
        if(($this->input->post('buch_id', true)) && $this->Model->validate_form(4) != false){

            $data['buch_id']     = $this->input->post('buch_id', true);

        }
        // Wenn eine BUCH_ID in der URL ist
        elseif($buch_id !=''){
            $data['buch_id']        = $buch_id;
            $data['fix']            = 'fix';
        }

        //--------------------------------------------------------------------------------//
        // Datenbankabfragen
        //--------------------------------------------------------------------------------//

        // Informationen aus der 'buch'-Tabelle holen
        $data2  = $this->Model->getRow('buch', 'buchtitel, untertitel, beschreibung, edition_id, genre_id, leseprobe, status', array('buch_id' => $data['buch_id']));
        $data   = array_merge($data, $data2);

        // Pressetexte aus der Tabelle holen
        $data['pressetextarr'] = $this->Model->getRows('pressetext', 'pressetext, pressetext_id, status',array('buch_id' => $data['buch_id']));

        // Existierende Auflagen des gewählten Buches auflisten
        $anz_auflage = 1 + $this->Model->countEntries('auflage', '', array('buch_id' => $data['buch_id']));
        if($anz_auflage > 1){
            $auflagearr = $this->Model->getRows('auflage', 'auflage_id, auflage, erscheinungsdatum, isbn, preis_sfr, preis_euro, vergriffen, bild, menge, format_id, medium_id, status',
                               array('buch_id' => $data['buch_id']));

            $i = 0;
            foreach($auflagearr AS $k=>$v){
                $data['auflage'][$i] = $v;

                // Existierendes Format der gewählten Auflage auflisten
                $format = $this->Model->getOne('format', 'format', 'format_id = ' . $v['format_id']);
                $data['auflage'][$i]['format'] = $format;



                // Existierendes Medium der gewählten Auflage auflisten
                $mediumarr = $this->Model->getRow('medium', 'medium', array('medium.medium_id' => $v['medium_id']));
                $data['auflage'][$i]['medium'] = $mediumarr['medium'];

                $i++;
            }
        }

        // Existierende Themenkreise des gewählten Buches auflisten
        $data['themenkreis']  = $this->Model->getRows('themenkreis', 'themenkreis', array('buch_themenkreis.buch_id' => $data['buch_id']),array('buch_themenkreis' => 'themenkreis.themenkreis_id = buch_themenkreis.themenkreis_id'));

        // Existierende Autoren des gewählten Buches auflisten
        $data['autor'] = $this->Model->getRows('autor', 'autor.vorname, autor.nachname', array('buch_autor.buch_id' => $data['buch_id']),array('buch_autor' => 'autor.autor_id = buch_autor.autor_id'));

        // Existierender Verlag des gewählten Buches auflisten
        $edition = $this->Model->getOne('edition', 'edition', 'edition_id =' . $data['edition_id']);
        $data['edition']= $edition;

        // Existierendes Genre des gewählten Buches auflisten
        $genre = $this->Model->getOne('genre', 'genre', 'genre_id =' . $data['genre_id']);
        $data['genre']= $genre;




        //--------------------------------------------------------------------------------//
        // Formular mit Daten ausgeben
        //--------------------------------------------------------------------------------//
        parent::__renderAll($this->sControllerName . '_show', $data);
    }







    /**
     * name:        delete
     *
     * deletes the current row-entries
     *
     * @param       integer  $id            User id
     * @param       integer  $current_page  of the overview pagination
     * @param       string   $auflage_id    Id of 'auflage
     * @param       string   $location      where is coming
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function delete($buch_id='', $current_page ='', $auflage_id = -1, $location = ''){

        //--------------------------------------//
        // zeigt Löschen Formular an
        //--------------------------------------//

        // Gesamtes Buch löschen
        if($buch_id!='' && $auflage_id == -1){
            $data['buchtitel']      = $this->Model->getOne('buch','buchtitel','buch_id=' . $buch_id);
            $data['heading']        = 'Buch löschen';
            $data['auflage_id']     = $auflage_id;
            $data['buch_id']        = $buch_id;
            $data['current_page']   = $current_page;
            $data['description']    = 'Folgender Bucheintrag inklusive alle dazugehörigen Auflagen löschen?';
            $data['back_link']      = base_url() . '/redaktion/buecheruebersicht/index/' . $current_page;
            if($location == 'show'){
                $data['back_link']      = base_url() . '/redaktion/buch/show/' . $buch_id . '/' . $current_page;
            }
            $data['back_txt']       = 'abbrechen';
            $data['submit_txt']     = 'löschen';

            parent::__renderAll('buch_delete', $data);
        }

        // Einzelne Auflage löschen
        elseif($buch_id!='' && $auflage_id != -1){
            $buchtitel              = $this->Model->getOne('buch','buchtitel','buch_id=' . $buch_id);
            $auflagenr              = $this->Model->getOne('auflage','auflage','auflage_id=' . $auflage_id);
            $data['buchtitel']      = 'Auflage ' . $auflagenr;
            $data['heading']        = 'Auflage löschen';
            $data['auflage_id']     = $auflage_id;
            $data['buch_id']        = $buch_id;
            $data['current_page']   = $current_page;
            $data['description']    = 'Folgende Auflage des Buches "' . $buchtitel . '" löschen?';
            $data['back_link']      = base_url() . '/redaktion/buch/show/' . $buch_id . '/' . $current_page;
            $data['back_txt']       = 'abbrechen';
            $data['submit_txt']     = 'löschen';

            parent::__renderAll('buch_delete', $data);
        }

        else{
            // nichts ausgewählt -> Fehlermeldung anzeigen
            $data['heading']    = 'Buch löschen:';
            $data['msg']        = 'kein Buch ausgewählt';
            $data['linktxt']    = 'zurück';
            $data['link']       = base_url() . $this->sUriUebersicht;
            parent::__renderAll('message', $data);
        }

        // -----------------------------------------------------------//
        // Löschvorgang gesamtes Buch
        // -----------------------------------------------------------//

        if($this->input->post('loeschen', true) && $this->Model->validate_form(5) && ($_POST['auflage_id'] ==-1)){
            $buch_id        = $this->input->post('buch_id', true);
            $current_page   = $this->input->post('current_page', true);

            // Leseprobe löschen falls vorhanden
            if($this->Model->getOne('buch', 'leseprobe', 'buch_id = ' . $buch_id) !=''){
                $this->Model->deleteFile($buch_id, 'buch_id', 'buch', 'leseprobe', './media/redaktion/leseprobe/');
            }

            // Bilder löschen
            $arr = $this->Model->getRows('auflage', 'auflage_id', array('buch_id' => $buch_id));
            if(count($arr)>0){
                foreach($arr AS $v) {
                    $this->Model->deleteImage($v['auflage_id'], 'auflage_id', $sTable = 'auflage');
                }
            }

            // Datenbankeinträge löschen
            $this->Model->deleteRecord('buch', $buch_id, 'buch_id');
            $this->Model->deleteRecord('auflage', $buch_id, 'buch_id');
            $this->Model->deleteRecord('buch_autor', $buch_id, 'buch_id');
            $this->Model->deleteRecord('buch_themenkreis', $buch_id, 'buch_id');
            $this->Model->deleteRecord('pressetext', $buch_id, 'buch_id');

            // Auf die Übersichtsseite umleiten
            redirect(base_url() . 'redaktion/buecheruebersicht/index/' . $current_page);
        }

        // -----------------------------------------------------------//
        // Löschvorgang einzelne Auflage
        // -----------------------------------------------------------//

        if($this->input->post('loeschen', true) && $this->Model->validate_form(5) && ($this->input->post('auflage_id', true) !=-1)){

            $buch_id        = $this->input->post('buch_id', true);
            $current_page   = $this->input->post('current_page', true);
            $auflage_id     = $this->input->post('auflage_id', true);

            // Bild zur Auflage löschen, falls vorhanden
            $bild = $this->Model->getOne('auflage', 'bild', 'auflage_id =' . $this->input->post('auflage_id', true));
            if ($bild != '') {
                $this->Model->deleteImage($this->input->post('auflage_id', true), 'auflage_id', 'auflage');
            }

            // Datenbankeinträge löschen
            $this->Model->deleteRecord('auflage', $auflage_id, 'auflage_id');

            // Auf die Übersichtsseite umleiten
            redirect(base_url() . 'redaktion/buch/show/' . $buch_id . '/' . $current_page);
        }

    }

    /**
     * name:        deleteImage
     *
     * delete image
     *
     * @param       integer  $buch_id            buch_id
     * @param       integer  $current_page       current page of the overview page
     * @param       integer  $auflage_id         auflage_id
     * @param       string   $location           coming from
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function deleteImage ($buch_id='',$current_page='', $auflage_id = '', $location='') {

        if ($auflage_id != '') {
            $this->Model->deleteImage($auflage_id, 'auflage_id', 'auflage');
            redirect(base_url() .  'redaktion/buch/edita/' . $buch_id . "/" . $current_page . "/" . $auflage_id . "/" . $location);
        }
    }


    /**
     * name:        deleteFile
     *
     * delete pdf
     *
     * @param       integer  $id            User id
     * @param       integer  $uri           of the overview pagination
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function deleteFile ($buch_id='',$current_page='', $location='') {

        if ($buch_id != '') {
            if($this->Model->deleteFile($buch_id, 'buch_id', 'buch', 'leseprobe', './media/redaktion/leseprobe/')==true){
                redirect(base_url() .  'redaktion/buch/editb/' . $buch_id . "/" . $current_page . "/" . $location);
            }
            else{
                // Fehlermeldung anzeigen
                $data['heading']    = 'Datei löschen:';
                $data['msg']        = 'Fehler: Datei konnte nicht gelöscht werden';
                $data['linktxt']    = 'zurück';
                $data['link']       = base_url() .  'redaktion/buch/editb/' . $buch_id . "/" . $current_page . "/" . $location;
                parent::__renderAll('message', $data);
            }
        }
    }


    /**
     * name:        valid_isbn
     *
     * validate isbn
     *
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function valid_isbn() {        //validiert die isbn-Nummer
        $isbn_long = $this->input->post('isbn', true);
        $isbn = str_replace('-','',$isbn_long);
        if (!is_numeric($isbn) || strlen($isbn)!= 13){
            $this->form_validation->set_message('valid_isbn', 'Bitte korrekte ISBN-Nummer eingeben');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}

/* End of file buch.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/buch.php */