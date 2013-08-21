<?php

/**
 * LICENSE
 *
 * This source file is subject to the new BSD license
 * It is  available through the world-wide-web at this URL:
 * http://www.petala-azul.com/bsd.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to geral@petala-azul.com so we can send you a copy immediately.
 *
 * @package   Bvb_Grid
 * @author    Bento Vilas Boas <geral@petala-azul.com>
 * @copyright ZFDatagrid 2010
 * @license   http://www.petala-azul.com/bsd.txt   New BSD License
 * @version   $Id: Grid.php 1053 2010-03-17 23:56:09Z bento.vilas.boas@gmail.com $
 */

abstract class Bvb_Grid
{

    const VERSION = "0.6";

    /**
     * Char encoding
     *
     * @var string
     */

    protected $_charEncoding = 'UTF-8';


    /**
     * DBRM server name
     * @var string
     */
    private $_server = null;

    /**
     * Fields order
     *
     * @var unknown_type
     */
    private $_fieldsOrder;

    /**
     * The path where we can find the library
     * Usally is lib or library
     *
     * @var unknown_type
     */
    protected $_libraryDir = 'library';


    /**
     * classes location
     *
     * @var array
     */
    //TODO set template classes from config file
    protected $_template = array();

    /**
     * templates type to be used
     *
     * @var unknown_type
     */
    protected $_templates;

    /**
     * dir and prefix list to be used when formatting fields
     *
     * @var unknown_type
     */
    protected $_formatter;

    /**
     * Number of results per page
     *
     * @var int
     */
    protected $_pagination = 15;

    /**
     * Type of export available
     *
     * @var array
     */
    protected $_export = array('pdf', 'word', 'wordx', 'excel', 'print', 'xml', 'csv', 'ods', 'odt', 'json');
    #protected $_export = array('pdf', 'word', 'wordx', 'excel', 'print', 'xml', 'csv', 'ods', 'odt', 'json','ofc');

    /**
     * All info that is not directly related to the database
     */
    protected $_info = array();

    /**
     * Registry for PK
     */
    protected $_primaryKey = array();

    /**
     * Baseurl
     *
     * @var string
     */
    protected $_baseUrl;

    /**
     * Array containing the query result from table(s)
     *
     * @var array
     */
    protected $_result;

    /**
     * Total records from db query
     *
     * @var int
     */
    protected $_totalRecords;

    /**
     * Array containing field titles
     *
     * @var array
     */
    protected $_titles;

    /**
     * Array containing table(s) fields
     *
     * @var array
     */
    protected $_fields = array();


    /**
     * Filters list
     *
     * @var array
     */
    protected $_filters;

    /**
     * Filters values inserted by the user
     *
     * @var array
     */
    protected $_filtersValues;

    /**
     * All information databse related
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Params list
     *
     * @var array
     */
    protected $_paramsAux = array();

    /**
     * URL params
     *
     * @var string
     */
    protected $_ctrlParams = array();

    /**
     * Extra fields array
     *
     * @var array
     */
    protected $_extraFields = array();

    /**
     * Final fields list (after all procedures).
     *
     *
     * @var unknown_type
     */
    protected $_finalFields;

    /**
     *Use cache or not.
     * @var bool
     */
    protected $_cache = false;

    /**
     * The field to set order by, if we have a horizontal row
     *
     * @var string
     */
    private $_fieldHorizontalRow;

    /**
     * Template instance
     *
     * @var unknown_type
     */
    protected $_temp;

    /**
     * Result untouched
     *
     * @var array
     */
    private $_resultRaw;

    /**
     * Check if all columns have been added by ->query()
     * @var bool
     */
    private $_allFieldsAdded = false;

    /**
     * If the user manually sets the query limit
     * @var int|bool
     */
    protected $_forceLimit = false;

    /**
     * Default filters to be applyed
     * @var array
     * @return array
     */
    protected $_defaultFilters;

    /**
     * Instead throwing an exception,
     * we queue the field list and call this in
     * getFieldsFromQuery()
     * @var array
     */
    protected $_updateColumnQueue = array();

    /**
     * List of callback functions to apply
     * on grid deploy and ajax
     * @var $_configCallbacks
     */
    protected $_configCallbacks = array();

    /**
     * Treat hidden fields as 'remove'
     * @var bool
     */
    protected $_removeHiddenFields = false;

    /**
     * Functions to be aplied on every field sbefore dislpay
     * @var unknown_type
     */
    protected $_escapeFunction = 'htmlspecialchars';


    /**
     * Grid Options.
     * They can be
     * @var array
     */
    protected $_options = array();

    /**
     * Id used for multiples insatnces onde the same page
     *
     * @var string
     */
    protected $_gridId;

    /**
     * Colspan for table
     * @var int
     */
    protected $_colspan;

    /**
     * User definid INFO for templates
     * @var array
     */
    protected $_templateParams = array();

    /**
     * To let a user know if the grid will be displayed or not
     * @var unknown_type
     */
    protected $_showsGrid = false;


    /**
     * Array of fields that should appear on detail view
     * @var unknown_type
     */
    protected $_gridColumns = null;


    /**
     * Array of columns that should appear on detail view
     * @var unknown_type
     */
    protected $_detailColumns = null;

    /**
     * If we are on detail or grid view
     * @var unknown_type
     */
    protected $_isDetail = false;


    /**
     * @var Zend_View_Interface
     */
    protected $_view;


    /**
     *
     * @var Bvb_Grid_Source_Interface
     */
    private $_source;

    /**
     * Last name from deploy class (table|pdf|csv|etc...)
     * @var unknown_type
     */
    private $_deployName = null;


    /**
     * What is beeing done with this request
     * @var unknown_type
     */
    protected $_willShow = array();


    /**
     * Print class based on conditions
     * @var array
     */
    protected $_classRowCondition = array();

    /**
     * Result to apply to every <tr> based on condition
     * @var $_classRowConditionResult array
     */
    protected $_classRowConditionResult = array();

    /**
     * Condition to apply a CSS class to a table cell <td>
     * @var unknown_type
     */
    protected $_classCellCondition = array();

    /**
     * Order setted by adapter
     * @var unknown_type
     */
    protected $_order;


    /**
     * @param $object
     */
    function query ($object)
    {

        if ( $object instanceof Zend_Db_Select ) {
            $this->setSource(new Bvb_Grid_Source_Zend_Select($object));
        } elseif ( $object instanceof Zend_Db_Table_Abstract ) {
            $this->setSource(new Bvb_Grid_Source_Zend_Table($object));
        } else {
            throw new Bvb_Grid_Exception('Please use the setSource() method instead');
        }

        return $this;

    }


    /**
     * Sets the source to be used
     *
     * Bvb_Grid_Source_*
     *
     * @param $source
     */
    function setSource (Bvb_Grid_Source_Interface $source)
    {

        $this->_source = $source;

        $this->getSource()->setCache($this->getCache());

        $tables = $this->getSource()->getMainTable();

        $this->_data['table'] = $tables['table'];
        $this->_crudTable = $this->_data['table'];

        $fields = $this->getSource()->buildFields();
        foreach ( $fields as $key => $field ) {
            $this->updateColumn($key, $field);
        }

        $this->_allFieldsAdded = true;

        return $this;
    }


    /**
     * The path where we can find the library
     * Usally is lib or library
     * @param $dir
     */
    function setLibraryDir ($dir)
    {
        $this->_libraryDir = $dir;
        return $this;
    }


    /**
     * Returns the actual library path
     */
    function getLibraryDir ()
    {
        return $this->_libraryDir;
    }


    /**
     * Sets grid cache
     * @param bool|array $cache
     */
    function setCache ($cache)
    {

        if ( $cache == false || (is_array($cache) && isset($cache['use']) && $cache['use'] == 0) ) {
            $this->_cache = array('use' => 0);
            return $this;
        }

        if ( is_array($cache) && isset($cache['use']) && isset($cache['instance']) && isset($cache['tag']) ) {
            $this->_cache = $cache;
            return $this;
        }

        return false;

    }


    function getCache ()
    {
        return $this->_cache;
    }


    /**
     * Returns the actual source object
     */
    function getSource ()
    {
        return $this->_source;
    }


    /**
     * Get db instance
     * @return Zend_Db_Adapter_Abstract
     */
    protected function _getDb ()
    {
        return $this->_db;
    }


    /**
     * The __construct function receives the db adapter. All information related to the
     * URL is also processed here
     *
     * @param array $data
     */
    function __construct ($options)
    {

        if ( ! $this instanceof Bvb_Grid_Deploy_Interface ) {
            throw new Bvb_Grid_Exception(get_class($this) . ' needs to implment the Bvb_Grid_Deploy_Interface');
        }

        if ( $options instanceof Zend_Config ) {
            $options = $options->toArray();
        } else if ( ! is_array($options) ) {
            throw new Bvb_Grid_Exception('options must be an instance from Zend_Config or an array');
        }

        $this->_options = $options;

        //Get the controller params and baseurl to use with filters
        $this->setParams(Zend_Controller_Front::getInstance()->getRequest()->getParams());
        $this->_baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();


        /**
         * plugins loaders
         */
        $this->_formatter = new Zend_Loader_PluginLoader();

        //Templates loading
        if ( is_array($this->_export) ) {
            foreach ( $this->_export as $temp ) {
                $this->_templates[$temp] = new Zend_Loader_PluginLoader(array());
            }
        }

        // Add the formatter fir for fields content
        $this->addFormatterDir('Bvb/Grid/Formatter', 'Bvb_Grid_Formatter');

        //Apply options to the fields
        $this->_applyOptionsToFields();

        $deploy = explode('_', get_class($this));
        $this->_deployName = strtolower(end($deploy));

    }


    /**
     * Set view object
     *
     * @param Zend_View_Interface $view view object to use
     *
     * @return Bvb_Grid_Deploy_JqGrid
     */
    public function setView (Zend_View_Interface $view = null)
    {
        $this->_view = $view;
        return $this;
    }


    /**
     * Retrieve view object
     *
     * If none registered, attempts to pull from ViewRenderer.
     *
     * @return Zend_View_Interface|null
     */
    public function getView ()
    {
        if ( null === $this->_view ) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $this->setView($viewRenderer->view);
        }

        return $this->_view;
    }


    /**
     * Sets the functions to be used to apply th each value
     * before fisplay
     * @param array $functions
     */
    public function setDefaultEscapeFunction ($functions)
    {
        $this->_escapeFunction = $functions;
        return $this;
    }


    /**
     * Returns the active escape functions
     */
    public function getDefaultEscapeFunction ()
    {
        return $this->_escapeFunction;
    }


    /**
     * Character encoding
     *
     * @param string $encoding
     * @return unknown
     */
    public function setcharEncoding ($encoding)
    {
        $this->_charEncoding = $encoding;
        return $this;
    }


    function getCharEncoding ()
    {
        return $this->_charEncoding;
    }


    /**
     * The translator
     *
     * @param string $message
     * @return string
     */
    protected function __ ($message)
    {
        if ( strlen($message) == 0 ) {
            return $message;
        }

        if ( Zend_Registry::isRegistered('Zend_Translate') ) {
            $message = Zend_Registry::get('Zend_Translate')->translate($message);
        }
        return $message;
    }


    /**
     * Use the overload function so we can return an object
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function __call ($name, $value)
    {

        if ( substr(strtolower($name), 0, 6) == 'source' ) {

            $meth = substr($name, 6);
            $meth[0] = strtolower($meth[0]);

            if ( is_object($this->getSource()) && method_exists($this->getSource(), $meth) ) {
                $this->getSource()->$meth();
                return $this;
            }
        }

        $class = $this->_deployName;


        if ( $name == 'set' . ucfirst($class) . 'GridColumns' ) {
            $this->setGridColumns($value[0]);
            return $this;
        }

        if ( $name == 'set' . ucfirst($class) . 'DetailColumns' ) {
            $this->setDetailColumns($value[0]);
            return $this;
        }

        if ( substr(strtolower($name), 0, strlen($class) + 3) == 'set' . $class ) {
            $name = substr($name, strlen($class) + 3);
            $name[0] = strtolower($name[0]);
            $this->deploy[$name] = $value[0];
            return $this;
        }

        if ( substr(strtolower($name), 0, 3) == 'set' ) {
            $name = substr($name, 3);

            if ( ! isset($value[0]) ) {
                $value[0] = null;
            }
            $this->__set($name, $value[0]);
        } else {
            throw new Bvb_Grid_Exception("call to unknown function $name");
        }

        return $this;
    }


    /**
     * @param string $var
     * @param string $value
     */
    public function __set ($var, $value)
    {
        $var[0] = strtolower($var[0]);
        $this->_info[$var] = $value;
        return $this;
    }


    /**
     * Update data from a column
     *
     * @param string $field
     * @param array $options
     * @return self
     */

    public function updateColumn ($field, $options = array())
    {
        if ( null == $this->getSource() ) {
            /**
             * Add to the queue and call it from the getFieldsFromQuery() method
             * @var $_updateColumnQueue Bvb_Grid
             */
            if ( isset($this->_updateColumnQueue[$field]) ) {
                $this->_updateColumnQueue[$field] = array_merge($this->_updateColumnQueue[$field], $options);
            } else {
                $this->_updateColumnQueue[$field] = $options;
            }

            return $this;
        }

        if ( $this->_allFieldsAdded == false ) {

            $this->_data['fields'][$field] = $options;

        } elseif ( array_key_exists($field, $this->_data['fields']) ) {

            if ( isset($options['hRow']) && $options['hRow'] == 1 ) {
                $this->_fieldHorizontalRow = $field;
                $this->_info['hRow'] = array('field' => $field, 'title' => $options['title']);
            }

            $this->_data['fields'][$field] = array_merge($this->_data['fields'][$field], $options);
        }

        return $this;
    }


    /**
     * Set option hidden=1 on several columns
     * @param $columns
     */
    function setColumnsHidden (array $columns)
    {
        foreach ( $columns as $column ) {
            $this->updateColumn($column, array('hidden' => 1));
        }
        return $this;
    }


    /**
     * Add a new dir to look for when formating a field
     *
     * @param string $dir
     * @param string $prefix
     * @return $this
     */
    public function addFormatterDir ($dir, $prefix)
    {
        $this->_formatter->addPrefixPath(trim($prefix, "_"), trim($dir, "/") . '/');
        return $this;
    }


    /**
     * Format a field
     *
     * @param unknown_type $value
     * @param unknown_type $formatter
     * @return unknown
     */
    protected function _applyFormat ($value, $formatter)
    {

        if ( is_array($formatter) ) {
            $result = reset($formatter);
            if(!isset($formatter[1]))
            {
                $formatter[1] = array();
            }

            $options = (array) $formatter[1];
        } else {
            $result = $formatter;
            $options = array();
        }

        $class = $this->_formatter->load($result);


        $t = new $class($options);


        if(!$t instanceof  Bvb_Grid_Formatter_Interface)
        {
            throw new Bvb_Grid_Exception("$class must implement the Bvb_Grid_Formatter_Interface");
        }

        $return = $t->format($value);

        return $return;
    }


    /**
     * The allowed fields from a table
     *
     * @param string $mode
     * @param string $table
     * @return string
     */
    protected function _getFields ($mode, $table)
    {

        $get = $this->getInfo("$mode,fields");
        if ( ! is_array($get) ) {
            $get = $this->_getTableFields($table);
        }
        return $get;
    }


    /**
     * Get table fields
     *
     * @param string $table
     * @return string
     */
    protected function _getTableFields ($table)
    {

        $table = $this->getSource()->getDescribeTable($table);
        foreach ( array_keys($table) as $key ) {
            $val[$key] = $key;
        }
        return $val;
    }


    /**
     * pagination definition
     *
     */
    public function setPagination ($number = 15)
    {
        $this->_pagination = (int) $number;
        return $this;
    }


    /**
     * Default values for filters.
     * Thy will be applied before displaying. However the user can still remove them.
     * @param $filters
     */
    public function setDefaultFiltersValues (array $filters)
    {
        $this->_defaultFilters = array_flip($filters);
        return $this;
    }


    /**
     * Build the query WHERE
     *
     * @return void
     */
    protected function _buildFiltersValues ()
    {

        //Build an array to know filters values
        $valor_filters = array();
        $filters = @urldecode($this->getParam('filters'));
        $filters = str_replace("filter_", "", $filters);

        if ( strlen($filters) > 5 ) {
            $filters = Zend_Json::decode($filters);
        }

        $fieldsSemAsFinal = $this->_data['fields'];

        if ( is_array($filters) ) {

            foreach ( $filters as $key => $filter ) {

                if ( strlen($filter) == 0 || ! in_array($key, $this->_fields) ) {
                    unset($filters[$key]);
                } else {
                    if ( isset($fieldsSemAsFinal[$key]['searchField']) ) {
                        $key = $fieldsSemAsFinal[$key]['searchField'];
                    }


                    $oldFilter = $filter;
                    if( isset($this->_filters[$key]['transform']) && is_callable($this->_filters[$key]['transform']))
                    {
                        $filter = call_user_func($this->_filters[$key]['transform'],$filter);
                    }

                    if ( isset($this->_filters[$key]['callback']) && is_array($this->_filters[$key]['callback']) ) {

                        if ( ! is_callable($this->_filters[$key]['callback']['function']) ) {
                            throw new Bvb_Grid_Exception($this->_filters[$key]['callback']['function'] . ' is not callable');
                        }

                        if ( ! isset($this->_filters[$key]['callback']['params']) || ! is_array($this->_filters[$key]['callback']['params']) ) {
                            $this->_filters[$key]['callback']['params'] = array();
                        }

                        $this->_filters[$key]['callback']['params'] = array_merge($this->_filters[$key]['callback']['params'], array('field' => $key, 'value' => $filter, 'select' => $this->getSource()->getSelectObject()));

                        $result = call_user_func($this->_filters[$key]['callback']['function'], $this->_filters[$key]['callback']['params']);

                    } elseif ( isset($this->_data['fields'][$key]['search']) && is_array($this->_data['fields'][$key]['search']) && $this->_data['fields'][$key]['search']['fulltext'] == true ) {
                        $this->getSource()->addFullTextSearch($filter, $key, $this->_data['fields'][$key]);
                    } else {

                        $op = $this->getFilterOp($key, $filter);

                        $this->getSource()->addCondition($op['filter'], $op['op'], $this->_data['fields'][$key]);
                    }

                    $valor_filters[$key] = $oldFilter;
                }

            }
        }

        $this->_filtersValues = $valor_filters;

        return $this;
    }


    function getFilterOp ($field, $filter)
    {

        if ( ! isset($this->_data['fields'][$field]['searchType']) ) {
            $this->_data['fields'][$field]['searchType'] = 'like';
        }

        $op = strtolower($this->_data['fields'][$field]['searchType']);

        if ( substr(strtoupper($filter), 0, 2) == 'R:' ) {
            $op = 'REGEX';
            $filter = substr($filter, 2);
        } elseif ( strpos($filter, '<>') !== false && substr($filter, 0, 2) != '<>' ) {
            $op = 'range';
        } elseif ( substr($filter, 0, 1) == '=' ) {
            $op = '=';
            $filter = substr($filter, 1);
        } elseif ( substr($filter, 0, 2) == '>=' ) {
            $op = '>=';
            $filter = substr($filter, 2);
        } elseif ( $filter[0] == '>' ) {
            $op = '>';
            $filter = substr($filter, 1);
        } elseif ( substr($filter, 0, 2) == '<=' ) {
            $op = '<=';
            $filter = substr($filter, 2);
        } elseif ( substr($filter, 0, 2) == '<>' || substr($filter, 0, 2) == '!=' ) {
            $op = '<>';
            $filter = substr($filter, 2);
        } elseif ( $filter[0] == '<' ) {
            $op = '<';
            $filter = substr($filter, 1);
        } elseif ( $filter[0] == '*' and substr($filter, - 1) == '*' ) {
            $op = 'like';
            $filter = substr($filter, 1, - 1);
        } elseif ( $filter[0] == '*' and substr($filter, - 1) != '*' ) {
            $op = 'llike';
            $filter = substr($filter, 1);
        } elseif ( $filter[0] != '*' and substr($filter, - 1) == '*' ) {
            $op = 'rlike';
            $filter = substr($filter, 0, - 1);
        } elseif ( stripos($filter, ',') !== false ) {
            $op = 'IN';
        }

        if ( isset($this->_data['fields']['searchTypeFixed']) && $this->_data['fields']['searchTypeFixed'] === true && $op != $this->_data['fields']['searchType'] ) {
            $op = $this->_data['fields']['searchType'];
        }

        return array('op' => $op, 'filter' => $filter);
    }


    /**
     * Build query.
     *
     * @return string
     */
    protected function _buildQueryOrderAndLimit ()
    {

        @$start = (int) $this->getParam('start');
        $order = $this->getParam('order');
        $order1 = explode("_", $order);
        $orderf = strtoupper(end($order1));

        if ( $orderf == 'DESC' || $orderf == 'ASC' ) {
            array_pop($order1);
            $order_field = implode("_", $order1);

            $this->getSource()->buildQueryOrder($order_field, $orderf);

            if ( in_array($order_field, $this->_fieldsOrder) ) {
                $this->getSource()->buildQueryOrder($order_field, $orderf, true);
            }
        }

        if ( strlen($this->_fieldHorizontalRow) > 0 ) {
            $this->getSource()->buildQueryOrder($this->_fieldHorizontalRow, 'ASC', true);
        }

        if ( false === $this->_forceLimit ) {
            $this->getSource()->buildQueryLimit($this->_pagination, $start);
        }

        return true;
    }


    /**
     * Returns the url, without the param(s) specified
     *
     * @param array|string $situation
     * @return string
     */
    public function getUrl ($situation = '')
    {

        //this array the a list of params that name changes
        //based on grid id. The id is prepended to the name
        $paramsGet = array('order', 'start', 'filters', 'noFilters', '_exportTo', 'add', 'edit', 'noOrder', 'comm', 'gridDetail', 'gridRemove');

        $url = '';
        $params = $this->getAllParams();

        if ( is_array($situation) ) {
            foreach ( $situation as $value ) {
                if ( in_array($value, $paramsGet) ) {
                    $value = $value . $this->getGridId();
                }
                unset($params[$value]);
            }

        } else {
            if ( in_array($situation, $paramsGet) ) {
                $situation = $situation . $this->getGridId();
            }
            unset($params[$situation]);
        }

        if ( count($this->_paramsAux) > 0 ) {
            //User as defined its own params (probably using routes)
            $myParams = array('comm', 'order', 'filters', 'add', 'edit', '_exportTo');
            $newParams = $this->_paramsAux;
            foreach ( $myParams as $value ) {
                if ( strlen($params[$value]) > 0 ) {
                    $newParams[$value] = $params[$value];
                }
            }
            $params = $newParams;
        }

        $params_clean = $params;
        unset($params_clean['controller']);
        unset($params_clean['module']);
        unset($params_clean['action']);
        unset($params_clean['gridmod']);

        /*foreach ( $params_clean as $key => $param ) {
            // Apply the urldecode function to the filtros param, because its JSON
            if ( $key == 'filters' . $this->getGridId() ) {
                $url .= "/" . trim($key) . "/" . trim(htmlspecialchars(urlencode($param), ENT_QUOTES));
            } else {
                @$url .= "/" . trim($key) . "/" . trim(htmlspecialchars($param, ENT_QUOTES));
            }
        }*/
        if(!empty($params_clean))
        $url .= '?' . http_build_query($params_clean);

        if ( strlen($params['action']) > 0 ) {
            $action = "/" . $params['action'];
        }

        if ( Zend_Controller_Front::getInstance()->getDefaultModule() != $params['module'] ) {
            $urlPrefix = $params['module'] . "/";
        } else {
            $urlPrefix = '';
        }

        // Remove the action e controller keys, they are not necessary (in fact they aren't part of url)
        if ( array_key_exists('ajax', $this->_info) && $this->getInfo('ajax') !== false ) {
            return $urlPrefix . $params['controller'] . $action . $url . "/gridmod/ajax";
        } else {
            return $this->_baseUrl . "/" . $urlPrefix . $params['controller'] . $action . $url;
        }
    }


    /**
     * Return variable stored in info. Return default if value is not stored.
     *
     * @param string $param
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getInfo ($param, $default = false)
    {
        if ( isset($this->_info[$param]) ) {
            return $this->_info[$param];
        } elseif(strpos($param,',')) {

            $params = explode(',',$param);
            $param = array_map('trim',$params);

            $final = $this->_info;

            foreach ($params as $check)
            {
                if(!isset($final[$check]))
                {
                    return $default;
                }
                $final = $final[$check];
            }

            return $final;
        }

        return $default;
    }


    /**
     *
     * Build Filters. If defined put the values
     * Also check if the user wants to hide a field
     *
     *
     * @return string
     */
    protected function _buildFilters ()
    {

        $return = array();
        if ( $this->getInfo('noFilters') == 1 ) {
            return false;
        }

        $data = $this->_fields;

        $tcampos = count($data);

        for ( $i = 0; $i < count($this->_extraFields); $i ++ ) {
            if ( $this->_extraFields[$i]['position'] == 'left' ) {
                $return[] = array('type' => 'extraField', 'class' => isset($this->_template['classes']['filter'])?$this->_template['classes']['filter']:'', 'position' => 'left');
            }
        }

        for ( $i = 0; $i < $tcampos; $i ++ ) {

            $nf = $this->_fields[$i];

            if ( ! isset($this->_data['fields'][$nf]['search']) ) {
                $this->_data['fields'][$nf]['search'] = true;
            }

            if ( $this->_displayField($nf) ) {


                if ( @array_key_exists($data[$i], $this->_filters) && $this->_data['fields'][$nf]['search'] != false ) {
                    $return[] = array('type' => 'field', 'class' => isset($this->_template['classes']['filter'])?$this->_template['classes']['filter']:'', 'value' => isset($this->_filtersValues[$data[$i]]) ? $this->_filtersValues[$data[$i]] : '', 'field' => $data[$i]);
                } else {
                    $return[] = array('type' => 'field', 'class' => @$this->_template['classes']['filter'], 'field' => $data[$i]);
                }
            }
        }

        for ( $i = 0; $i < count($this->_extraFields); $i ++ ) {
            if ( $this->_extraFields[$i]['position'] == 'right' ) {
                $return[] = array('type' => 'extraField', 'class' => @$this->_template['classes']['filter'], 'position' => 'right');
            }
        }

        return $return;
    }


    /**
     *
     * @param string $field
     * @return bool
     */
    protected function _displayField ($field)
    {

        if ( ! isset($this->_data['fields'][$field]['remove']) ) {
            $this->_data['fields'][$field]['remove'] = false;
        }
        if ( ! isset($this->_data['fields'][$field]['hidden']) ) {
            $this->_data['fields'][$field]['hidden'] = false;
        }

        if ( $this->_data['fields'][$field]['remove'] == 0 && (($this->_data['fields'][$field]['hidden'] == 0) || ($this->_data['fields'][$field]['hidden'] == 1 && $this->_removeHiddenFields !== true)) ) {

            return true;
        }

        return false;

    }


    /**
     *
     * @param array $fields
     * @return array
     */
    protected function _prepareReplace ($fields)
    {
        return array_map(create_function('$value', 'return "{{{$value}}}";'), $fields);
    }


    /**
     * Build the titles with the order links (if wanted)
     *
     * @return string
     */
    protected function _buildTitles ()
    {

        $return = array();
        $url = $this->getUrl(array('order', 'start', 'comm', 'noOrder'));

        $tcampos = count($this->_fields);

        for ( $i = 0; $i < count($this->_extraFields); $i ++ ) {
            if ( $this->_extraFields[$i]['position'] == 'left' ) {
                $return[$this->_extraFields[$i]['name']] = array('type' => 'extraField', 'value' => $this->__($this->_extraFields[$i]['name']), 'position' => 'left');
            }
        }

        $titles = $this->_fields;

        $novaData = array();

        if ( is_array($this->_data['fields']) ) {
            foreach ( $this->_data['fields'] as $key => $value ) {
                $nkey = stripos($key, ' AS ') ? substr($key, 0, stripos($key, ' AS ')) : $key;
                $novaData[$nkey] = $value;
            }
        }

        $links = $this->_fields;

        if ( ! $this->getParam('noOrder') ) {
            $selectOrder = $this->getSource()->getSelectOrder();

            if ( count($selectOrder) == 1 ) {
                $this->setParam('order' . $this->getGridId(), $selectOrder[0] . '_' . strtoupper($selectOrder[1]));
            }
        }

        for ( $i = 0; $i < $tcampos; $i ++ ) {
            if ( $this->getParam('order') ) {
                $explode = explode('_', $this->getParam('order'));
                $name = str_replace('_' . end($explode), '', $this->getParam('order'));
                $this->_order[$name] = strtoupper(end($explode)) == 'ASC' ? 'DESC' : 'ASC';
            }

            $fieldsToOrder = $this->_resetKeys($this->_data['fields']);

            if ( isset($fieldsToOrder[$i]['orderField']) && strlen($fieldsToOrder[$i]['orderField']) > 0 ) {
                $orderFinal = $fieldsToOrder[$i]['orderField'];
            } else {
                $orderFinal = $titles[$i];
            }

            if(is_array($this->_order))
            {
               $order = $orderFinal == @key($this->_order) ? $this->_order[$orderFinal] : 'ASC';
            }else{
                $order = 'ASC';
            }

            if ( $this->_displayField($titles[$i]) ) {

                $noOrder = $this->getInfo('noOrder') ? $this->getInfo('noOrder') : '';

                if ( $noOrder == 1 ) {
                    $return[$titles[$i]] = array('type' => 'field', 'name' => $links[$i], 'field' => $links[$i], 'value' => $this->__($this->_titles[$links[$i]]));
                } else {
                    $return[$titles[$i]] = array('type' => 'field', 'name' => $titles[$i], 'field' => $orderFinal, 'simpleUrl' => $url, 'url' => "$url/order{$this->getGridId()}/{$orderFinal}_$order", 'value' => $this->__($this->_titles[$links[$i]]));
                }
            }
        }

        for ( $i = 0; $i < count($this->_extraFields); $i ++ ) {
            if ( $this->_extraFields[$i]['position'] == 'right' ) {
                $return[$this->_extraFields[$i]['name']] = array('type' => 'extraField', 'value' => $this->__($this->_extraFields[$i]['name']), 'position' => 'right');
            }
        }

        $this->_finalFields = $return;


        return $return;
    }


    protected function _replaceSpecialTags (&$item, $key, $text)
    {
        $item = str_replace($text['find'], $text['replace'], $item);
    }


    /**
     * Aplies the format option to a field
     * @param $new_value
     * @param $value
     * @param $search
     * @param $replace
     */
    protected function _applyFieldFormat ($new_value, $value, $search, $replace)
    {
        if ( is_array($value) ) {
            array_walk_recursive($value, array($this, '_replaceSpecialTags'), array('find' => $search, 'replace' => $replace));
        }

        return $this->_applyFormat($new_value, $value);
    }


    /**
     * Applies the callback option to a field
     * @param unknown_type $new_value
     * @param unknown_type $value
     * @param unknown_type $search
     * @param unknown_type $replace
     */
    protected function _applyFieldCallback ($new_value, $value, $search, $replace)
    {

        if ( ! is_callable($value['function']) ) {
            throw new Bvb_Grid_Exception($value['function'] . ' not callable');
        }

        if ( isset($value['params']) && is_array($value['params']) ) {
            $toReplace = $value['params'];
            $toReplaceArray = array();
            $toReplaceObj = array();

            foreach ( $toReplace as $key => $rep ) {
                if ( is_scalar($rep) || is_array($rep) ) {
                    $toReplaceArray[$key] = $rep;
                } else {
                    $toReplaceObj[$key] = $rep;
                }
            }

        } else {
            return call_user_func($value['function']);
        }

        if ( is_array($toReplace) ) {
            array_walk_recursive($toReplaceArray, array($this, '_replaceSpecialTags'), array('find' => $search, 'replace' => $replace));
        }

        for ( $i = 0; $i <= count($toReplace); $i ++ ) {
            if ( isset($toReplaceArray[$i]) ) {
                $toReplace[$i] = $toReplaceArray[$i];
            } elseif ( isset($toReplaceObj[$i]) ) {
                $toReplace[$i] = $toReplaceObj[$i];
            }
        }

        return call_user_func_array($value['function'], $toReplace);

    }


    /**
     * Aplies the decorator to a fields
     * @param unknown_type $find
     * @param unknown_type $replace
     * @param unknown_type $value
     */
    protected function _applyFieldDecorator ($find, $replace, $value)
    {
        return str_replace($find, $replace, $value);
    }


    /**
     * Applies escape functions to a field
     * @param  $value
     */
    protected function _applyFieldEscape ($value)
    {
        if ( $this->_escapeFunction === false ) {
            return $value;
        }

        if ( ! is_callable($this->_escapeFunction) ) {
            throw new Bvb_Grid_Exception($this->_escapeFunction . ' not callable');
        }

        $value = call_user_func($this->_escapeFunction, $value);
        return $value;

    }


    /**
     * Apply escape functions to column
     * @param string $field
     * @param string $new_value
     * @return mixed
     */
    private function _escapeField ($field, $new_value)
    {

        if ( ! isset($this->_data['fields'][$field]['escape']) ) {
            $this->_data['fields'][$field]['escape'] = 1;
        }

        if ( ($this->_data['fields'][$field]['escape'] ? 1 : 0) == 0 ) {
            return $new_value;
        }

        if ( $this->_data['fields'][$field]['escape'] == 1 ) {
            return $this->_applyFieldEscape($new_value);
        }

        if ( ! is_callable($this->_data['fields'][$field]['escape']) ) {
            throw new Bvb_Grid_Exception($this->_data['fields'][$field]['escape'] . ' not callable');
        }

        return call_user_func($this->_data['fields'][$field]['escape'], $new_value);

    }


    protected function _applyFieldHelper ($new_value, $value, $search, $replace)
    {

        if ( is_array($value) ) {
            array_walk_recursive($value, array($this, '_replaceSpecialTags'), array('find' => $search, 'replace' => $replace));
        }

        $name = $value['name'];
        $t = $this->getView()->getHelper($name);
        $re = new ReflectionMethod($t, $name);

        if ( isset($value['params']) && is_array($value['params']) ) {
            $new_value = $re->invokeArgs($t, $value['params']);
        } else {
            $new_value = $re->invoke($t);
        }

        return $new_value;
    }


    /**
     * The loop for the results.
     * Check the extra-fields,
     *
     * @return string
     */
    protected function _buildGrid ()
    {

        $return = array();

        $search = $this->_prepareReplace($this->_fields);

        $fields = $this->_fields;

        $i = 0;


        $classConditional = array();
        foreach ( $this->_result as $dados ) {

            $outputToReplace = array();
            foreach ( array_combine($fields, $fields) as $key => $value ) {
                $outputToReplace[$key] = $dados[$value];
            }

            if ( $this->_deployName == 'table' ) {


                if ( isset($this->_classRowCondition[0]) && is_array($this->_classRowCondition[0]) ) {
                    $this->_classRowConditionResult[$i] = '';

                    foreach ( $this->_classRowCondition as $key => $value ) {
                        $cond = str_replace($search, $outputToReplace, $value['condition']);
                        $final = call_user_func(create_function('', "if($cond){return true;}else{return false;}"));
                        $this->_classRowConditionResult[$i] .= $final == true ? $value['class'] . ' ' : $value['else'] . ' ';
                    }

                } else {
                    $this->_classRowConditionResult[$i] = '';
                }

                $this->_classRowConditionResult[$i] .= ($i % 2) ? $this->_cssClasses['even'] : $this->_cssClasses['odd'];


                if ( count($this->_classCellCondition) > 0 ) {
                    foreach ( $this->_classCellCondition as $key => $value ) {
                        $classConditional[$key] = '';
                        foreach ( $value as $condFinal ) {
                            $cond = str_replace($search, $outputToReplace, $condFinal['condition']);
                            $final = call_user_func(create_function('', "if($cond){return true;}else{return false;}"));
                            $classConditional[$key] .= $final == true ? $condFinal['class'] . ' ' : $condFinal['else'] . ' ';
                        }
                    }
                }

            }
            /**
             *Deal with extrafield from the left
             */

            foreach ( $this->_getExtraFields('left') as $value ) {

                $value['class'] = ! isset($value['class']) ? '' : $value['class'];

                $value['style'] = ! isset($value['style']) ? '' : $value['style'];


                $new_value = '';

                if ( isset($value['format']) ) {
                    $new_value = $this->_applyFieldFormat($new_value, $value['format'], $search, $outputToReplace);
                }

                if ( isset($value['callback']['function']) ) {
                    $new_value = $this->_applyFieldCallback($new_value, $value['callback'], $search, $outputToReplace);
                }

                if ( isset($value['helper']) ) {
                    $new_value = $this->_applyFieldHelper($new_value, $value['helper'], $search, $outputToReplace);
                }

                if ( isset($value['decorator']) ) {
                    $new_value = $this->_applyFieldDecorator($search, $outputToReplace, $value['decorator']);
                }

                $return[$i][] = array('class' => $value['class'], 'value' => $new_value, 'style' => $value['style']);

            }

            /**
             * Deal with the grid itself
             */
            $is = 0;
            foreach ( $fields as $campos ) {

                $new_value = $dados[$fields[$is]];

                $new_value = $this->_escapeField($fields[$is], $new_value);


                if ( isset($this->_data['fields'][$fields[$is]]['callback']['function']) ) {
                    $new_value = $this->_applyFieldCallback($new_value, $this->_data['fields'][$fields[$is]]['callback'], $search, $outputToReplace);
                    $outputToReplace[$fields[$is]] = $new_value;
                }


                if ( isset($this->_data['fields'][$fields[$is]]['format']) ) {
                    $new_value = $this->_applyFieldFormat($new_value, $this->_data['fields'][$fields[$is]]['format'], $search, $outputToReplace);
                    $outputToReplace[$fields[$is]] = $new_value;
                }


                if ( isset($this->_data['fields'][$fields[$is]]['helper']) ) {
                    $new_value = $this->_applyFieldHelper($new_value, $this->_data['fields'][$fields[$is]]['helper'], $search, $outputToReplace);
                    $outputToReplace[$fields[$is]] = $new_value;
                }


                if ( isset($this->_data['fields'][$fields[$is]]['decorator']) ) {
                    $new_value = $this->_applyFieldDecorator($search, $outputToReplace, $this->_data['fields'][$fields[$is]]['decorator']);
                }


                if ( $this->_displayField($fields[$is]) ) {

                    $style = ! isset($this->_data['fields'][$fields[$is]]['style']) ? '' : $this->_data['fields'][$fields[$is]]['style'];
                    $fieldClass = isset($this->_data['fields'][$fields[$is]]['class']) ? $this->_data['fields'][$fields[$is]]['class'] : '';
                    $finalClassConditional = isset($classConditional[$fields[$is]])?$classConditional[$fields[$is]]:'';



                    $return[$i][] = @array('class' => $fieldClass . ' ' . $finalClassConditional, 'value' => $new_value, 'field' => $this->_fields[$is], 'style' => $style);
                }

                $is ++;

            }

            /**
             * Deal with extra fields from the right
             */

            //Reset the value. This is an extra field.
            $new_value = null;
            foreach ( $this->_getExtraFields('right') as $value ) {

                $value['class'] = ! isset($value['class']) ? '' : $value['class'];
                $value['style'] = ! isset($value['style']) ? '' : $value['style'];

                if ( isset($value['callback']['function']) ) {
                    $new_value = $this->_applyFieldCallback($new_value, $value['callback'], $search, $outputToReplace);
                }

                if ( isset($value['format']) ) {
                    $new_value = $this->_applyFieldFormat($new_value, $value['format'], $search, $outputToReplace);
                }

                if ( isset($value['helper']) ) {
                    $new_value = $this->_applyFieldHelper($new_value, $value['helper'], $search, $outputToReplace);
                }

                if ( isset($value['decorator']) ) {
                    $new_value = $this->_applyFieldDecorator($search, $outputToReplace, $value['decorator']);
                }

                $return[$i][] = array('class' => $value['class'], 'value' => $new_value, 'style' => $value['style']);

            }
            $i ++;
        }

        return $return;
    }


    /**
     * Get the extra fields for a give position
     *
     * @param string $position
     * @return array
     */
    protected function _getExtraFields ($position = 'left')
    {

        if ( ! is_array($this->_extraFields) ) {
            return array();
        }

        $final = array();

        foreach ( $this->_extraFields as $value ) {
            if ( $value['position'] == $position ) {
                $final[] = $value;
            }
        }

        return $final;

    }


    /**
     *Reset keys
     * @param unknown_type $array
     * @return unknown
     */
    protected function _resetKeys (array $array)
    {

        $novo_array = array();
        $i = 0;
        foreach ( $array as $value ) {
            $novo_array[$i] = $value;
            $i ++;
        }
        return $novo_array;
    }


    /**
     * Apply SQL Functions
     *
     */
    protected function _buildSqlExp ()
    {

        $return = false;

        $final = $this->getInfo('sqlexp') ? $this->getInfo('sqlexp') : '';

        if ( ! is_array($final) ) {
            return false;
        }


        foreach ( $final as $key => $value ) {

            if ( ! array_key_exists($key, $this->_data['fields']) ) continue;


            if ( ! isset($value['value']) ) {
                $value['value'] = $key;
            }

            $resultExp = $this->getSource()->getSqlExp($value);

            if ( ! isset($value['format']) && isset($this->_data['fields'][$key]['format']) ) {
                $resultExp = $this->_applyFormat($resultExp, $this->_data['fields'][$key]['format']);
            } elseif ( isset($value['format']) && strlen(isset($value['format'])) > 2 && false !== $value['format'] ) {
                $resultExp = $this->_applyFormat($resultExp, $value['format']);
            }

            $result[$key] = $resultExp;

        }

        if ( isset($result) && is_array($result) ) {
            $return = array();
            foreach ( $this->_finalFields as $key => $value ) {
                if ( array_key_exists($key, $result) ) {
                    $class = $this->getInfo("sqlexp,$key,class") ? ' ' .$this->getInfo("sqlexp,$key,class") : '';
                    $return[] = array('class' => $class, 'value' => $result[$key], 'field' => $key);
                } else {
                    $class = $this->getInfo("sqlexp,$key,class") ? ' ' . $this->getInfo("sqlexp,$key,class") : '';
                    $return[] = array('class' => $class, 'value' => '', 'field' => $key);
                }
            }
        }
        return $return;
    }


    /**
     * Make sure the fields exists on the database, if not remove them from the array
     *
     * @param array $fields
     */
    protected function _validateFields (array $fields)
    {

        $hidden = array();
        $show = array();
        foreach ( $fields as $key => $value ) {
            //A parte da order



            if ( ! isset($value['order']) || $value['order'] == 1 ) {
                if ( isset($value['orderField']) ) {
                    $orderFields[$key] = $value['orderField'];
                } else {
                    $orderFields[$key] = $key;
                }
            }

            if ( isset($value['title']) ) {
                $titulos[$key] = $value['title'];
            } else {
                $titulos[$key] = ucwords(str_replace('_', ' ', $key));
            }

            if ( isset($this->_data['fields'][$key]['hidden']) && $this->_data['fields'][$key]['hidden'] == 1 ) {
                $hidden[$key] = $key;
            } else {
                $show[$key] = $key;
            }

        }

        $fields_final = array();
        $lastIndex = 1;
        $norder = 0;
        foreach ( $show as $key => $value ) {

            $value = $this->_data['fields'][$value];

            if ( isset($value['position']) && (! isset($value['hidden']) || $value['hidden'] == 0) ) {

                if ( $value['position'] == 'last' ) {
                    $fields_final[($lastIndex + 100)] = $key;
                } elseif ( $value['position'] == 'first' ) {
                    $fields_final[($lastIndex - 100)] = $key;
                } else {

                    if ( $value['position'] == 'next' ) {
                        $norder = $lastIndex + 1;
                    } else {
                        $norder = (int) $value['position'];
                    }

                    if ( array_key_exists($norder, $fields_final) ) {
                        for ( $i = count($fields_final); $i >= $norder; $i -- ) {
                            $fields_final[($i + 1)] = $fields_final[$i];
                        }
                        $fields_final[$norder] = $key;
                    }

                    $fields_final[$norder] = $key;
                }

            } elseif ( ! isset($value['hidden']) || $value['hidden'] == 0 ) {

                while (true) {
                    if ( array_key_exists($lastIndex, $fields_final) ) {
                        $lastIndex ++;
                    } else {
                        break;
                    }
                }
                $fields_final[$lastIndex] = $key;
            }
        }

        ksort($fields_final);

        $fields_final = $this->_resetKeys($fields_final);

        //Put the hidden fields on the end of the array
        foreach ( $hidden as $value ) {
            $fields_final[] = $value;
        }

        $this->_fields = $fields_final;
        $this->_titles = $titulos;
        $this->_fieldsOrder = $orderFields;
    }


    /**
     * Make sure the filters exists, they are the name from the table field.
     * If not, remove them from the array
     * If we get an empty array, we then creat a new one with all the fields specifieds
     * in $this->_fields method
     *
     * @param string $filters
     */
    protected function _validateFilters ()
    {

        if ( $this->getInfo("noFilters") == 1 ) {
            return false;
        }

        $filters = null;

        if ( is_array($this->_filters) ) {
            return $this->_filters;
        } else {
            $filters = array_combine($this->_fields, $this->_fields);
        }

        return $filters;
    }


    /**
     * Build user defined filters
     */
    protected function _buildDefaultFilters ()
    {

        if ( is_array($this->_defaultFilters) && ! $this->getParam('filters') && ! $this->getParam('noFilters') ) {
            $df = array();
            foreach ( $this->_data['fields'] as $key => $value ) {

                if ( ! $this->_displayField($key) ) {
                    continue;
                }

                if ( array_key_exists($key, array_flip($this->_defaultFilters)) ) {
                    $df['filter_' . $key] = array_search($key, $this->_defaultFilters);
                } else {
                    $df['filter_' . $key] = '';
                }
            }

            $defaultFilters = $df;

            $this->setParam('filters' . $this->getGridId(), Zend_Json_Encoder::encode($defaultFilters));
        }

        return $this;
    }


    /**
     * Done. Send the grid to the user
     *
     * @return string
     */
    public function deploy ()
    {

        // apply additional configuration
        $this->_runConfigCallbacks();

        if ( $this->getParam('gridDetail') == 1 && $this->_deployName == 'table' && (is_array($this->_detailColumns) || $this->getParam('gridRemove') )) {
            $this->_isDetail = true;
        }

        if ( $this->_isDetail === true && is_array($this->_detailColumns) ) {
            if ( count($this->_detailColumns) > 0 ) {

                $finalColumns = array_intersect($this->_detailColumns, array_keys($this->_data['fields']));

                foreach ( $this->_data['fields'] as $key => $value ) {
                    if ( ! in_array($key, $finalColumns) ) {
                        $this->updateColumn($key, array('remove' => 1));
                    }
                }
            }

        }


        if ( $this->_isDetail === false && is_array($this->_gridColumns) ) {
            $finalColumns = array_intersect($this->_gridColumns, array_keys($this->_data['fields']));
            foreach ( $this->_data['fields'] as $key => $value ) {
                if ( ! in_array($key, $finalColumns) ) {
                    $this->updateColumn($key, array('remove' => 1));
                }
            }

        }

        if ( $this->_isDetail == true ) {
            $result = $this->getSource()->fetchDetail($this->getPkFromUrl());
            if ( $result == false ) {
                 $this->_gridSession->message = $this->__('Record Not Found');
                 $this->_gridSession->_noForm = 1;
                 $this->_gridSession->correct = 1;
                $this->_redirect($this->getUrl(array('comm', 'gridDetail','gridRemove')));
            }
        }


        if ( count($this->getSource()->getSelectOrder()) > 0 && ! $this->getParam('order') ) {
            $norder = $this->getSource()->getSelectOrder();

            if ( ! $norder instanceof Zend_Db_Expr ) {
                $this->setParam('order' . $this->getGridId(), $norder[0] . '_' . strtoupper($norder[1]));
            }
        }


        $this->_buildDefaultFilters();

        // Validate table fields, make sure they exist...
        $this->_validateFields($this->_data['fields']);

        // Filters. Not required that every field as filter.
        $this->_filters = $this->_validateFilters($this->_filters);


        $this->_buildFiltersValues();

        if ( $this->_isDetail == false ) {
            $this->_buildQueryOrderAndLimit();
        }

        if ( $this->getParam('noOrder') == 1 ) {
            $this->getSource()->resetOrder();
        }

        $result = $this->getSource()->execute();

        if ( $this->_forceLimit === false ) {
            $resultCount = $this->getSource()->getTotalRecords();
        } else {
            $resultCount = $this->_forceLimit;
            if ( count($result) < $resultCount ) {
                $resultCount = count($result);
            }
        }


        //Total records found
        $this->_totalRecords = $resultCount;

        //The result
        $this->_result = $result;


        $this->_colspan();
        return $this;
    }


    /**
     * Get details about a column
     *
     * @param string $column
     * @return null|array
     */
    protected function _getColumn ($column)
    {

        return isset($this->_data['fields'][$column]) ? $this->_data['fields'][$column] : null;

    }


    /**
     *Convert Object to Array
     * @param object $object
     * @return array
     */
    protected function _object2array ($data)
    {

        if ( ! is_object($data) && ! is_array($data) ) return $data;

        if ( is_object($data) ) $data = get_object_vars($data);

        return array_map(array($this, '_object2array'), $data);

    }


    /**
     * set template locations
     *
     * @param string $path
     * @param string $prefix
     * @return unknown
     */
    public function addTemplateDir ($dir, $prefix, $type)
    {

        if ( ! isset($this->_templates[$type]) ) {
            $this->_templates[$type] = new Zend_Loader_PluginLoader();
        }

        $this->_templates[$type]->addPrefixPath(trim($prefix, "_"), trim($dir, "/") . '/', $type);
        return $this;
    }


    /**
     * Define the template to be used
     *
     * @param string $template
     * @return unknown
     */
    public function setTemplate ($template, $output = 'table', $options = array())
    {

        $tmp = $options;
        $options['userDefined'] = $tmp;


        $class = $this->_templates[$output]->load($template, $output);

        if ( isset($this->_options['template'][$output][$template]) ) {
            $tpOptions = array_merge($this->_options['template'][$output][$template], $options);
        } else {
            $tpOptions = $options;
        }


        $tpInfo = array('colspan' => $this->_colspan, 'charEncoding' => $this->getCharEncoding(), 'name' => $template, 'dir' => $this->_templates[$output]->getClassPath($template, $output), 'class' => $this->_templates[$output]->getClassName($template, $output));

        $this->_temp[$output] = new $class();

        $this->_temp[$output]->options = array_merge($tpInfo, $tpOptions);

        return $this->_temp[$output];

    }


    /**
     * Add multiple columns at once
     *
     */
    public function updateColumns ()
    {

        $fields = func_get_args();

        foreach ( $fields as $value ) {

            if ( $value instanceof Bvb_Grid_Column ) {

                $value = $this->_object2array($value);
                foreach ( $value as $field ) {

                    $finalField = $field['field'];
                    unset($field['field']);
                    $this->updateColumn($finalField, $field);

                }
            }
        }

        return;
    }


    /**
     * Calculate colspan for pagination and top
     *
     * @return int
     */
    protected function _colspan ()
    {

        $totalFields = count($this->_fields);

        foreach ( $this->_data['fields'] as $value ) {
            if ( isset($value['remove']) && $value['remove'] == 1 ) {
                $totalFields --;
            } elseif ( isset($value['hidden']) && $value['hidden'] == 1 && $this->_removeHiddenFields === true ) {
                $totalFields --;
            }

            if ( isset($value['hRow']) && $value['hRow'] == 1 ) {
                $totalFields --;
            }
        }

        if ( $this->getInfo("delete,allow") == 1 ) {
            $totalFields ++;
        }

        if ( $this->getInfo("edit,allow") == 1 ) {
            $totalFields ++;
        }

        if ( is_array($this->_detailColumns) && $this->_isDetail == false ) {
            $totalFields ++;
        }

        $colspan = $totalFields + count($this->_extraFields);

        $this->_colspan = $colspan;

        return $colspan;
    }


    /**
     * Returns a field and is options
     * @param $field
     */
    function getField ($field)
    {
        return $this->_data['fields'][$field];
    }


    /**
     *Return fields list.
     *Optional param returns also fields options
     * @param $returnOptions
     */
    function getFields ($returnOptions = false)
    {

        if ( false !== $returnOptions ) {
            return $this->_data['fields'];
        }

        return array_keys($this->_data['fields']);

    }


    /**
     * Add filters
     *
     */
    public function addFilters ($filters)
    {

        $filtersObj = $filters;

        $filters = $this->_object2array($filters);
        $filters = $filters['_filters'];

        foreach ( $filtersObj->_filters as $key => $value ) {
            if ( isset($filters[$key]['callback']) ) {
                $filters[$key]['callback'] = $value['callback'];
            }
            if ( isset($filters[$key]['transform']) ) {
                $filters[$key]['transform'] = $value['transform'];
            }
        }

        $this->_filters = $filters;

        foreach ( $filters as $key => $filter ) {
            if ( isset($filter['searchType']) ) {
                $this->updateColumn($key, array('searchType' => $filter['searchType']));
            }
        }

        $unspecifiedFields = array_diff($this->getFields(), array_keys($this->_filters));

        foreach ( $unspecifiedFields as $value ) {
            $this->updateColumn($value, array('search' => false));
        }

        return $this;
    }


    /**
     * Add extra columns
     *
     * @return unknown
     */
    public function addExtraColumns ()
    {

        $extra_fields = func_get_args();

        if ( is_array($this->_extraFields) ) {
            $final = $this->_extraFields;
        } else {
            $final = array();
        }

        foreach ( $extra_fields as $value ) {
            if ( $value instanceof Bvb_Grid_Extra_Column ) {
                $value = $this->_object2array($value);
                array_push($final, $value['_field']);
            }
        }
        $this->_extraFields = $final;
        return $this;
    }


    /**
     * Returns the grid version
     * @return string
     */
    public function getVersion ()
    {
        return self::VERSION;
    }


    /**
     * Return number records found
     */
    public function getTotalRecords ()
    {
        return (int) $this->_totalRecords;
    }


    /**
     * Automates export functionality
     *
     * @param array|array of array $classCallbacks key should be lowercase, functions to call once before deploy() and ajax() functions
     * @param array|boolean $requestData request parameters will bu used if FALSE
     */
    public static function factory ($defaultClass, $options = array(), $id = '', $classCallbacks = array(), $requestData = false)
    {

        if ( ! is_string($id) ) {
            $id = "";
        }

        if(strpos($defaultClass,'_')===false)
        {
            $defaultClass = 'Bvb_Grid_Deploy_'.ucfirst(strtolower($defaultClass));
        }

        if ( false === $requestData ) {
            $requestData = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        }

        if ( ! isset($requestData['_exportTo' . $id]) ) {

            // return instance of the main Bvb object, because this is not and export request
            $grid = new $defaultClass($options);
            $lClass = $defaultClass;
        } else {
            $lClass = strtolower($requestData['_exportTo' . $id]);
            // support translating of parameters specifig for the export initiator class
            if ( isset($requestData['_exportFrom']) ) {
                // TODO support translating of parameters specifig for the export initiator class
                $requestData = $requestData;
            }

            // now we need to find and load the right Bvb deploy class
            $className = "Bvb_Grid_Deploy_" . ucfirst($requestData['_exportTo' . $id]); // TODO support user defined classes



            if ( Zend_Version::compareVersion('1.8.0') == 1 ) {
                if ( Zend_Loader::autoload($className) ) {
                    $grid = new $className($options);
                } else {
                    $grid = new $defaultClass($options);
                    $lClass = $defaultClass;
                }
            } else {

                if ( Zend_Loader_Autoloader::autoload($className) ) {
                    $grid = new $className($options);
                } else {
                    $grid = new $defaultClass($options);
                    $lClass = $defaultClass;
                }
            }
        }

        // add the powerfull configuration callback function
        if ( isset($classCallbacks[$lClass]) ) {
            $grid->_configCallbacks = $classCallbacks[$lClass];
        }

        if ( is_string($id) ) {
            $grid->_setGridId($id);
        }

        return $grid;
    }


    /**
     *
     * @return
     */
    protected function _runConfigCallbacks ()
    {
        if ( ! is_array($this->_configCallbacks) ) {
            call_user_func($this->_configCallbacks, $this);
        } elseif ( count($this->_configCallbacks) == 0 ) {
            // no callback
            return;
        } elseif ( count($this->_configCallbacks) > 1 && is_array($this->_configCallbacks[0]) ) {
            die("multi");
            // TODO maybe fix
            // ordered list of callback functions defined
            foreach ( $this->_configCallbacks as $func ) {

            }
            break;
        } else {
            // only one callback function defined
            call_user_func($this->_configCallbacks, $this);
        }
        // run it only once
        $this->_configCallbacks = array();
    }


    /**
     * Build list of exports with options
     *
     * Options:
     * caption   - mandatory
     * img       - (default null)
     * cssClass   - (default ui-icon-extlink)
     * newWindow - (default true)
     * url       - (default actual url)
     * onClick   - (default null)
     * _class    - (reserved, used internaly)
     */
    public function getExports ()
    {
        $res = array();
        foreach ( $this->_export as $name => $defs ) {
            if ( ! is_array($defs) ) {
                // only export name is passed, we need to get default option
                $name = $defs;
                $className = "Bvb_Grid_Deploy_" . ucfirst($name); // TODO support user defined classes



                if ( Zend_Version::compareVersion('1.8.0') == 1 ) {
                    if ( Zend_Loader::autoload($className) && method_exists($className, 'getExportDefaults') ) {
                        // learn the defualt values
                        $defs = call_user_func(array($className, "getExportDefaults"));
                    } else {
                        // there are no defaults, we need at least some caption
                        $defs = array('caption' => $name);
                    }
                } else {
                    if ( Zend_Loader_Autoloader::autoload($className) && method_exists($className, 'getExportDefaults') ) {
                        // learn the defualt values
                        $defs = call_user_func(array($className, "getExportDefaults"));
                    } else {
                        // there are no defaults, we need at least some caption
                        $defs = array('caption' => $name);
                    }

                }
                $defs['_class'] = $className;

            }
            $res[$name] = $defs;
        }

        return $res;
    }


    /**
     * This is usefull if the deploy clas has no intention of using hidden fields
     * @param bool $value
     * @return $this
     */
    protected function _setRemoveHiddenFields ($value)
    {

        $this->_removeHiddenFields = (bool) $value;
        return $this;

    }


    /**
     *
     * @param $options
     */
    public function updateOptions ($options)
    {
        if ( $options instanceof Zend_Config ) {
            $options = $options->toArray();
        } else if ( ! is_array($options) ) {
            throw new Bvb_Grid_Exception('options must be an instance from Zend_Config or an array');
        }

        $this->_options = array_merge($this->options, $options);
        return $this;
    }


    /**
     *
     * @param $options
     */
    public function setOptions ($options)
    {
        $this->_options = array_merge($options, $this->_options);
        return $this;
    }


    /**
     * Apply the options to the fields
     */
    protected function _applyOptionsToFields ()
    {
        if ( isset($this->_options['fields']) && is_array($this->_options['fields']) ) {
            foreach ( $this->_options['fields'] as $field => $options ) {

                if ( isset($options['format']['function']) ) {
                    if ( ! isset($options['format']['params']) ) {
                        $options['format']['params'] = array();
                    }
                    $options['format'] = array($options['format']['function'], $options['format']['params']);
                }

                if ( isset($options['callback']) ) {

                    if ( ! isset($options['callback']['params']) ) {
                        $options['callback']['params'] = array();
                    }

                    if ( isset($options['callback']['function']) && isset($options['callback']['class']) ) {
                        $options['callback'] = array('function' => array($options['callback']['class'], $options['callback']['function']), 'params' => $options['callback']['params']);
                    } else {
                        $options['callback'] = array('function' => $options['callback']['function'], 'params' => $options['callback']['params']);
                    }

                }

                $this->updateColumn($field, $options);

            }
        }

        $deploy = explode('_', get_class($this));
        $name = strtolower(end($deploy));

        if ( isset($this->_options['deploy'][$name]) && is_array($this->_options['deploy'][$name]) ) {
            if ( method_exists($this, '_applyConfigOptions') ) {
                $this->_applyConfigOptions($this->_options['deploy'][$name]);
            } else {
                $this->deploy = $this->_options['deploy'][$name];
            }
        }

        if ( isset($this->_options['template'][$name]) && is_array($this->_options['template'][$name]) ) {
            $this->setTemplateParams($this->_options['template'][$name]);
        }

        if ( isset($this->_options['grid']['formatter']) ) {
            $this->_options['grid']['formatter'] = (array) $this->_options['grid']['formatter'];

            foreach ( $this->_options['grid']['formatter'] as $formatter ) {
                $temp = $formatter;
                $temp = str_replace('_', '/', $temp);
                $this->addFormatterDir($temp, $formatter);
            }

        }

    }


    /**
     * Sets the grid id, to allow multiples instances per page
     * @param $id
     */
    protected function _setGridId ($id)
    {
        $this->_gridId = $id;
        return $this;
    }


    /**
     * Returns the current id.
     * ""=>emty string is a valid value
     */
    public function getGridId ()
    {
        return $this->_gridId;
    }


    /**
     *Set user definied params for templates.
     * @param array $options
     * @return unknown
     */
    function setTemplateParams (array $options)
    {
        $this->_templateParams = $options;
        return $this;
    }


    /**
     * Seet user definied params for templates.
     * @param $name
     * @param $value
     */

    function addTemplateParam ($name, $value)
    {
        $this->_templateParams[$name] = $value;
        return $this;
    }


    /**
     * Adds user definied params for templates.
     * @param array $options
     * @return $this
     */
    function addTemplateParams (array $options)
    {

        $this->_templateParams = array_merge($this->_templateParams, $options);
        return $this;

    }


    /**
     * Returns template info defined by the user
     */
    function getTemplateParams ()
    {
        return $this->_templateParams;
    }


    /**
     * Reset otpions fo column
     * @param string $column
     * @return self
     */
    function resetColumn ($column)
    {
        $support = array();
        $support[] = $this->_data['fields']['title'];
        $support[] = $this->_data['fields']['field'];
        $this->updateColumn($column, $support);
        return $this;
    }


    /**
     * Reset options for several columns
     * @param $columns
     */
    function resetColumns (array $columns)
    {
        foreach ( $columns as $column ) {
            $support = array();
            $support[] = $this->_data['fields']['title'];
            $support[] = $this->_data['fields']['field'];
            $this->updateColumn($column, $support);
        }

        return $this;
    }


    /**
     * Some debug info
     */
    function debug ($returnSerialized = false)
    {
        $result = array();
        $result['fields'] = $this->getFields(true);
        $result['colspan'] = $this->_colspan();
        $result['filters'] = $this->_filters;
        $result['filtersValues'] = $this->_filtersValues;
        $result['mainSelect'] = $this->getSource()->getSelectObject()->__toString();
        $result['form'] = isset($this->_form) ? $this->_form : null;

        if ( $returnSerialized === true ) {
            return serialize($result);
        }

        return $result;
    }


    function setGridColumns (array $columns)
    {
        $this->_gridColumns = $columns;
        return $this;
    }


    function addGridColumns (array $columns)
    {
        $this->_gridColumns = array_merge($this->_gridColumns, $columns);
        return $this;
    }


    function setDetailColumns ($columns = array())
    {
        $this->_detailColumns = $columns;
        return $this;
    }


    function addDetailColumns (array $columns)
    {
        $this->_detailColumns = array_merge($this->_detailColumns, $columns);
        return $this;
    }


    /**
     * Get the list of primary keys from the URL
     *
     * @return string
     */
    function getPkFromUrl ()
    {
        if ( ! $this->getParam('comm') ) {
            return array();
        }

        $param = $this->getParam('comm');
        $explode = explode(';', $param);
        $param = end($explode);
        $param = substr($param, 1, - 1);

        $paramF = explode('-', $param);
        $param = '';

        $returnArray = array();
        foreach ( $paramF as $value ) {
            $f = explode(':', $value);
            $returnArray[$f[0]] = $f[1];
        }
        return $returnArray;
    }


    /**
     * Let the user know waht will be displayed.
     * @param $option (grid|form)
     * @return array|bool
     */
    public function willShow ()
    {
        return $this->_willShow;
    }


    /**
     * Get a param from the $this->_ctrlParams appending the grid id
     * @param $param
     * @param $default
     */
    function getParam ($param, $default = false)
    {
        return isset($this->_ctrlParams[$param . $this->getGridId()]) ? $this->_ctrlParams[$param . $this->getGridId()] : $default;
    }


    function getAllParams ()
    {
        return $this->_ctrlParams;
    }


    protected function _redirect ($url, $code = 302)
    {
        $response = Zend_Controller_Front::getInstance()->getResponse();
        $response->setRedirect($url, $code);
        $response->sendResponse();
        die();
    }


    /**
     * Set a param to be used by controller.
     *
     * @param $param
     * @param $value
     */
    function setParam ($param, $value)
    {
        $this->_ctrlParams[$param] = $value;
        return $this;
    }


    /**
     * Remove a param
     * @param $param
     */
    function removeParam ($param)
    {
        unset($this->_ctrlParams[$param]);
        return $this;
    }


    function removeAllParams ()
    {
        $this->_ctrlParams = array();
        return $this;
    }


    function setParams (array $params)
    {
        $this->_ctrlParams = $params;
        return $this;
    }


    function setExport (array $export)
    {
        $this->_export = $export;
        return $this;
    }


    function getExport ()
    {
        return $this->_export;
    }


    function setSqlExp (array $exp)
    {

        $this->_info['sqlexp'] = $exp;

        return $this;
    }


}