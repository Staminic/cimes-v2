<?php
defined('_JEXEC') or die;

if (!key_exists('field', $displayData))
{
	return;
}

$field = $displayData['field'];
$label = JText::_($field->label);
$value = $field->value;
$showLabel = $field->params->get('showlabel');
$labelClass = $field->params->get('label_render_class');

if ($value == '') 
{
  return;
}
?>

<div class="reference-backlink">
  <h3 class="text-primary mt-5"><?php echo JText::_('ARTICLE_REFERENCE_BACKLINK'); ?></h3>
    <ul class="list-unstyled"><?php echo $value; ?></ul>
</div>
