<?php

    class Agenda extends MY_Controller {

        protected $sControllerName = 'agenda';
        protected $sTable           = 'agenda';


        function __construct () {
            //Controller Name in Kleinbuchstaben
            $this->sControllerName = strtolower(__CLASS__);
            parent::__construct($this->sControllerName);
            $this->load->model('agenda_model');
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

            $data['result'] = $this->agenda_model->getAll($sTable = $this->sTable,
                $sSpecialSql = '',
                $sFields = 'agenda_id, datum, zeit, ' .
                    'titel, beschreibung, ort',
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