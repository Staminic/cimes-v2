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

<div class="related-references">
  <h2 class="line-through text-primary">
    <span><?php echo JText::_('ARTICLE_FIELDS_REFERENCES'); ?></span>
  </h2>
  <div class="related-references-list row">
    <div class="col-md-6 col-lg-4 d-flex">
      <div class="item column-1 card-event-catid-2 w-100 mb-4">
        <?php echo $value; ?>
      </div>
    </div>
  </div>
</div>
