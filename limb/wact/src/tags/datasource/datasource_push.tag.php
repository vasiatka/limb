<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id$
 * @package    wact
 */
require_once('limb/wact/src/tags/fetch/WactBaseFetchingTag.class.php');

/**
* @tag datasource:push
* @req_const_attributes to
* @req_attributes from
* @forbid_end_tag
*/
class WactDatasourcePushTag extends WactCompilerTag
{
  function preParse()
  {
    if(!$this->hasAttribute('target') && !$this->hasAttribute('to'))
      $this->raiseCompilerError('Required attribute not found',
                                array('attribute' => 'target or to'));

    if($this->hasAttribute('target') && $this->hasAttribute('to'))
      $this->raiseCompilerError('Both target and to attribute are not supported');

    $this->_fillToAttributeFromTargetAttribute();

    return parent :: preParse();
  }

  function generateBeforeContent($code_writer)
  {
    foreach($this->_getExpressionsInTargetAttribute() as $expression)
    {
      $dbe = new WactDataBindingExpressionNode($expression, $this, $this->parent);
      $datasource = $dbe->getDatasourceContext();
      $field_name = $dbe->getFieldName();

      if($field_name && !$datasource->isDatasource())
        $this->raiseCompilerError('Wrong datasource path in target or to attribute',
                                  array('expression' => $expression));

      if(count($dbe->getPathToTargetDatasource()))
      {
        $this->raiseCompilerError('Path based variable is not supported in target or to attribute',
                                  array('expression' => $expression));
      }

      $code_writer->writePHP($datasource->getComponentRefCode() . '->registerDatasource(');
      $this->attributeNodes['from']->generateExpression($code_writer);
      $code_writer->writePHP(');' . "\n");
    }
  }

  protected function _fillToAttributeFromTargetAttribute()
  {
    if(!$this->hasAttribute('target'))
      return;

    $pieces = explode(',', $this->getAttribute('target'));

    $to = array();
    foreach($pieces as $piece)
     $to[] = '[' . $piece . ']';

    $this->setAttribute('to', implode(',', $to));
  }

  protected function _getExpressionsInTargetAttribute()
  {
    $result = array();

    $pieces = explode(',', $this->getAttribute('to'));

    foreach($pieces as $piece)
     $result[] = trim($piece);

    return $result;
  }
}

?>