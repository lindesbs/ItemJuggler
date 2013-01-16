<?php
if (!defined('TL_ROOT'))
    die('You can not access this file directly!');

$GLOBALS['TL_DCA']['tl_itemjuggler']=array(

    // Config
    'config'=> array(
        'dataContainer'=>'Table',
        'enableVersioning'=>true,
        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['title'],
        'onload_callback'=> array(),
        'onsubmit_callback'=> array( array(
                'tl_itemjuggler',
                'onsubmitCallback'
            ), )
    ),

    // List
    'list'=> array(
        'sorting'=> array(
            'mode'=>1,
            'fields'=> array('title'),
            'flag'=>1,
            'panelLayout'=>'search,sort,filter,limit ',
            'icon'=>'system/modules/taxonomy/html/icon.gif',
        ),
        'label'=> array(
            'fields'=> array(
                'title',
                'source_table',
                'source_column'
            ),
            'format'=>'%s (%s:%s)'
        ),
        'global_operations'=> array('all'=> array(
                'label'=>&$GLOBALS['TL_LANG']['MSC']['all'],
                'href'=>'act=select',
                'class'=>'header_edit_all',
                'attributes'=>'onclick="Backend.getScrollOffset();"'
            )),
        'operations'=> array(
            'edit'=> array(
                'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['edit'],
                'href'=>'act=edit',
                'icon'=>'edit.gif',
            ),
            'copy'=> array(
                'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['copy'],
                'href'=>'act=copy',
                'icon'=>'copy.gif',
            ),
            'delete'=> array(
                'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['delete'],
                'href'=>'act=delete',
                'icon'=>'delete.gif',
                'attributes'=>'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ),
            'cut'=> array(
                'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['cut'],
                'href'=>'act=paste&amp;mode=cut',
                'icon'=>'cut.gif',
                'attributes'=>'onclick="Backend.getScrollOffset();"',
            ),
            'show'=> array(
                'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['show'],
                'href'=>'act=show',
                'icon'=>'show.gif'
            ),
        )
    ),

    'metapalettes'=> array('default'=> array(
            'title'=> array('title'),
            'source'=> array(
                'source_table',
                'source_column',
                'filterpart',
                'action'
            ),

            'trigger'=> array(
                'dca_trigger_callback',
                'dca_trigger_cron',
                'logging'
            ),
        )),
    'metasubpalettes'=> array(
        'dca_trigger_callback'=> array('dca_trigger_callback_list'),
        'dca_trigger_cron'=> array('dca_trigger_cron_list')
    ),

    // Fields
    'fields'=> array(
        'title'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['title'],
            'exclude'=>true,
            'inputType'=>'text',
            'eval'=> array(
                'mandatory'=>true,
                'maxlength'=>255,
                'tl_class'=>'w50'
            )
        ),
        'logging'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['logging'],
            'exclude'=>true,
            'inputType'=>'checkbox',
            'eval'=> array()
        ),

        'source_table'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['source_table'],
            'exclude'=>true,
            'inputType'=>'select',
            'options'=>$this->Database->listTables(),
            'eval'=> array(
                'mandatory'=>true,
                'decodeEntities'=>true,
                'submitOnChange'=>true,
                'tl_class'=>'w50',
                'includeBlankOption'=>true
            )
        ),

        'source_column'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['source_column'],
            'exclude'=>true,
            'inputType'=>'select',
            'options_callback'=> array(
                'tl_itemjuggler',
                'getSourceTableColumns'
            ),
            'eval'=> array(
                'mandatory'=>true,
                'tl_class'=>'w50'
            )
        ),

        'filterpart'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['filterpart'],
            'exclude'=>true,
            'inputType'=>'multiColumnWizard',
            'eval'=> array('columnFields'=> array(

                    'field_type'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['field_type'],
                        'exclude'=>true,
                        'inputType'=>'select',
                        'options'=> array(
                            'exactly',
                            'like',
                            'startsWith',
                            'endsWith',
                            'isEmpty'
                        ),
                        'reference'=>$GLOBALS['TL_LANG']['tl_itemjuggler']['field_type'],
                        'eval'=> array('style'=>'width:180px')
                    ),

                    'query'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['query'],
                        'exclude'=>true,
                        'inputType'=>'text',

                        'eval'=> array('style'=>'width:180px')
                    ),
                    'negate'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['negate'],
                        'exclude'=>true,
                        'inputType'=>'checkbox',

                        'eval'=> array()
                    ),
                ))
        ),

        'action'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['action'],
            'exclude'=>true,
            'inputType'=>'multiColumnWizard',
            'eval'=> array('columnFields'=> array(
                    'action_type'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['action_type'],
                        'exclude'=>true,
                        'inputType'=>'select',
                        'options'=> array(
                            'rewriteTo',
                            'prepend',
                            'postpend',
                            'purge'
                        ),
                        'reference'=>$GLOBALS['TL_LANG']['tl_itemjuggler']['action_type'],
                        'eval'=> array('style'=>'width:180px')
                    ),
                    'dest_column'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['dest_column'],
                        'exclude'=>true,
                        'inputType'=>'select',
                        'options_callback'=> array(
                            'tl_itemjuggler',
                            'getDestinationTableColumns'
                        ),
                        'eval'=> array(
                            'mandatory'=>true,
                            'tl_class'=>'w50'
                        )
                    ),
                    'replacement'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['replacement'],
                        'exclude'=>true,
                        'inputType'=>'text',

                        'eval'=> array()
                    ),
                ))
        ),

        'dca_trigger_callback'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['dca_trigger_callback'],
            'exclude'=>true,
            'inputType'=>'checkbox',
            'eval'=> array('submitOnChange'=>true)
        ),
        'dca_trigger_cron'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['dca_trigger_cron'],
            'exclude'=>true,
            'inputType'=>'checkbox',
            'eval'=> array('submitOnChange'=>true)
        ),

        'dca_trigger_callback_list'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['dca_trigger_callback_list'],
            'exclude'=>true,
            'inputType'=>'multiColumnWizard',
            'eval'=> array('columnFields'=> array(
                    'tables'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['tables'],
                        'exclude'=>true,
                        'inputType'=>'select',
                        'options'=>$this->Database->listTables(),
                        'eval'=> array('style'=>'width:180px')
                    ),
                    'callbacks'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['callbacks'],
                        'exclude'=>true,
                        'inputType'=>'select',
                        'options'=> array(
                            'ondelete_callback',
                            'onload_callback',
                            'onsubmit_callback',
                            'oncut_callback',
                            'oncopy_callback',
                        ),
                        'eval'=> array(
                            'style'=>'width:250px',
                            'includeBlankOption'=>true
                        )
                    ),
                ))
        ),
        'dca_trigger_cron_list'=> array(
            'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['dca_trigger_cron_list'],
            'exclude'=>true,
            'inputType'=>'multiColumnWizard',
            'eval'=> array('columnFields'=> array('cron_type'=> array(
                        'label'=>&$GLOBALS['TL_LANG']['tl_itemjuggler']['cron_type'],
                        'exclude'=>true,
                        'inputType'=>'select',
                        'options'=>array_keys($GLOBALS['TL_CRON']),
                        'eval'=> array('style'=>'width:180px')
                    ), ))
        )
    )
);

class tl_itemjuggler extends Backend
{
    public function __construct()
    {

        $this->import("Config");
        $this->import("Input");
        $this->import("Database");
        $this->import("BackendUser", "User");

        include_once (TL_ROOT."/system/drivers/DC_Table.php");

        $GLOBALS['ITEMJUGGLER_CONFIGMODE']=true;
    }

    public function onsubmitCallback(DataContainer $dc)
    {

    }

    public function getSourceTableColumns(DataContainer $dc)
    {
        $arrReturn=array();
        if ($dc->activeRecord->source_table!='')
        {
            $arrFields=$this->Database->listFields($dc->activeRecord->source_table);

            foreach ($arrFields as $field)
            {
                $arrReturn[$field['name']]=$field['name'];

            }
        }
        return $arrReturn;
    }

    public function getDestinationTableColumns($dc)
    {

        $arrReturn=array();
        $objConfig=$this->Database->prepare("SELECT source_table FROM tl_itemjuggler WHERE id=?")->limit(1)->execute($this->Input->get("id"));
        if ($objConfig->source_table!='')
        {

            $arrFields=$this->Database->listFields($objConfig->source_table);

            foreach ($arrFields as $field)
            {
                $arrReturn[$field['name']]=$field['name'];

            }
        }
        return $arrReturn;
    }

}
?>