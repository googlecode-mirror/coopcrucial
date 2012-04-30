<?php
/**
 * Smarty Rendering Driver
 * 
 * PHP versions 4 and 5
 *
 * LICENSE:
 * 
 * Copyright (c) 1997-2006, Andrew Nagy <asnagy@webitecture.org>,
 *                          Olivier Guilyardi <olivier@samalyse.com>,
 *                          Mark Wiesemann <wiesemann@php.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the 
 *      documentation and/or other materials provided with the distribution.
 *    * The names of the authors may not be used to endorse or promote products 
 *      derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * CSV file id: $Id: Smarty.php,v 1.39 2006/12/15 16:07:48 wiesemann Exp $
 * 
 * @version  $Revision: 1.39 $
 * @package  Structures_DataGrid_Renderer_Smarty
 * @category Structures
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 */

require_once 'Structures/DataGrid/Renderer.php';

/**
 * Smarty Rendering Driver
 *
 * SUPPORTED OPTIONS:
 * 
 * - selfPath:            (string) The complete path for sorting and paging links.
 *                                 (default: $_SERVER['PHP_SELF'])
 * - sortingResetsPaging: (bool)   Whether sorting HTTP queries reset paging.  
 * - convertEntities:     (bool)   Whether or not to convert html entities.
 *                                 This calls htmlspecialchars(). 
 *
 * SUPPORTED OPERATION MODES:
 *
 * - Container Support: yes
 * - Output Buffering:  no
 * - Direct Rendering:  no
 * - Streaming:         no
 *
 * GENERAL NOTES:
 *
 * This driver does not support the render() method, it only is able to "fill"
 * a Smarty object, by calling Smarty::assign() and Smarty::register_function().
 *
 * It's up to you to called Smarty::display() after the Smarty object has been
 * filled.
 *
 * This driver assigns the following Smarty variables: 
 * - $columnSet:       array of columns specifications
 *                     structure: 
 *                          array ( 
 *                              0 => array (
 *                                  'name'       => field name,
 *                                  'label'      => column label,
 *                                  'link'       => sorting link,
 *                                  'attributes' => attributes string,
 *                              ),
 *                              ... 
 *                          )
 * - $recordSet:       array of records values
 * - $currentPage:     current page (starting from 1)
 * - $recordLimit:     number of rows per page
 * - $pagesNum:        number of pages
 * - $columnsNum:      number of columns
 * - $recordsNum:      number of records in the current page
 * - $totalRecordsNum: total number of records
 * - $firstRecord:     first record number (starting from 1)
 * - $lastRecord:      last record number (starting from 1)
 * - $currentSort:     array with column names and the directions used for sorting
 * 
 * This driver also registers a Smarty custom function named getPaging
 * that can be called from Smarty templates with {getPaging} in order
 * to print paging links. This function accepts any of the Pager::factory()
 * options as parameters.
 *
 * Template example, featuring sorting and paging:
 * 
 * <code>
 * <!-- Show paging links using the custom getPaging function -->
 * {getPaging prevImg="<<" nextImg=">>" separator=" | " delta="5"}
 * 
 * <p>Showing records {$firstRecord} to {$lastRecord} 
 * from {$totalRecordsNum}, page {$currentPage} of {$pagesNum}</p>
 * 
 * <table cellspacing="0">
 *     <!-- Build header -->
 *     <tr>
 *         {section name=col loop=$columnSet}
 *             <th {$columnSet[col].attributes}>
 *                 <!-- Check if the column is sortable -->
 *                 {if $columnSet[col].link != ""}
 *                     <a href="{$columnSet[col].link}">{$columnSet[col].label}</a>
 *                 {else}
 *                     {$columnSet[col].label}
 *                 {/if}
 *             </th>
 *         {/section}
 *     </tr>
 *     
 *     <!-- Build body -->
 *     {section name=row loop=$recordSet}
 *         <tr {if $smarty.section.row.iteration is even}bgcolor="#EEEEEE"{/if}>
 *             {section name=col loop=$recordSet[row]}
 *                 <td {$columnSet[col].attributes}>{$recordSet[row][col]}</td>
 *             {/section}
 *         </tr>
 *     {/section}
 * </table>
 * </code>
 * 
 * This template can be used with code similar to this prototype:
 *
 * <code>
 * $smarty = new Smarty(...);
 * $datagrid =& new Structures_DataGrid(...);
 * $datagrid->bind(...);
 * $datagrid->fill($smarty);
 * $smarty->display(PATH TO YOUR TEMPLATE);
 * </code>
 * 
 * @version  $Revision: 1.39 $
 * @author   Andrew S. Nagy <asnagy@webitecture.org>
 * @author   Olivier Guilyardi <olivier@samalyse.com>
 * @access   public
 * @package  Structures_DataGrid_Renderer_Smarty
 * @category Structures
 */
class Structures_DataGrid_Renderer_Smarty extends Structures_DataGrid_Renderer
{
    /**
     * Smarty container
     * @var object $_smarty;
     */
    var $_smarty;
    
    /**
     * Constructor
     *
     * @access  public
     */
    function Structures_DataGrid_Renderer_Smarty()
    {
        parent::Structures_DataGrid_Renderer();
        $this->_addDefaultOptions(
            array(
                'selfPath'            => htmlspecialchars($_SERVER['PHP_SELF']),
                'convertEntities'     => true,
                'sortingResetsPaging' => true,
            )
        );
    }

    /**
     * Attach an already instantiated Smarty object
     * 
     * @param  object $smarty Smarty container
     * @return mixed True or PEAR_Error
     */
    function setContainer(&$smarty)
    {
        $this->_smarty =& $smarty;
        return true;
    }
    
    /**
     * Return the currently used Smarty object
     *
     * @return object Smarty or PEAR_Error object
     */
    function &getContainer()
    {
        if (!isset($this->_smarty)) {
            $id = __CLASS__ . '::' . __FUNCTION__;
            return PEAR::raiseError("$id: no Smarty container loaded");
        }
        return $this->_smarty;
    }
    
    /**
     * Initialize the Smarty container
     * 
     * @access protected
     */
    function init()
    {
        if (!isset($this->_smarty)) {
            $id = __CLASS__ . '::' . __FUNCTION__;
            return PEAR::raiseError("$id: no Smarty container loaded");
        }
        $this->_smarty->assign('currentPage', $this->_page);
        $this->_smarty->assign('recordLimit', $this->_pageLimit);
        $this->_smarty->assign('columnsNum', $this->_columnsNum);
        $this->_smarty->assign('recordsNum', $this->_recordsNum);
        $this->_smarty->assign('totalRecordsNum', $this->_totalRecordsNum);
        $this->_smarty->assign('pagesNum', $this->_pagesNum);
        $this->_smarty->assign('firstRecord', $this->_firstRecord);
        $this->_smarty->assign('lastRecord', $this->_lastRecord);
        $this->_smarty->assign('currentSort', $this->_currentSort);

        $this->_smarty->register_function('getPaging',
                                          array(&$this, '_smartyGetPaging'));
    }

    /**
     * Attach a Smarty instance
     * 
     * @deprecated Use setContainer() instead
     * @param object Smarty instance
     * @access public
     */
    function setSmarty(&$smarty)
    {
        return $this->setContainer($smarty);
    }


    function buildHeader(&$columns)
    {
        $prepared = array();
        foreach ($columns as $index => $spec) {
            if (in_array($spec['field'], $this->_sortableFields)) {
                reset($this->_currentSort);
                if ((list($currentField, $currentDirection) = each($this->_currentSort))
                    && isset($currentField)
                    && $currentField == $spec['field']
                   ) {
                    if ($currentDirection == 'ASC') {
                        $direction = 'DESC';
                    } else {
                        $direction = 'ASC';
                    }
                } else {
                    $direction = $this->_defaultDirections[$spec['field']];
                }
                $extra = array('page' => $this->_options['sortingResetsPaging'] 
                                         ? 1 : $this->_page);
                $query = $this->_buildSortingHttpQuery($spec['field'], 
                                                       $direction, true, $extra);
                $prepared[$index]['link'] = "{$this->_options['selfPath']}?$query";
            } else {
                $query = '';
                $prepared[$index]['link'] = "";
            }
            $prepared[$index]['name']   = $spec['field'];
            $prepared[$index]['label']  = $spec['label'];

            $prepared[$index]['attributes'] = "";
            if (isset($this->_options['columnAttributes'][$spec['field']])) {
                foreach ($this->_options['columnAttributes'][$spec['field']] 
                            as $name => $value) {
                    $value = htmlspecialchars($value, ENT_COMPAT, 
                                              $this->_options['encoding']);
                    $prepared[$index]['attributes'] .= "$name=\"$value\" "; 
                }
            }
        }

        $this->_smarty->assign('columnSet', $prepared);
    }
    
    /**
     * Handles building the body of the table
     *
     * @access  protected
     * @return  void
     */
    function buildBody()
    {
        $this->_smarty->assign('recordSet', $this->_records);
    }

    /**
     * Gets the Smarty object
     *
     * @deprecated Use getContainer() instead 
     * @access  public
     * @return  object Smarty container (reference)
     */
    function &getSmarty()
    {
        return $this->getContainer();
    }


    /**
     * Discard the unsupported render() method
     * 
     * This Smarty driver does not support the render() method.
     * It is required to use the setContainer() (or 
     * Structures_DataGrid::fill()) method in order to do anything
     * with this driver.
     * 
     */
    function render()
    {
        return $this->_noSupport(__FUNCTION__);
    }

    /**
     * Smarty custom function "getPaging"
     *
     * This is only meant to be called from a smarty template, using the
     * expression: {getPaging <options>}
     *
     * <options> are any Pager::factory() options
     *
     * @param array  $params Options passed from the Smarty template
     * @param object $smarty Smarty object
     * @return string Paging HTML links
     * @access private
     */
    function _smartyGetPaging($params, &$smarty)
    {
        // Load and get output from the Pager rendering driver
        $driver =& Structures_DataGrid::loadDriver('Structures_DataGrid_Renderer_Pager');

        // Propagate the selfPath option. Do not override user params
        if (!isset($params['path']) && !isset($params['filename'])) {
            $params['path'] = dirname($this->_options['selfPath']);
            $params['fileName'] = basename($this->_options['selfPath']);
            $params['fixFileName'] = false;
        }

        $driver->setupAs($this, $params);
        $driver->build(array(), 0);
        return $driver->getOutput();
    }

    /**
     * Default formatter for all cells
     * 
     * @param string  Cell value 
     * @return string Formatted cell value
     * @access protected
     */
    function defaultCellFormatter($value)
    {
        return $this->_options['convertEntities']
               ? htmlspecialchars($value, ENT_COMPAT, $this->_options['encoding'])
               : $value;
    }
}

/* vim: set expandtab tabstop=4 shiftwidth=4: */
?>
