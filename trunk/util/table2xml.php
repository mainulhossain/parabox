<?php
/** 
 * Sets of functions to convert the data loaded
 * from database to the XML documents or fragments
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/

function convert_by_dom_xml($table, $item, $rows) {

	// Start XML file, create parent node
	$doc = domxml_new_doc("1.0");
	$node = $doc->create_element($table);
	$parnode = $doc->append_child($node);
	
// Iterate through the rows, adding XML nodes for each
	while ($row = mysql_fetch_assoc($result)){
	  $node = $doc->create_element($item);
	  $newnode = $parnode->append_child($node);
	
	  foreach($row as $key => $value) {
	  	$newnode->set_attribute($key, $value);
	  }
	}
	
	$xmlfile = $doc->dump_mem();
	echo $xmlfile;
}

function convert_by_dom_func($table, $item, $rows) {

	// Start XML file, create parent node
	$dom = new DOMDocument("1.0", "UTF-8");
	$dom->formatOutput = true;
	$node = $dom->createElement($table);
	$parnode = $dom->appendChild($node); 
	

// Iterate through the rows, adding XML nodes for each
	while ($row = mysql_fetch_assoc($rows)){
	  $node = $dom->createElement($item);
	  $newnode = $parnode->appendChild($node);
	
	  foreach($row as $key => $value) {
	  	$newnode->setAttribute($key, $value);
	  }
	}
	
	// $dom->documentElement as parameter is necessary. Otherwise,
	// DOM parser can't parser the document
	return $dom->saveXML($dom->documentElement); 
}

?>