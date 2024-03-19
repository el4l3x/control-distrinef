<?php
include("Editor-PHP-2.2.2/lib/DataTables.php");
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

Editor::inst($db, 'gfc_product', 'id_product')
    ->field(
        Field::inst('gfc_product.id_product'),
        Field::inst('gfc_manufacturer.name'),
        Field::inst('gfc_product_lang.name'),
        Field::inst('gfc_product.reference'),
		Field::inst('gfc_product.mpn'),
        Field::inst('gfc_product.CodAuna'),
        Field::inst('gfc_product.stock_mags'),
        Field::inst('gfc_product.stock_abad'),
		Field::inst('gfc_product.stock_cale'),
		Field::inst('gfc_product.stock_ferre'),
        Field::inst('gfc_product.pcompra_mags'),
        Field::inst('gfc_product.pcompra_abad'),
		Field::inst('gfc_product.pcompra_cale'),
		Field::inst('gfc_product.pcompra_ferre')
    )
    ->leftJoin('gfc_product_lang', 'gfc_product_lang.id_product', '=', 'gfc_product.id_product')
	->leftJoin('gfc_manufacturer', 'gfc_manufacturer.id_manufacturer', '=', 'gfc_product.id_manufacturer')
    ->where('gfc_product.active', 1)
	->where('gfc_product.available_for_order', 1)
    ->debug(true)
    ->write(false)
    ->process($_POST)
    ->json();
?>