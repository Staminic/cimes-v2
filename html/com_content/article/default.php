<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (JLanguageAssociations::isEnabled() && $params->get('show_associations'));
JHtml::_('behavior.caption');

// Enable direct access by name to custom fields
foreach($this->item->jcfields as $jcfield)
 {
  $this->item->jcFields[$jcfield->name] = $jcfield;
 }

?>
<div class="item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />

	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>

	<?php
		if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
		{
			echo $this->item->pagination;
		}
	?>

	<?php // Todo Not that elegant would be nice to group the params ?>
	<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam); ?>

	<?php if (!$useDefList && $this->print) : ?>
		<div id="pop-print" class="btn hidden-print">
			<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
		</div>
		<div class="clearfix"> </div>
	<?php endif; ?>

	<?php if ($params->get('access-view')) : ?>

	<div class="hero">
		<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
			<div class="item-img-fullwidth" style="background-image: url('<?php echo htmlspecialchars($images->image_fulltext); ?>');">
        <?php echo JHtml::_('content.prepare', '{loadposition hero-video}'); ?>
        <div class="overlay"></div>

				<div class="container d-flex flex-column">

					<?php if(($this->item->jcFields['hero-title']->value !="") || ($this->item->jcFields['hero-text']->value !="")) : ?>
						<div class="hero-slogan">
							<?php if ($this->item->jcFields['hero-title']->value !="") : ?>
									<p class="h1">
										<?php echo $this->item->jcFields['hero-title']->value; ?>
									</p>
							<?php endif; ?>
							<?php if ($this->item->jcFields['hero-text']->value !="") : ?>
									<p class="h2">
										<?php echo $this->item->jcFields['hero-text']->value; ?>
									</p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
						<div class="page-header ">
							<?php if ($params->get('show_title')) : ?>
                <?php if (!($useDefList && ($info == 0 || $info == 2))) : ?>
  								<?php if ($this->params->get('show_page_heading')) : ?>
  									<h2 itemprop="headline">
  										<?php echo $this->escape($this->item->title); ?>
  									</h2>
  								<?php else : ?>
  									<h1 itemprop="headline">
  										<?php echo $this->escape($this->item->title); ?>
  									</h1>
  								<?php endif; ?>
                <?php endif; ?>
							<?php endif; ?>

							<?php if ($this->item->state == 0) : ?>
								<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
							<?php endif; ?>
							<?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
								<span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
							<?php endif; ?>
							<?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate()) : ?>
								<span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
							<?php endif; ?>

              <?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
                <?php // Todo: for Joomla4 joomla.content.info_block.block can be changed to joomla.content.info_block ?>
                <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
              <?php endif; ?>

						</div>

						<?php echo JHtml::_('content.prepare', '{loadposition hero-menu}'); ?>

						{module Fil de navigation}
					<?php endif; ?>
				</div>

			</div>
		<?php endif; ?>
	</div>

  <div class="container">

  	<?php if (!$this->print) : ?>
  		<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
  			<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
  		<?php endif; ?>
  	<?php else : ?>
  		<?php if ($useDefList) : ?>
  			<div id="pop-print" class="btn hidden-print">
  				<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
  			</div>
  		<?php endif; ?>
  	<?php endif; ?>

  	<?php // Content is generated by content plugin event "onContentAfterTitle" ?>
  	<?php echo $this->item->event->afterDisplayTitle; ?>

  	<?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
  		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>

  		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
  	<?php endif; ?>

  	<?php // Content is generated by content plugin event "onContentBeforeDisplay" ?>
  	<?php echo $this->item->event->beforeDisplayContent; ?>

  	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
  		|| (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
  	<?php echo $this->loadTemplate('links'); ?>
  	<?php endif; ?>

  	<?php
  	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative) :
  		echo $this->item->pagination;
  	endif;
  	?>

  	<?php if (isset ($this->item->toc)) :
  		echo $this->item->toc;
  	endif; ?>

    <?php if ($params->get('show_title') || $params->get('show_author')) : ?>

      <?php if ($params->get('show_title')) : ?>
        <?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
          <?php if ($this->params->get('show_page_heading')) : ?>
            <h2 itemprop="headline">
              <?php echo $this->escape($this->item->title); ?>
            </h2>
          <?php else : ?>
            <h1 itemprop="headline">
              <?php echo $this->escape($this->item->title); ?>
            </h1>
          <?php endif; ?>
        <?php endif; ?>
      <?php endif; ?>

      <?php if ($this->item->state == 0) : ?>
        <span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
      <?php endif; ?>
      <?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
        <span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
      <?php endif; ?>
      <?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate()) : ?>
        <span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
      <?php endif; ?>

    <?php endif; ?>

    <div itemprop="articleBody">
  		<?php echo $this->item->text; ?>
  	</div>


  	<?php if ($info == 1 || $info == 2) : ?>
  		<?php if ($useDefList) : ?>
  				<?php // Todo: for Joomla4 joomla.content.info_block.block can be changed to joomla.content.info_block ?>
  			<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
  		<?php endif; ?>
  		<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
  			<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
  			<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
  		<?php endif; ?>
  	<?php endif; ?>

  	<?php
  	if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative) :
  		echo $this->item->pagination;
  	?>
  	<?php endif; ?>
  	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
  	<?php echo $this->loadTemplate('links'); ?>
  	<?php endif; ?>
  	<?php // Optional teaser intro text for guests ?>
  	<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
  	<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>
  	<?php echo JHtml::_('content.prepare', $this->item->introtext); ?>
  	<?php // Optional link to let them register to see the whole article. ?>
  	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
  	<?php $menu = JFactory::getApplication()->getMenu(); ?>
  	<?php $active = $menu->getActive(); ?>
  	<?php $itemId = $active->id; ?>
  	<?php $link = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
  	<?php $link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language))); ?>
  	<p class="readmore">
  		<a href="<?php echo $link; ?>" class="register">
  		<?php $attribs = json_decode($this->item->attribs); ?>
  		<?php
  		if ($attribs->alternative_readmore == null) :
  			echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
  		elseif ($readmore = $attribs->alternative_readmore) :
  			echo $readmore;
  			if ($params->get('show_readmore_title', 0) != 0) :
  				echo JHtml::_('string.truncate', $this->item->title, $params->get('readmore_limit'));
  			endif;
  		elseif ($params->get('show_readmore_title', 0) == 0) :
  			echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
  		else :
  			echo JText::_('COM_CONTENT_READ_MORE');
  			echo JHtml::_('string.truncate', $this->item->title, $params->get('readmore_limit'));
  		endif; ?>
  		</a>
  	</p>
  	<?php endif; ?>
  	<?php endif; ?>
  	<?php
  	if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
  		echo $this->item->pagination;
  	?>
  	<?php endif; ?>
  	<?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
  	<?php echo $this->item->event->afterDisplayContent; ?>

    <?php
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$active = $app->getMenu()->getActive();
		$parentitemid = $active->parent_id;
    $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
    ?>
		<?php if($parentitemid == 138) : ?>
			<a class="btn btn-outline-black" href="<?php echo $url; ?>">Retour</a>
		<?php endif; ?>

  </div>
</div>
