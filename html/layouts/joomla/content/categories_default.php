<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

?>
<?php if ($displayData->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $displayData->escape($displayData->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<div class="hero">
	<div class="item-img-fullwidth" style="background-image: url('<?php echo htmlspecialchars($displayData->parent->getParams()->get('image')); ?>');">
		<div class="overlay"></div>

		<div class="container d-flex flex-column">
			<div class="page-header">
				<h1 itemprop="headline">
					<?php // If there is a description in the menu parameters use that; ?>
					<?php echo JHtml::_('content.prepare', $displayData->params->get('categories_description'), '',  $displayData->get('extension') . '.categories'); ?>
				</h1>
			</div>

			<div class="d-none d-md-block">
				<?php echo JHtml::_('content.prepare', '{loadposition hero-menu}'); ?>
			</div>
		</div>
	</div>
</div>
<?php if ($displayData->params->get('show_base_description')) : ?>
	<?php // Otherwise get one from the database if it exists. ?>
	<?php if ($displayData->parent->description) : ?>
		<div class="category-desc base-desc">
			<div class="container">
				<?php echo JHtml::_('content.prepare', $displayData->parent->description, '', $displayData->parent->extension . '.categories'); ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
