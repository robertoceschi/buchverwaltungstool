<?php

    class Autoren extends MY_Controller {

        protected $sControllerName = 'autoren';
        protected $sTable = 'autor';


        function __construct () {
            //Controller Name in Kleinbuchstaben
            $this->sControllerName = strtolower(__CLASS__);
            parent::__construct($this->sControllerName);
            $this->load->model('autoren_model');
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

            $data['result'] = $this->autoren_model->getAll($sTable = $this->sTable,
                $sSpecialSql = '',
                $sFields = 'autor_id, nachname, vorname, ' .
                    'biografie, website, status, bild',
                $aJoinLeft = '',
                $aWhere = '',
                $sGroupBy = '',
                $sOrder = '',
                $iPerPage = 20,
                $iCurrentPage = 0
            );

            parent::__renderAll($this->sControllerName, $data);
        }
    }
