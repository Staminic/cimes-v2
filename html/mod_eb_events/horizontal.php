<?php
/**
 * @package            Joomla
 * @subpackage         Event Booking
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2020 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */


defined('_JEXEC') or die;

$db                = JFactory::getDbo();
$nullDate          = $db->getNullDate();
$dateFormat        = $config->date_format;
$timeFormat        = $config->event_time_format ? $config->event_time_format : 'g:i a';
$bootstrapHelper   = EventbookingHelperBootstrap::getInstance();
$rowFluidClass     = $bootstrapHelper->getClassMapping('row-fluid');
$span2Class        = $bootstrapHelper->getClassMapping('span2');
$span10Class       = $bootstrapHelper->getClassMapping('span10');
$iconMapMakerClass = $bootstrapHelper->getClassMapping('icon-map-marker');
$iconFolderClass   = $bootstrapHelper->getClassMapping('icon-folder-open');
$span              = $bootstrapHelper->getClassMapping('span' . intval(12 / $numberEventPerRow));
$iconCalendarClass = $bootstrapHelper->getClassMapping('icon-calendar');
$numberEvents      = count($rows);

if ($numberEvents > 0)
{
?>
    <div id="eb-events" class="<?php echo $rowFluidClass; ?>">
        <?php
        $baseUri = JUri::base(true);
        $count = 0;

        for ($i = 0, $n = count($rows) ; $i < $n; $i++)
        {
          // print_r($event);
          $event = $rows[$i];
          $count++;
          $date = JHtml::_('date', $event->event_date, 'd', null);
          $month = JHtml::_('date', $event->event_date, 'n', null);
          $eventDate =  JHtml::_('date', $event->event_date, 'h:i A') .' to '. JHtml::_('date', $event->event_end_date, 'h:i A');

	        if ($linkToRegistrationForm && EventbookingHelperRegistration::acceptRegistration($event))
	        {
		        if ($event->registration_handle_url)
		        {
			        $detailUrl = $event->registration_handle_url;
		        }
		        else
		        {
			        $detailUrl = JRoute::_('index.php?option=com_eventbooking&task=register.individual_registration&event_id=' . $event->id . '&Itemid=' . $itemId);
		        }
	        }
	        else
	        {
		        $detailUrl = JRoute::_(EventbookingHelperRoute::getEventRoute($event->id, $event->main_category_id, $itemId));;
	        }
			    ?>

          <div class="up-event-item <?php echo $span; ?> col-md-6 d-flex eb-event-box card-event-catid-<?php echo $event->category->id; ?>">
            <div class="eb-event-date-time clearfix">
              <?php
              if ($event->event_date != EB_TBC_DATE)
              {
                echo JHtml::_('date', $event->event_date, 'd', null) . '<span class="small">' . JHtml::_('date', $event->event_date, 'F', null) . '</span>';
              }
              else
              {
                echo JText::_('EB_TBC');
              }
              ?>
            </div>

            <a class="w-100 card" href="<?php echo $detailUrl; ?>" <?php echo (($event->category->id == '3' ) ? 'style="background-image: url(' . $event->image . ');"' : ''); // backgound-image des événements ?>>
              <?php
              if ($showThumb && $event->thumb && file_exists(JPATH_ROOT . '/media/com_eventbooking/images/thumbs/' . $event->thumb))
              {
              ?>
                <?php if($event->category->id !== '3') : // n'appartient pas à la catégorie Événements ?>
                <div class="card-thumbnail" style="background-image: url('<?php echo $baseUri . '/media/com_eventbooking/images/thumbs/' . $event->thumb; ?>');">
                <?php else : ?>
                <div class="card-thumbnail">
                <?php endif; ?>

                  <?php if ($showCategory) : ?>
                    <h4 class="h3 event-category"><?php echo $event->category->name; ?></h4>
                  <?php endif; ?>

                </div>
              <?php
              }
              ?>

              <div class="card-body">
              	<h3 class="card-title">
      						<?php echo $event->title;?>
      				  </h3>

        				<div class="eb-event-location-price">
        					<?php
        					if ($event->location_id && $showLocation)
        					{
        					?>
        						<div class="eb-event-location <?php echo $bootstrapHelper->getClassMapping('span9'); ?>">
        							<i class="<?php echo $iconMapMakerClass; ?>"></i>
        							<?php
        							if ($event->location_address)
        							{
        							?>
        								<a href="<?php echo JRoute::_('index.php?option=com_eventbooking&view=map&location_id='.$event->location_id.'&tmpl=component'); ?>" class="eb-colorbox-map"><span><?php echo $event->location_name ; ?></span></a>
        							<?php
        							}
        							else
        							{
        								echo $event->location_name;
        							}
        							?>
        						</div>
        						<?php
        					}

        					if ($event->price_text)
        					{
        						$priceDisplay = $event->price_text;
        					}
        					elseif ($event->individual_price > 0)
        					{
        						$symbol        = $event->currency_symbol ? $event->currency_symbol : $config->currency_symbol;
        						$priceDisplay  = EventbookingHelper::formatCurrency($event->individual_price, $config, $symbol);
        					}
        					elseif ($config->show_price_for_free_event)
        					{
        						$priceDisplay = JText::_('EB_FREE');
        					}
        					else
        					{
        						$priceDisplay = '';
        					}

        					if ($priceDisplay && $showPrice)
        					{
        					?>

                    <?php if($event->category->id !== '3') : // n'appartient pas à la catégorie Événements ?>
          						<div class="eb-event-price">
          							<span class="eb-individual-price badge badge-primary"><?php echo $priceDisplay; ?></span>
          						</div>
                    <?php endif; ?>

        					<?php
        					}
        					?>
        				</div>

  	            <?php
                  if ($showShortDescription)
                  {
                  ?>
  	                <div class="eb-event-short-description clearfix">
  		                <?php echo $event->short_description; ?>
  	                </div>
  		            <?php
                  }
                ?>
              </div>

              <div class="card-footer">
                <span class="btn btn-outline-primary">
                  <?php echo JText::_('EB_DETAILS'); ?>
                </span>
              </div>

            </a>
          </div>

        <?php
        }
        ?>

    </div>

    <?php
    }
    else
    {
    ?>
        <div class="eb_empty"><?php echo JText::_('EB_NO_UPCOMING_EVENTS') ?></div>
    <?php
    }
