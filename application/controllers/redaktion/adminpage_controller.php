<?php  !defined('BASEPATH') and exit('No direct script access allowed');


    class Adminpage_controller extends CI_Controller {

        protected $headerData = array();
        protected $titleData = array();
        protected $searchData = array();

        /**
         * name:        __construct
         *
         * @param       name    of the navigation-point
         * @param       special model to load for this page
         * @param       special javascript for this page
         *
         * @author      parobri.ch
         * @date        20120710
         */
        //kleine aenderung
        public function __construct ($sControllerName = 'home', $model = false, $js_file = false) {
            parent::__construct();

            //Profiling
            //$this->output->enable_profiler(TRUE);

            // Es wird überprüft, ob der Besucher eingeloggt ist
            if (!$this->is_logged_in()) {

                // gesuchte URL wird in der Session gespeichert
                $redirect_url = base_url() . uri_string();
                $this->session->set_userdata('redirect_url', $redirect_url);
                // wenn Besucher nicht eingeloggt ist wird er umgeleitet
                redirect('redaktion/login/no_permission');
                exit();
            }


            // Zusätzliche Helpers und Libraries werden geladen
            $this->load->helper(array('url', 'form'));

            // Headerdata wird definiert
            $this->headerData['css_files'] = array('screen, print'      => 'lib/redaktion/js/jquery_ui/css/blitzer/jquery-ui-1.8.22.custom.css',
                                                   'screen'             => 'css/redaktion/style.css',
                                                   'print'              => 'css/redaktion/css/print.css');
            $this->headerData['js_files']  = array('jquery_1.7.2'       => 'lib/js/jquery-1.7.1.min.js',
                                                   'jquery_ui_custom'   => 'lib/redaktion/js/jquery_ui/jquery-ui-1.8.22.custom.js',
                                                  
                                                   'jquery_hilfe'       => 'lib/redaktion/js/hilfe.js',
                                                   'jquery_tooltip'     => 'lib/redaktion/js/tooltip.js');


            $this->headerData['heading'] = 'Home';

            //Titledata und Searchdata werden definiert
            // Page-Titel gleich wie den Controllernamen (in title.php)
            $this->headerData['sPageTitle'] = str_replace('ue', 'ü', ucfirst($sControllerName));
            $this->titleData['sPageTitle']  = str_replace('ue', 'ü', ucfirst($sControllerName));
            $this->titleData['sUriNew']     = $this->sTable;
            $this->searchData['controller'] = $sControllerName;

            // Läd individuelles Model
            if ($model === true) {
                $this->load->model('redaktion/' . $sControllerName . '_model', 'Model');
            }

            // Läd individuelles JavaScript
            if ($js_file === true) {
                $this->headerData['js_files']['js_custom'] = 'lib/redaktion/js/' . $sControllerName . '.js';
            }

            // Läd jQuery PlugIn "timepicker" (Uhrzeit) nur bei der Seite Agenda
            if ($sControllerName == 'agenda') {
                $this->headerData['js_files']['js_timepicker'] = 'lib/redaktion/js/jquery_ui/jquery.ui.timepicker.js';
            }

            // Läd jQuery PlugIn "validate"
            if ($sControllerName == 'nutzer') {
                $this->headerData['js_files']['js_validate'] = 'lib/redaktion/js/jquery.validate.js';
            }

            // Sprachmodul für die Upload-Fehlermeldungen in Deutsch
            $this->lang->load('upload', 'german');
        }


        /**
         * name:        renderAll
         *
         * renders and loads views
         *
         * @param       string  $template       controller name
         * @param       array   $data           some data
         *
         * @author      parobri.ch
         * @date        20120710
         */
        public function __renderAll ($template, $data) {
            $this->load->view('redaktion/template/header', $this->headerData);
            $this->load->view('redaktion/' . $template . '_view', $data);
            $this->load->view('redaktion/template/footer');

        }


        /**
         * name:        renderAllwithSearch
         *
         * renders and loads views
         * adds the search form and title views
         *
         * @param       string  $template       controller name
         * @param       array   $data           some data
         *
         * @author      parobri.ch
         * @date        20120710
         */
        public function __renderAllwithSearch ($template, $data) {
            $this->load->view('redaktion/template/header', $this->headerData);
            $this->load->view('redaktion/template/title', $this->titleData);
            $this->load->view('redaktion/template/search', $this->searchData);
            $this->load->view('redaktion/' . $template . '_view', $data);
            $this->load->view('redaktion/template/footer');
        }


        /**
         * name:        is_logged_in
         *
         * checks if user is logged in
         *
         *
         * @author      parobri.ch
         * @date        20120710
         */
        protected function is_logged_in () {
            $is_logged_in = $this->session->userdata('is_logged_in');

            //User ist nicht eingeloggt => Keine Session wurde erstellt
            if (!isset($is_logged_in) || $is_logged_in != true) {
                return false;
            }
            return true;
        }



    }


/* End of file adminpage_controller.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/adminpage_controller.php */