<?php
/**
 * @package            Joomla
 * @subpackage         Event Booking
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2020 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Layout variables
 * -----------------
 * @var   EventbookingTableEvent $item
 * @var   RADConfig              $config
 * @var   boolean                $showLocation
 * @var   stdClass               $location
 * @var   boolean                $isMultipleDate
 * @var   string                 $nullDate
 * @var   int                    $Itemid
 */

$bootstrapHelper = EventbookingHelperBootstrap::getInstance();

$dateFormat = $config->event_date_format;
$timeFormat = $config->event_time_format;
?>

<div class="card-body">
	<?php // Category
		$categories = [];
		foreach ($item->categories as $category)
		{
			$categories[] = $category->name;
		}
		// echo '<h4 class="h3">' . implode(',' , $categories) . '</h4>';
		echo '<h4 class="eb-event-category card-event-catid-' . $category->id . '">' . '<span>' . $category->name . '</span>' . '</h4>';
	?>

	<div class="eb-event-date-time">
		<?php // date
		if ($item->event_date == EB_TBC_DATE)
		{
			echo JText::_('EB_TBC');
		}
		else
		{
			$startDate =  JHtml::_('date', $item->event_date, 'Y-m-d', null);
			$endDate   = JHtml::_('date', $item->event_end_date, 'Y-m-d', null);
			$startDateMounth =  JHtml::_('date', $item->event_date, 'm', null);
			$endDateMounth   = JHtml::_('date', $item->event_end_date, 'm', null);
			$startDateYear =  JHtml::_('date', $item->event_date, 'Y', null);
			$endDateYear   = JHtml::_('date', $item->event_end_date, 'Y', null);

			if ($startDate == $endDate) {
				echo '<span class="eb-date h1">' . JHtml::_('date', $item->event_date, 'd F Y', null) . '</span>';
				echo '<span class="eb-time h3">' . JHtml::_('date', $item->event_date, $timeFormat, null) . ' - ' . JHtml::_('date', $item->event_end_date, $timeFormat, null);
			}
			else {
				if ($startDateYear == $endDateYear) {
					if ($startDateMounth == $endDateMounth) {
						echo '<span class="eb-date h1">' . JHtml::_('date', $item->event_date, 'd', null) . ' - ' . JHtml::_('date', $item->event_end_date, 'd', null) . ' ' . JHtml::_('date', $item->event_date, 'F Y', null) . '</span>';
					} else {
						echo '<span class="eb-date h1">' . JHtml::_('date', $item->event_date, 'd F', null) . ' - ' . JHtml::_('date', $item->event_end_date, 'd F', null) . ' ' . JHtml::_('date', $item->event_date, 'Y', null) . '</span>';
					}
				} else {
					echo '<span class="eb-date h1">' . JHtml::_('date', $item->event_date, 'd F Y', null) . '</span>';
					echo '<span class="eb-date h1">' . JHtml::_('date', $item->event_end_date, 'd F Y', null) . '</span>';
				}
				echo '<span class="eb-time h3">' . JHtml::_('date', $item->event_date, $timeFormat, null) . ' - ' . JHtml::_('date', $item->event_end_date, $timeFormat, null);
			}
		}


		?>
	</div>

	<div class="eb-event-price">
		<?php
		if ($item->individual_price > 0)
		{
			echo '<span class="h3">' . EventbookingHelper::formatCurrency($item->individual_price, $config, $item->currency_symbol) . '</span>';
		}
		else
		{
			if ($item->price_text) {
				echo '<span class="h3">' . $item->price_text . '</span>';
			} else {
				echo '<span class="eb_free h3">' . JText::_('EB_FREE') . '</span>';
			}
		}
		?>
	</div>

	<div class="eb-event-action">
		<?php if (!$item->can_register) : ?>
			<p>Inscription fermée pour le moment</p>
		<?php else : ?>
			<a href="#eb-individual-registration-page" class="btn btn-lg">Inscrivez-vous</a>
		<?php endif ; ?>
	</div>
</div>
