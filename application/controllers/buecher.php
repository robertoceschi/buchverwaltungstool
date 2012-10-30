<?php

    class Buecher extends MY_Controller {

        protected $sControllerName = 'buecher';
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
         * prepare autoren view
         *
         *
         * @author      parobri.ch
         * @date        20120710
         */
        public function index () {


            //$data['table_names'] = $this->Home_model->getTableNames();


            parent::__renderAll($this->sControllerName);
        }
    }