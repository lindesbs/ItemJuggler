<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

array_insert($GLOBALS['BE_MOD']['system'], -1, array
(
	'itemjuggler' => array (
		'tables'   => array('tl_itemjuggler'),
	),
));


$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('ItemJugglerModule', 'myLoadDataContainer');


$arrCronConfig = deserialize($GLOBALS['ITEMJUGGLER_CRON']);
if (is_array($arrCronConfig))
{
	
	foreach ($arrCronConfig as $key=>$cron)
	{
		$GLOBALS['TL_CRON'][$key] = array_merge($GLOBALS['TL_CRON'][$key],$cron);
		
	}	

}
