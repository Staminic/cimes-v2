<?php
/**
 * @package        	Joomla
 * @subpackage		Event Booking
 * @author  		Tuan Pham Ngoc
 * @copyright    	Copyright (C) 2010 - 2020 Ossolution Team
 * @license        	GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die;

?>
<div id="eb-registration-complete-page" class="eb-container">
	<div class="hero">
			<div class="item-img-fullwidth" style="background-image: url('../images/ville.jpg');">
				<div class="overlay"></div>

				<div class="container d-flex flex-column">
					<div class="page-header ">
						<h1 itemprop="headline">Webinaires et formations</h1>
					</div>
				</div>
			</div>
	</div>

	<div class="container">
		<h2 class="h3 text-primary"><?php echo JText::_('EB_REGISTRATION_COMPLETE'); ?>
			<?php
			if ($this->showPrintButton === '1' &&  !$this->print)
			{
				$uri = JUri::getInstance();
				$uri->setVar('tmpl', 'component');
				$uri->setVar('print', '1');
			?>
				<div id="pop-print" class="btn hidden-print">
					<a href="<?php echo $uri->toString(); ?>" target="_blank" title="<?php echo JText::_('EB_PRINT_THIS_PAGE'); ?>" rel="nofollow">
						<span class="<?php echo $this->bootstrapHelper->getClassMapping('icon-print'); ?>"></span>
					</a>
				</div>
			<?php
			}
			?>
		</h2>
		<div id="eb-message" class="eb-message"><?php echo JHtml::_('content.prepare', $this->message); ?></div>
	</div>
</div>

<?php
	if ($this->print)
	{
	?>
		<script type="text/javascript">
			window.print();
		</script>
	<?php
	}
	echo $this->conversionTrackingCode;
?>
