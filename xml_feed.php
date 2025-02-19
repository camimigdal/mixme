<?php 
require_once("/home/zuaibkjh/public_html/class/class.php");
$Obj = new mainClass;

$arr_feed =$Obj->FeedFacebook();

$objetoXML = new XMLWriter();

	// Estructura básica del XML
	$objetoXML->openURI("/home/zuaibkjh/public_html/feed.xml");
	$objetoXML->setIndent(true);
	$objetoXML->setIndentString("\t");
	$objetoXML->startDocument('1.0', 'utf-8');
	// Inicio del nodo raíz
	$objetoXML->startElement("rss");
	$objetoXML->writeAttribute("version", "2.0");
	
	$objetoXML->startElement("channel");

	foreach ($arr_feed as $prod){
		$objetoXML->startElement("item"); 

		$objetoXML->startElement("id");
		$objetoXML->text($prod["pd_id"]);
		$objetoXML->endElement();

		$objetoXML->startElement("title");
		$objetoXML->text($prod["pd_titulo"]);
		$objetoXML->endElement();

		$objetoXML->startElement("description");
		$objetoXML->text($Obj->parseToXML($prod["pd_descripcion"]));
		$objetoXML->endElement();

		$objetoXML->startElement("availability");
		$objetoXML->text("in stock");
		$objetoXML->endElement();

		$objetoXML->startElement("condition");
		$objetoXML->text("new");
		$objetoXML->endElement();

		$objetoXML->startElement("price");
		$objetoXML->text($prod["precio"]);
		$objetoXML->endElement();

		$objetoXML->startElement("link");
		$objetoXML->text($Obj->parseToXML($prod["url"]));
		$objetoXML->endElement();

		$objetoXML->startElement("image_link");
		$objetoXML->text($Obj->parseToXML($prod["imagen"]));
		$objetoXML->endElement();

		$objetoXML->startElement("brand");
		$objetoXML->text("Mixme");
		$objetoXML->endElement();

		$objetoXML->startElement("fb_product_category");
		$objetoXML->text("2");
		$objetoXML->endElement();

		$objetoXML->startElement("google_product_category");
		$objetoXML->text("433");
		$objetoXML->endElement();

		$objetoXML->startElement("product_type");
		$objetoXML->text($prod["ct_titulo"]);
		$objetoXML->endElement();

		$objetoXML->fullEndElement (); // Final del elemento "item".
	}
	
	$objetoXML->endElement(); // Final del nodo raíz, "channel"
	$objetoXML->endElement(); // Final del nodo raíz, "rss"
	$objetoXML->endDocument(); // Final del documento

	print('Ok, archivo generado');
	$Obj->insertarProceso('feed');
?>