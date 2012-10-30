<?php

    class Home extends MY_Controller {

        protected $sControllerName = 'home';
        protected $sTable           = '';


        function __construct () {
            //Controller Name in Kleinbuchstaben
            $this->sControllerName = strtolower(__CLASS__);
            parent::__construct($this->sControllerName);
            //$this->load->model('site/Home_model');
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


            $data = '';

            //$this->load->view('home_view', $data);
            parent::__renderAll($this->sControllerName, $data);
        }
    }