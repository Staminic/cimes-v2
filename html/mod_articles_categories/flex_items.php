<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_categories
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$class="card";

?>

<?php foreach ($list as $item) : ?>
	<a  href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id)); ?>" <?php if ($_SERVER['REQUEST_URI'] === JRoute::_(ContentHelperRoute::getCategoryRoute($item->id))) $class.="active"; ?> class="<?php echo $class; ?>">

		<img src="<?php echo pathinfo($item->getParams()->get('image'))['dirname'] . '/' . pathinfo($item->getParams()->get('image'))['filename'] . '-thumb.jpg'; ?>" alt="<?php echo $item->getParams()->get('image_alt'); ?>" class="img-fluid" />

		<div class="card-img-overlay">
			<?php $levelup = $item->level - $startLevel - 1; ?>
			<h<?php echo $params->get('item_heading') + $levelup; ?> class="card-title">
				<?php echo $item->title; ?>
					<?php if ($params->get('numitems')) : ?>
						(<?php echo $item->numitems; ?>)
					<?php endif; ?>
	   	</h<?php echo $params->get('item_heading') + $levelup; ?>>

			<?php if ($params->get('show_description', 0)) : ?>
				<div class="card-text">
					<?php echo JHtml::_('content.prepare', $item->description, $item->getParams(), 'mod_articles_categories.content'); ?>
				</div>
			<?php endif; ?>
			<?php if ($params->get('show_children', 0) && (($params->get('maxlevel', 0) == 0)
				|| ($params->get('maxlevel') >= ($item->level - $startLevel)))
				&& count($item->getChildren())) : ?>
				<?php echo '<ul>'; ?>
				<?php $temp = $list; ?>
				<?php $list = $item->getChildren(); ?>
				<?php require JModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default') . '_items'); ?>
				<?php $list = $temp; ?>
				<?php echo '</ul>'; ?>
			<?php endif; ?>

		</div>

	</a>
<?php endforeach; ?>