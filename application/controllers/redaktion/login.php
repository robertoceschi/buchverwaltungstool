<?php  !defined('BASEPATH') and exit('No direct script access allowed');


    class Login extends CI_Controller {

        protected $sControllerName = '';

        public function __construct () {
            //Controller Name in Kleinbuchstaben
            $this->sControllerName = strtolower(__CLASS__);
            parent::__construct();
        }


        /**
         * name:        index
         *
         * shows empty login form
         *
         *
         * @author      parobri.ch
         * @date        20120710
         */
        public function index () {
            //checkt ob der Benutzer schon eingeloggt ist => falls ja gehts direkt zur Hauptseite
            if (($this->session->userdata('benutzername') != "")) {
                redirect("redaktion/home");
                exit();
            }
            $this->renderAllNoHeader($this->sControllerName);
        }


        // wenn nicht eingeloggt: Loginformular + Fehlermeldung anzeigen
        public function no_permission () {
            $data['message'] = 'Sie haben keine Erlaubnis diese Seite zu sehen! Bitte loggen Sie sich ein.';
            $this->renderAllNoHeader($this->sControllerName, $data);
        }


        // bei falschem Passwort: Loginformular + Fehlermeldung anzeigen
        private function wrong_password () {
            $data['message'] = 'Falsches Passwort oder Benutzername';
            $this->renderAllNoHeader($this->sControllerName, $data);
        }


        //Identifikation wird überprüft
        public function validate_credentials () {
            //Membership Model wird geladen
            $this->load->model('redaktion/login_model', 'Model');
            //Validierung der Daten
            $query = $this->Model->validate();

            //Falls TRUE zurückgeliefert wird von der Methode validate() wird ein array mit dem usernamen und dem Status
            //is logged erstellt
            if ($query) {
                $benutzername = $this->input->post('benutzername', true);
                //$this->load->model('redaktion/home_model', 'Model');
                //$id   = $this->Model->getOne('nutzer', 'nutzer_id', 'benutzername = \'' . $benutzername . '\'');


                //Id, Name, Vorname usw. aus der Datenbank holen
                $where = array ('benutzername' => $benutzername);
                $fields = 'nutzer_id, vorname, nachname, status, emailadresse, benutzername';
                $data = $this->Model->getRow('nutzer', $fields, $where);
                $data['is_logged_in'] = true;

                // $data wird an die Session geliefert und die Userdaten werden gesetzt
                $this->session->set_userdata($data);

                //wenn keine URL in der Session gespeichert ist weiterleiten auf die Startseite
                $redirect_url = base_url() . 'redaktion/home';


                // Wenn eine URL in der Session gespeichert ist zu dieser umleiten
                if ($this->session->userdata('redirect_url')) {
                    $redirect_url = $this->session->userdata('redirect_url');
                    $this->session->unset_userdata('redirect_url');
                }
                redirect($redirect_url);
                exit();

            } else {
                //wird nichts von der DB geliefert
                $this->wrong_password();
            }
        }


        /**
         * name:        logout
         *
         * logout user
         * shows empty login form
         *
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function logout () {
            $this->session->sess_destroy();
            $data['message'] = 'Sie haben sich erfolgreich ausgeloggt.';
            $footerdata      = array('logout'=> 'logout');
            $this->renderAllNoHeader($this->sControllerName, $data, $footerdata);
        }


        /**
         * name:        renderAllNoHeader
         *
         * renders and loads views
         *
         * @param       string  $template       controller name
         * @param       array   $data           some data
         * @param       array   $footerdata     some footer data
         *
         * @author      parobri.ch
         * @date        20120710
         */
        private function renderAllNoHeader ($template, $data = array(), $footerdata = array()) {
            $this->load->view('redaktion/' . $template . '_view', $data);
            $this->load->view('redaktion/template/footer_login', $footerdata);
        }


    }

/* End of file login.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/login.php */