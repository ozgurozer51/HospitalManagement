<?php
//var_dump($_GET);
/*
 * Script:    DataTables server-side script for PHP and PostgreSQL
 * Copyright: 2010 - Allan Jardine
 * License:   GPL v2 or BSD (3-point)
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */
$deger="";
$virgul="";
$bolunmus = explode(",", $_GET["gorunecekler"]); 
$virgulsayisi=count($bolunmus);
$virgulyenisayisi=1;

  foreach ($bolunmus as $d) {
//      $deger.="'";
//echo $virgulsayisi;
      $deger.=$d; 
//      $deger.="'";
      if($virgulyenisayisi!=$virgulsayisi){
          $deger.=','; }
      $virgulyenisayisi++;
   
  }
$ihsan= $deger .$virgul ;
//  echo $ihsan;
//var_dump($deger .$virgul);
//$aColumns = array($deger .$virgul );
//var_dump($ihsan);
$aColumns = explode(",",$ihsan);

//$aColumns = array('id','process_group_id','official_code','process_name','process_description');
//var_dump($aColumns);

/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";

/* DB table to use */
$sTable = $_GET["tablo"];

/* Database connection information */
$gaSql['user']       = "hbysgen_c";
$gaSql['password']   = "Bal1kesir******";
$gaSql['db']         = "hbysgen_t";
$gaSql['server']     = "incyazilim.com.tr";



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
 * no need to edit below this line
 */

/*
 * DB connection
 */
$gaSql['link'] = pg_connect(
    " host=".$gaSql['server'].
    " dbname=".$gaSql['db'].
    " user=".$gaSql['user'].
    " password=".$gaSql['password']
) or die('Could not connect: ' . pg_last_error());


/*
 * Paging
 */
$sLimit = "";
if ( isset( $_GET['start'] ) && $_GET['length'] != '-1' )
{
    $sLimit = "LIMIT ".intval( $_GET['length'] )." OFFSET ".
        intval( $_GET['start'] );
}


/*
 * Ordering
 */
if ( isset( $_GET['iSortCol_0'] ) )
{
    $sOrder = "ORDER BY  ";
    for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
    {
        if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
        {
            $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                    ".($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc').", ";
        }
    }

    $sOrder = substr_replace( $sOrder, "", -2 );
    if ( $sOrder == "ORDER BY" )
    {
        $sOrder = "";
    }
}


/*
 * Filtering
 * NOTE This assumes that the field that is being searched on is a string typed field (ie. one
 * on which ILIKE can be used). Boolean fields etc will need a modification here.
 */
$sWhere = "";
if ( $_GET['search']['value'] != "" )
{
    $sWhere = "WHERE (";
    
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if($aColumns[$i]!="id"){
        if ($_GET['columns'][$i]['searchable'] )
        {
            $sWhere .= $aColumns[$i]." like '%".pg_escape_string( $_GET['search']['value']  )."%' OR ";
        }
    }
    }
    $sWhere = substr_replace( $sWhere, "", -3 );
    $sWhere .= ")";
}
//var_dump($sWhere);
/* Individual column filtering */
for ( $i=0 ; $i<count($aColumns) ; $i++ )
{
    if($aColumns[$i]!="id"){
    if ( $_GET['columns'][$i]['searchable'] == "true" && $_GET['search']['value'] != '' )
    {
        if ( $sWhere == "" )
        {
            $sWhere = "WHERE ";
        }
        else
        {
            $sWhere .= " AND ";
        }
        $sWhere .= $aColumns[$i]." LIKE '%".pg_escape_string($_GET['searcch_'.$i])."%' ";
    }
}
}


$sQuery = "
        SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM   $sTable
        $sWhere
        $sOrder
        $sLimit
    ";
//var_dump($sQuery);
$rResult = pg_query( $gaSql['link'], $sQuery ) or die(pg_last_error());
//var_dump($sQuery);
$sQuery = "
        SELECT $sIndexColumn
        FROM   $sTable
    ";

$rResultTotal = pg_query( $gaSql['link'], $sQuery ) or die(pg_last_error());
$iTotal = pg_num_rows($rResultTotal);
pg_free_result( $rResultTotal );

if ( $sWhere != "" )
{
    $sQuery = "
            SELECT $sIndexColumn
            FROM   $sTable
            $sWhere
        ";
    $rResultFilterTotal = pg_query( $gaSql['link'], $sQuery ) or die(pg_last_error());
    $iFilteredTotal = pg_num_rows($rResultFilterTotal);
    pg_free_result( $rResultFilterTotal );
}
else
{
    $iFilteredTotal = $iTotal;
}



/*
 * Output
 */
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

while ( $aRow = pg_fetch_array($rResult, null, PGSQL_ASSOC) )
{
    $row = array();
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( $aColumns[$i] == "version" )
        {
            /* Special output formatting for 'version' column */
            $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
        }
        else if ( $aColumns[$i] != ' ' )
        {
            /* General output */
            $row[] = $aRow[ $aColumns[$i] ];
        }
    }
    $output['aaData'][] = $row;
}

echo json_encode( $output );

// Free resultset
pg_free_result($rResult);

// Closing connection
pg_close( $gaSql['link'] );
?>