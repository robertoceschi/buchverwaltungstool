<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * name:        pagination_config
 *
 * create the Config File for pagination class
 *
 * @param       string  $sPaginationUrl    base url for pagination
 * @param       integer $iTotalRows        total rows
 * @param       integer $iPerPage          total rows per page
 * @param       integer $iUriSegment       total uri segments
 *
 * @return      array
 *
 * @author      Parobri - www.parobri.ch
 * @date        20120719
 *
 **/

function pagination_config($sPaginationUrl='', $iTotalRows=0, $iPerPage=15, $iUriSegment=4){

    // Anzahl Einträge gesamt
    $aConfig['total_rows']  = $iTotalRows;

    // Anzahl Einträge pro Seite
    $aConfig['per_page']    = $iPerPage;

    // Base url
    $aConfig['base_url']    = $sPaginationUrl;

    //uri_segement: an welcher Stelle wird Pagination angegeben (beispiel/beispiel/13 -> uri_segment=3)
    $aConfig['uri_segment'] = $iUriSegment;

    // Link, um auf erste Seite zu gelangen
    $aConfig['first_link']  = '&lt;&lt;';

    // Link, um auf letzte Seite zu gelangen
    $aConfig['last_link']  = '&gt;&gt;';

    // Anzahl Links (< 1 2 3 4 5 > ->num_links=5)
    $aConfig['num_links'] = 10;

    return $aConfig;
}