<?php !defined('BASEPATH') and exit('No direct script access allowed');


require_once APPPATH . 'controllers/redaktion/adminpage_controller.php';

class AutorenUebersicht extends Adminpage_controller {

    protected $sControllerName  = '';
    protected $sTable           = 'autor';
    protected $sUriEdit         = 'autor';

    public function __construct() {
        //Controller Name in Kleinbuchstaben
        $this->sControllerName = strtolower(__CLASS__);
        parent::__construct($this->sControllerName, true, true);

        // h1 in header.php
        $this->headerData['heading'] = 'Autoren bearbeiten';
    }


    /**
     * name:        index
     *
     * prepares the table with all entries
     *
     * @param       integer  $iCurrentPage  current paginated page
     *
     * @author      parobri.ch
     * @date        20120710
     */
    public function index($iCurrentPage = 0) {
        //--------------------------------------//
        // Suchformular
        //--------------------------------------//
        $searchfields = array('nachname',
                              'vorname',
                              'biografie'
                        );
        // Suchbegriffe werden in der "searchValid()"-Methode aus dem $_Post gelesen, validiert
        // und als String "$valid_searchstr" aufbereitet
        // wenn der "$valid_searchstr" nicht leer ist, wird er in die Session geschrieben
        $valid_searchstr            = $this->Model->searchValid();
        if($valid_searchstr != ''){
            $this->session->set_userdata(array('search_' . $this->sTable => $valid_searchstr));
        }
        $SessSearch                 =  $this->session->userdata('search_' . $this->sTable);
        $this->searchData['search'] = $SessSearch;
        $wherearr                   = $this->Model->whereArr($SessSearch, $searchfields);


        //--------------------------------------//
        // Order Sql definieren
        //--------------------------------------//

        $sortbyarr      = $this->Model->sortby();
        $aData['sort']  = $sortbyarr['sort'];
        $order          = $sortbyarr['order'];

        //--------------------------------------//
        // Statusänderung in der Tabelle
        //--------------------------------------//

        $this->Model->status($this->sTable);

        //--------------------------------------//
        // Pagination < 1 2 3 >
        //--------------------------------------//

        // Pagination Class wird geladen
        $this->load->library('pagination');

        // Pagination Url wird definiert
        $sPaginationUrl    = base_url() . 'redaktion/' . $this->sControllerName . '/index/';

        // Anzahl Einträge insgesamt
        $iTotalRows = $this->Model->countEntries($this->sTable, $wherearr);
        $iAllRows   = $this->Model->countEntries($this->sTable);

        // Anzahl Einträge pro Seite
        $iPerPage = 15;

        // Helper wird geladen und Daten der Funktion "pagination_config" übergeben
        $this->load->helper('pagination');
        $aConfig = pagination_config($sPaginationUrl, $iTotalRows, $iPerPage);

        // $aConfig-Daten werden pagination class übergeben
        $this->pagination->initialize($aConfig);

        // Links < 1 2 3 > werden definiert
        $aData['pagination'] = $this->pagination->create_links();

        $aData['gesamt']        = $iAllRows;
        $aData['current_page']  = $iCurrentPage;

        //--------------------------------------//
        // weitere Daten, die übergeben werden
        //--------------------------------------//

        $aData['uri_edit']      = $this->sUriEdit;

        // Tabellen Kopf Zeile (Status und Bearbeitung sind fix in der View)
        $aData['tablehead'] = array('Nachname', 'Vorname', 'Bild');

        //--------------------------------------//
        // alle Daten aus der Datenbank holen und auslesen
        //--------------------------------------//
        $aData['result'] = $this->Model->getAll($sTable = $this->sTable,
                                                $sSpecialSql   = '',
                                                $sFields       = 'autor_id, nachname, vorname, bild, ' .
                                                                 'status',
                                                $aJoinLeft     = '',
                                                $aWhere        = $wherearr,
                                                $sGroupBy      = '',
                                                $sOrder        = $order,
                                                $iPerPage,
                                                $iCurrentPage
        );


        //--------------------------------------//
        // Rendern
        //--------------------------------------//
        parent::__renderAllwithSearch($this->sControllerName, $aData);
    }

    /**
     * name:        showAll
     *
     * delete session entry and shows all results
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function showAll() {
        // Search wird gelöscht und alle Einträge werden angezeigt
        $this->session->unset_userdata('search_' . $this->sTable);
        $this->index();
    }
}

/* End of file autorenuebersicht.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/autorenuebersicht.php */