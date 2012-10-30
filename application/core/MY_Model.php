<?php  !defined('BASEPATH') and exit('No direct script access allowed');

class MY_Model extends CI_Model {



/**
 *---------------------------------------------------------------------------
 * Methoden Uebersicht
 *---------------------------------------------------------------------------
 **/

    /**
     * name:        getAll
     *
     * get all entries from selected Table
     *
     * @param       string  $table          database table name
     * @param       string  $sfields        fieldnames comaseparated
     * @param       string  $aWhere         string for WHERE-CLause
     * @param       string  $sOrder         string for ORDER BY-part
     * @param       integer $iPerPage       number of results per page for pagination
     * @param       integer $iCurPage       current page for pagination
     *
     * @return      array
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120711
     *
     **/
    public function getAll($sTable,
                           $sSpecialSql   = '',
                           $sFields       = '',
                           $aJoinLeft     = '',
                           $aWhere        = '',
                           $sGroupBy      = '',
                           $sOrder        = '',
                           $iPerPage      = -1,
                           $iCurPage      = -1) {
        $items = array();

        // SELECT zusammensetzen
        if($sSpecialSql !=''){
            $this->db->select($sSpecialSql, FALSE);
        }
        else{
            $this->db->select($sFields, FALSE);
        }

        // JOIN zusammensetzen
        if($aJoinLeft !=''){
            foreach($aJoinLeft AS $table=>$join){
                $this->db->join($table, $join, 'left');
            //$this->db->join('buch_autor', 'buch.buch_id = buch_autor.buch_id', 'left');
            //$this->db->join('autor', 'autor.autor_id = buch_autor.autor_id', 'left');
            }
        }

        // wenn aWhere nicht leer ist
        if ($aWhere != '') {
            foreach($aWhere AS $sWhere){
                $this->db->or_like($sWhere, NULL, FALSE);
            }
        }
        // wenn sGroupBy nicht leer ist
        if ($sGroupBy != '') {
            $this->db->group_by($sGroupBy);
        }

        // wenn order nicht leer ist
        if($sOrder != ''){
            $this->db->order_by($sOrder);
        }

        // mit Pagination
        if($iPerPage !=-1 && $iCurPage !=-1){
            $query = $this->db->get($sTable, $iPerPage, $iCurPage);
        }
        // ohne Pagination
        else{
            $query = $this->db->get($sTable);
        }

        // Assoziatives, mehrdimensionales Array zusammenstellen
        $i = 0;
        $sFields = str_replace(' ','',$sFields);
        $sFields = str_replace($sTable . '.','',$sFields);
        //$sFields = str_replace($sJoinTable . '.','',$sFields);
        $aFields = explode(',', $sFields);
        foreach($query->result() as $row){

            foreach ($aFields AS $field){

                $items[$i][$field] = $row->$field;

                // Tabellenfeld "beispiel_id" in "id" umwandeln
                if(isset($items[$i][$sTable . '_id'])){
                    $items[$i]['id'] = $items[$i][$sTable . '_id'];
                    unset($items[$i][$sTable . '_id']);
                }
            }
            $i++;
        }
        return $items;
    }


    /**
     * name:        status
     *
     * update the status field on the overview page
     *
     * @param       string  $sTableName      table name
     * @param       string  $sIdName         id field name
     *
     * @return
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120717
     *
     **/
    public function status($sTableName = '', $sIdName = ''){

        if($this->input->post('status') && $this->input->post('id')) {
            $this->form_validation->set_rules('status', '', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id', '', 'trim|required|xss_clean');

            if ($this->form_validation->run() != FALSE) {

                $id     = $this->input->post('id');
                $data   = array('status' => $this->input->post('status'));
                // Tabelle updaten
                if($sIdName == ''){
                    $this->db->where($sTableName . '_id', $id);
                }
                else {
                    $this->db->where($sIdName, $id);
                }
                $this->db->update($sTableName, $data);
                return true;
            }
        }
        return false;
    }


    /**
     * name:        countEntries
     *
     * count searched Records
     *
     * @param       string  $sTable     database table name
     * @param       array   $aLike      multidimensional array
     * @param       array   $aWhere     multidimensional array
     * @return      integer
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120711
     */
    public function countEntries($sTable, $aLike = '', $aWhere = '') {
        if($aLike != ''){
            foreach($aLike AS $l){
                $this->db->or_like($l, NULL, FALSE);
            }
        }

        if($aWhere != ''){
                $this->db->or_where($aWhere);
        }

        $this->db->from($sTable);
        return $this->db->count_all_results();
    }


    /**
     * name:        sortBy
     *
     * preparings for sort the results
     *
     *
     * @return      array
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120719
     *
     **/

    public function sortBy(){

        $order = '';
        $sort  = 'ASC';
        if($this->input->get('order') && $this->input->get('sort')){
            $order = $this->input->get('order', true);

            if($this->input->get('sort', true)== 'ASC'){
                $order .= ' ASC';
                $sort   = 'DESC';
            }
            else {
                $order        .= ' DESC';
            }
        }
        return array('order' => $order,
                     'sort' =>$sort);
    }


/**
 *---------------------------------------------------------------------------
 * Methoden, um einzelne Einträge zu mutieren
 *---------------------------------------------------------------------------
 **/


    /**
     * name:        getRow
     *
     * get associative array width data from one Record
     *
     * @param       string  $sTable     database table name
     * @param       string  $sFields    fieldnames, comma separated
     * @param       array   $aWhere     data for WHERE-Clause, key =
     *                                  fieldname, value = search string
     * @return      array
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20111209
     *
     **/
    public function getRow($sTable,
                           $sFields,
                           $aWhere) {
        $this->db->select($sFields, false);
        $this->db->or_where($aWhere);
        return $this->db->get($sTable)->row_array();
    }


    /**
     * name:        getRows
     *
     * get associative array width data from one Record
     *
     * @param       string  $sTable     database table name
     * @param       string  $sFields    fieldnames, comma separated
     * @param       array   $aWhere     data for WHERE-Clause, key =
     *                                  fieldname, value = search string
     *@param       array    $aJoin Left Joins as an array
     *@param       string   $sOrder     Order Rows
     *
     * @return      array
     *
     * @author      parobri.ch
     * @date        20120906
     *
     **/
    public function getRows($sTable,
                            $sFields,
                            $aWhere = '',
                            $aJoinLeft     = '',
                            $sOrder = '') {
        $this->db->select($sFields, false);

        if($aJoinLeft !=''){
            foreach($aJoinLeft AS $table=>$join){
                $this->db->join($table, $join, 'left');
                //$this->db->join('buch_autor', 'buch.buch_id = buch_autor.buch_id', 'left');
                //$this->db->join('autor', 'autor.autor_id = buch_autor.autor_id', 'left');
            }
        }
        // wenn where nicht leer ist
        if($aWhere != ''){
            $this->db->where($aWhere);
        }

        // wenn order nicht leer ist
        if($sOrder != ''){
            $this->db->order_by($sOrder);
        }

        return $this->db->get($sTable)->result_array();
    }



    /**
     * name:        deleteRecord
     *
     * delete one record by ID
     *
     * @param       string  $sTable      database table name
     * @param       integer $iId         ID of Record to delete
     * @param       string  $sIdName     idName for WHERE Clause
     * @return      boolean
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120719
     */
    public function deleteRecord($sTable, $iId, $sIdName = 'id') {
        $this->db->where($sIdName, $iId);
        return $this->db->delete($sTable);
    }


    /**
     * name:        saveRecord
     *
     * insert or update one record by ID
     *
     * @param       string   $sTable        database table name
     * @param       array    $aData         assoziative array with fieldnames and values
     * @param       integer  $iItemId       if exists RowID
     * @param       string   $sIdFieldName  ID Name
     *
     * @return      RowID
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120719
     */
    public function saveRecord($sTable, $aData, $iItemId = -1, $sIdFieldName = 'id') {
        $aData = $this->filterDataArray($sTable, $aData);
        if ($iItemId != -1) {
            $this->db->where($sIdFieldName, $iItemId);
            $this->db->update($sTable, $aData);
        }
        else {
            $this->db->insert($sTable, $aData);
            $iItemId = $this->db->insert_id();
        }
        return $iItemId;
    }


    /**
     * Make sure that the incoming array does not contain any
     * fields which are not in the target table.
     *
     * @param       string   $sTable    table name
     * @param       array   $aData      assoziative Data array
     *
     * @access      public
     * @param       string
     * @param       array (call by reference)
     *
     */
    public function filterDataArray($sTable, &$aData){
        $fields = $this->db->list_fields($sTable);
        foreach ($aData as $k => $v) {
            if (in_array($k, $fields) == false){
                unset($aData[$k]);
            }
        }
        return $aData;
    }


/**
*---------------------------------------------------------------------------
* Methoden fürs Suchformular
*---------------------------------------------------------------------------
**/


    /**
     * name:        whereArr
     *
     * get all entries from selected Table
     *
     * @param       string  $searchstr      string with searchitems
     * @param       array  $fieldsarr       array with searchfields
     *
     * @return      array   $searchsql
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120711
     *
     **/
    public function whereArr($searchstr = '', $fieldsarr = array()) {

        $searcharr = $this->str_to_arr($searchstr);
        $i = 0;
        $searchsql = array();
        if (count($searcharr) > 0){
            foreach($fieldsarr AS $field){
                foreach($searcharr AS $k=>$v){
                    if($v != ''){
                        $searchsql[$i] = array($field => $v);
                        $i ++;
                    }
                }
            }
        }
        return $searchsql;
    }


    /**
     * name:        searchValid
     *
     * Validate the search inputs
     *
     * @param       string  $search    search key of the searchform
     *
     * @return      array   $searcharr
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120711
     *
     **/
    public function searchValid($forminputname = 'search'){
        if($this->input->get_post($forminputname, true) && ($this->input->get_post($forminputname, true) !='')){

            $this->form_validation->set_rules($forminputname, '', 'trim|required|xss_clean');

            if ($this->form_validation->run() == true) {
                $searchstr = $this->input->get_post($forminputname, true);

                return $searchstr;
            }
        }
        return '';
    }


    /**
     * name:        str_to_arr
     *
     * Converts String to Arr
     *
     * @param       string  $string    string separates with , / + - _
     *
     * @return      array   $array      index array
     *
     * @author      Parobri - www.parobri.ch
     * @date        20120711
     *
     **/
    private function str_to_arr($string =''){
        $array = array();
        $trennarr = array('-', '/', '+',',', '_');
        $str = str_replace($trennarr, ' ', $string);
        $str = str_replace('  ', ' ', $str);

        $array = explode(' ', $str);
        return $array;
    }





/**
 *---------------------------------------------------------------------------
 * Upload Methoden
 *---------------------------------------------------------------------------
 **/






    /**
     * name:        doImageUpload
     *
     * upload an image and create a thumbnail
     *
     * @param       string  $id         id of the entry
     * @param       string  $sIdname    fieldname of the id
     * @param       string  $sTable     table name
     * @param       string  $sFieldname name of the image field
     * @param       array   $aConfig     configdata with 1. upload_path
     *                                                   2. allowed_types
     *                                                   3. max_size
     *                                                   4. max_width
     *                                                   5. max_height
     *                                                   6. file_name
     *
     * @return      string   Bildname or Errormessage
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    //public function doUpload ($id = '', $sIdname = '', $sTable = '', $sFieldname = 'bild', $sUploadpath = '', $aConfig = array()) {
    public function doImageUpload ($id = '', $sIdname = '', $sTable = '', $sFieldname = 'bild', $aConfig = array()) {

        // mit Config füttern
        $this->load->library('upload', $aConfig);

        // wenn der upload nicht valide ist, gib Fehlermeldung zurück
        if (!$this->upload->do_upload()) {
            return 'Fehler Bildupload: ' . $this->upload->display_errors();
        }
        // wenn der upload geklappt hat...
        else{

            // Checkt ob schon ein Bild in der Tabelle vorhanden ist
            // Wenn 'ja' werden die Daten geholt und gelöscht.
            if ($id != '') {
                $bild = $this->getOne($sTable, $sFieldname, $sIdname . ' = ' . $id);
                if ($bild != '') {
                    $this->deleteImage($id, $sIdname, $sTable, $sFieldname);
                }
            }

            // Bildinformationen werden zurückgegeben
            $image_data = $this->upload->data();

            //Fileextension ändern von .jpeg auf .jpg
            if ($image_data['file_ext'] == '.jpeg' || '.png' || '.gif') {
                $image_data['file_ext'] = '.jpg';
            }

            // Bildname wird definiert
            $image_name = time() . $image_data['file_ext'] ;

            // Bild wird verkleinert (Thumbnail)
            $config = array(
                    'image_library'  => 'gd2',
                    'source_image'   => $image_data['full_path'],
                    //CI-Standart wird an Thumbnail am Schluss _thumb angefügt / gewechselt auf _thumb am Beginn der Datei
                    'new_image'      => './media/redaktion/images/' . $sTable . '/thumbs/thumb_' . $image_name,
                    'create_thumb'   => false,
                    'maintain_ratio' => true,
                    'width'          => 200,
                    'height'         => 200
            );
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            //Gibt den Bildnamen weiter an den Controller
            return $image_name;
        }
    }



    /**
     * name:        deleteImage
     *
     * delete an image and image table entry
     *
     * @param       string  $id         id of the entry
     * @param       string  $sIdname    fieldname of the id
     * @param       string  $sTable     table name
     * @param       string  $sFieldname name of the image field
     * @param       string  $sPath      imagepath
     *
     * @return      bool   true or false
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function deleteImage ($id = '', $sIdname = '', $sTable = '', $sFieldname = 'bild', $sPath = '') {
        if ($id != '') {

            // wenn kein Pfad mitgegeben wird den Standardpfad benutzen
            if($sPath !=''){
                $path = $sPath;
            }
            else {
                $path = './media/redaktion/images/' . $sTable . '/';
            }

            //löscht die Files vom Server
            $bild      = $this->getOne($sTable, $sFieldname, $sIdname . ' = ' . $id);
            $pathThumb = $path . 'thumbs/' . 'thumb_' . $bild;
            $pathOrig  = $path . $bild;
            unlink($pathThumb);
            unlink($pathOrig);

            //Datenbankeintrag wird gelöscht
            $data = array($sFieldname => '');
            $this->db->where($sIdname, $id);
            $this->db->update($sTable, $data);
            return true;
        }
        return false;
    }


    /**
     * name:        deleteFile
     *
     * delete a file and table entry
     *
     * @param       string  $id         id of the entry
     * @param       string  $sIdname    fieldname of the id
     * @param       string  $sTable     table name
     * @param       string  $sFieldname name of the image field
     * @param       string  $sPath      imagepath
     *
     * @return      string   Fname or Errormessage
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function deleteFile ($id = '', $sIdname = '', $sTable = '', $sFieldname = '', $sPath = '') {
        if ($id != '') {

            // wenn kein Pfad mitgegeben wird false ausgeben
            if($sPath !=''){

                //löscht die Files vom Server
                $bild      = $this->getOne($sTable, $sFieldname, $sIdname . ' = ' . $id);
                $pathOrig  = $sPath . $bild;
                unlink($pathOrig);

                //Datenbankeintrag wird gelöscht
                $data = array($sFieldname => '');
                $this->db->where($sIdname, $id);
                $this->db->update($sTable, $data);
                return true;
            }
        }
        return false;
    }



    /**
     * name:        doFileUpload
     *
     * upload a File
     *
     * @param       string  $id         id of the entry
     * @param       string  $sIdname    fieldname of the id
     * @param       string  $sTable     table name
     * @param       string  $sFieldname name of the table field
     * @param       array   $aConfig     configdata with 1. upload_path
     *                                                   2. allowed_types
     *                                                   3. max_size
     *                                                   4. file_name
     *
     * @return      string   Filename or Errormessage
     *
     *
     * @author      parobri.ch
     * @date        20120719
     */
    public function doFileUpload ($id = '', $sIdname = '', $sTable = '', $sFieldname = '', $aConfig = array()) {

        // mit Config füttern
        $this->load->library('upload', $aConfig);

        // wenn der upload nicht valide ist, gib Fehlermeldung zurück
        if (!$this->upload->do_upload()) {
            return 'Fehler Datei-upload: ' . $this->upload->display_errors();
        }
        // wenn der upload geklappt hat...
        else{

            // Checkt ob schon ein Bild in der Tabelle vorhanden ist
            // Wenn 'ja' werden die Daten geholt und gelöscht.
            if ($id != '') {
                $file_name_alt = $this->getOne($sTable, $sFieldname, $sIdname . ' = ' . $id);

                if ($file_name_alt != '') {
                    $this->deleteFile($id, $sIdname, $sTable, $sFieldname, $aConfig['upload_path']);
                }
            }

            // Fileinformationen werden zurückgegeben
            $file_data = $this->upload->data();

            // Filename wird definiert
            $filename = time() . $file_data['file_ext'] ;

            //Gibt den Bildnamen weiter an den Controller
            return $filename;
        }
    }

/**
 *---------------------------------------------------------------------------
 * allgemeine Methoden
 *---------------------------------------------------------------------------
 **/


    /**
     * name:        getCol
     *
     * get array width data from selected Records
     *
     * @param       string  $table    database table name
     * @param       string  $field    fieldname
     * @param       string  $where    string for WHERE-CLause
     * @param       string  $order    string for ORDER BY-part
     *
     * @return      array
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20111209
     *
     **/
    public function getCol($table,
                           $field,
                           $where = '',
                           $order = 'RAND()') {
        $items = array();
        $this->db->from($table);
        $this->db->select($field);
        if ($where != '') {
            $this->db->where($where, NULL, FALSE);
        }
        $this->db->order_by($order);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                $items[] = $row->$field;
            }
        }
        return $items;
    }


    /**
     * name:        getOne
     *
     * get value of one field
     *
     * @param       string  $table    database table name
     * @param       string  $field    fieldname
     * @param       string  $where    string for WHERE-CLause
     * @return      string
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20111209
     *
     **/
    public function getOne($table, $field, $where = '', $sort = '') {
        $this->db->from($table);
        $this->db->select($field);
        $where != '' and $this->db->where($where, NULL, FALSE);
        $sort  != '' and  $this->db->order_by($sort);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            $row = $result->result();
            return $row[0]->$field;
        }
        return '';
    }


    /**
     * name:        updateSort
     *
     * Reduces the Value of the sort field by 1 for all
     * Records following the one to be deleted
     *
     * @param       string  $sTable      database table name
     * @param       integer $iId         ID of Record to delete
     * @param       string  $sWhere      WHERE Clause for UpdateSort
     * @param       string  $sIdField    Name of field which has to match $iId
     * @return      boolean
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20120115
     */
    public function updateSort($sTable, $iId, $sWhere = '', $sIdField = 'id') {
        $iCurrentSort = $this->getOne($sTable, 'sort', $sIdField . '=' . $iId);
        $sSql = 'UPDATE ' . $this->db->dbprefix($sTable) . '
             SET sort = sort - 1
             WHERE sort > ' . $iCurrentSort;
        $sWhere != '' and $sSql .= ' AND ' . $sWhere;
        return $this->db->query($sSql);
    }


    /**
     * name:        getRowsByWhereIn
     *
     * get array width data from selected Records
     *
     * @param       string  $table    database table name
     * @param       string  $field    name of field fpr WHERE IN query
     * @param       array   $wherein_array array with values for WHERE clause
     * @param       string  $order    string for ORDER BY-part
     * @return      array
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20111209
     */
    public function getRowsByWhereIn($table,
                                     $field,
                                     $wherein_array,
                                     $order)
    {
        $this->db->where_in($field, $wherein_array);
        $this->db->order_by($order);
        $query = $this->db->get($table);
        return $query->result_array();
    }


    /**
     * name:        saveOrder
     *
     * Updates sort field for all records matching
     * values (IDs) in aIds-Array: starts with 1 and increments
     * sort field of every next record by 1
     * If ID is not the primary key, the WHERE Clause will
     * help to determine which records are affected
     *
     * @access      public
     * @param       string  $sTable    database table name
     * @param       array   $aIds      data-Array to be saved
     * @param       string  $sWhere    WHERE clause
     * @param       string  $sIdField  Name of field which has to match $iId
     *
     * @return      true
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20120115
     *
     */
    public function saveOrder($sTable, $aIds, $sWhere = '', $sIdField = 'id') {
        if (count($aIds) > 0) {
            foreach ($aIds as $i => $iId) {
                $sWhere != '' and $this->db->where($sWhere);
                $this->db->where($sIdField, $iId);
                $this->db->update($sTable, array('sort' => ($i + 1)));
            }
        }
        return true;
    }


    /**
     * name:        getNextId
     *
     * Gets next ID value for sTable
     *
     * @access      public
     * @param       string  $sTable    database table name
     * @param       string  $sWhere    WHERE clause
     *
     * @return      integer ID
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20120115
     *
     */
    public function getNextId($sTable = 'workshop', $sWhere = '') {
        return $this->getNextValue($sTable, 'id', $sWhere);
    }


    /**
     * name:        getNextSort
     *
     * Gets next sort field value for sTable
     *
     * @access      public
     * @param       string  $sTable    database table name
     * @param       string  $sWhere    WHERE clause
     *
     * @return      integer Sortfield value
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20120115
     *
     */
    public function getNextSort($sTable = 'workshop', $sWhere = '') {
        return $this->getNextValue($sTable, 'sort', $sWhere);
    }


    /**
     * name:        getNextValue
     *
     * Gets next value value for sFieldname in sTable
     *
     * @access      public
     * @param       string  $sTable      database table name
     * @param       string  $sFieldname  name of field for which next value will be calculated
     * @param       string  $sWhere      WHERE clause
     *
     * @return      integer next value or -1
     *
     * @author      Roger Klein - rklein [at] klik-info [dot] ch
     * @date        20120115
     *
     */
    public function getNextValue($sTable = '', $sFieldname = 'id', $sWhere = '') {
        if ($sTable == '') { return -1; }
        $iReturnValue = 1;
        $this->db->select_max($sFieldname, 'max');
        $sWhere != '' and $this->db->where($sWhere);
        $oResult = $this->db->get($sTable)->result();
        isset($oResult[0]) and $iReturnValue = $oResult[0]->max + 1;
        return $iReturnValue;
    }



}


/* End of file MY_Model.php */
/* Location: ./buchverwaltung/application/core/MY_Model.php */