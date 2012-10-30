<?php  !defined('BASEPATH') and exit('No direct script access allowed');


require_once APPPATH . 'controllers/redaktion/adminpage_controller.php';

class Home extends Adminpage_controller {

    protected $sControllerName  = '';
    protected $sTable           = '';

    function __construct () {
        //Controller Name in Kleinbuchstaben
        $this->sControllerName = strtolower(__CLASS__);
        parent::__construct($this->sControllerName, true, false);
    }


    /**
     * name:        index
     *
     * prepare home view
     *
     *
     * @author      parobri.ch
     * @date        20120710
     */
    public function index () {

        $data = $this->session->all_userdata();
        parent::__renderAll($this->sControllerName, $data);

    }

}



/* End of file home.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/home.php */



