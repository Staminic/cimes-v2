<?php
/**
 * @package        	Joomla
 * @subpackage		Event Booking
 * @author  		Tuan Pham Ngoc
 * @copyright    	Copyright (C) 2010 - 2020 Ossolution Team
 * @license        	GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die ;

$return     = base64_encode(JUri::getInstance()->toString());
$timeFormat = $config->event_time_format ?: 'g:i a';
$dateFormat = $config->date_format;

/* @var EventbookingHelperBootstrap $bootstrapHelper */
$rowFluidClass     = $bootstrapHelper->getClassMapping('row-fluid');
$btnClass          = $bootstrapHelper->getClassMapping('btn');
$btnInverseClass   = $bootstrapHelper->getClassMapping('btn-inverse');
$iconOkClass       = $bootstrapHelper->getClassMapping('icon-ok');
$iconRemoveClass   = $bootstrapHelper->getClassMapping('icon-remove');
$iconPencilClass   = $bootstrapHelper->getClassMapping('icon-pencil');
$iconDownloadClass = $bootstrapHelper->getClassMapping('icon-download');
$iconCalendarClass = $bootstrapHelper->getClassMapping('icon-calendar');
$iconMapMakerClass = $bootstrapHelper->getClassMapping('icon-map-marker');
$clearfixClass     = $bootstrapHelper->getClassMapping('clearfix');
$btnPrimaryClass   = $bootstrapHelper->getClassMapping('btn-primary');

$linkThumbToEvent   = $config->get('link_thumb_to_event_detail_page', 1);

$numberColumns = JFactory::getApplication()->getParams()->get('number_columns', 3);

if (!$numberColumns)
{
	$numberColumns = 3;
}

$baseUri      = JUri::base(true);
$span         = 'span' . intval(12 / $numberColumns);
$span         = $bootstrapHelper->getClassMapping($span);
$numberEvents = count($events);
$count        = 0;

if (!empty($category->id))
{
	$activeCategoryId = $category->id;
}
else
{
	$activeCategoryId = 0;
}

EventbookingHelperData::prepareDisplayData($events, $activeCategoryId, $config, $Itemid);
?>

<div id="eb-events" class="<?php echo $rowFluidClass . ' ' . $clearfixClass; ?>">
	<?php
		for ($i = 0 , $n = count($events) ;  $i < $n ; $i++)
		{
			$count++;
			$event = $events[$i];
		?>
			<div class="<?php echo(($event->category_id == '3') ? 'col-lg-6' : $span ); ?> col-md-6 d-flex eb-category-<?php echo $event->category_id; ?><?php if ($event->featured) echo ' eb-featured-event'; ?> eb-event-box eb-event-<?php echo $event->id; ?> clearfix card-event-catid-<?php echo $category->id; ?> mb-4" <?php echo(($event->category_id == '3') ? 'style="margin: 0 auto;"' : '' ); ?>>
				<div class="eb-event-date-time <?php echo $clearfixClass; ?>">
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

				<?php if($category->id == '3') : // n'appartient pas à la catégorie Événements ?>
					<div class="ribbon">
						<span>Save the date</span>
					</div>
				<?php endif; ?>

				<a class="w-100 card" href="<?php echo $event->url; ?>" <?php echo (($category->id == '3' ) ? 'style="background-image: url(' . $event->image . ');"' : ''); // backgound-image des événements ?>>
					<?php if($category->id !== '3') : // n'appartient pas à la catégorie Événements ?>
					<div class="card-thumbnail" style="background-image: url('<?php echo $event->thumb_url; ?>');">
					<?php else : ?>
					<div class="card-thumbnail">
					<?php endif; ?>

						<?php
							$categories = [];
							foreach ($event->categories as $category)
							{
								$categories[] = $category->name;
							}

							// echo '<h4 class="h3">' . implode(',' , $categories) . '</h4>';
							echo '<h4 class="h3 event-category">' . $category->name . '</h4>';
						?>
					</div>

					<div class="card-body">
						<h3 class="card-title">
							<?php
								echo $event->title;
							?>
						</h3>

						<div class="eb-event-location-price">
							<?php
							if ($event->location_id)
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

							if ($config->show_discounted_price)
							{
								$price = $event->discounted_price;
							}
							else
							{
								$price = $event->individual_price;
							}

							if ($event->price_text)
							{
								$priceDisplay = $event->price_text;
							}
							elseif ($price > 0)
							{
								$symbol        = $event->currency_symbol ? $event->currency_symbol : $config->currency_symbol;
								$priceDisplay  = EventbookingHelper::formatCurrency($price, $config, $symbol);
							}
							elseif ($config->show_price_for_free_event)
							{
								$priceDisplay = JText::_('EB_FREE');
							}
							else
							{
								$priceDisplay = '';
							}

							if ($priceDisplay)
							{
							?>
								<?php if($category->id !== '3') : // n'appartient pas à la catégorie Événements ?>
									<div class="eb-event-price">
										<span class="eb-individual-price badge badge-primary"><?php echo $priceDisplay; ?></span>
									</div>
								<?php endif; ?>
							<?php
							}
							?>
						</div>

						<?php if ($event->short_description) : ?>
							<div class="eb-event-short-description <?php echo $clearfixClass; ?>">
								<?php echo $event->short_description; ?>
							</div>
						<?php endif; ?>

						<?php
						    // Event message to tell user that they already registered, need to login to register or don't have permission to register...
						    // echo EventbookingHelperHtml::loadCommonLayout('common/event_message.php', array('config' => $config, 'event' => $event));
						?>
					</div>

					<div class="card-footer">
						<div class="eb-taskbar <?php echo $clearfixClass; ?>">
							<ul>
								<?php
								if ($config->get('show_register_buttons', 1) && !$event->is_multiple_date)
								{
									if ($event->can_register)
									{
										$registrationUrl = trim($event->registration_handle_url);

										if ($registrationUrl)
										{
										?>
											<li>
												<a class="<?php echo $btnClass; ?>" href="<?php echo $registrationUrl; ?>" target="_blank"><?php echo JText::_('EB_REGISTER');; ?></a>
											</li>
										<?php
										}
										else
										{
											if ($event->registration_type == 0 || $event->registration_type == 1)
											{
												if ($config->multiple_booking && !$event->has_multiple_ticket_types)
												{
													$url = 'index.php?option=com_eventbooking&task=cart.add_cart&id=' . (int) $event->id . '&Itemid=' . (int) $Itemid;

													if ($event->event_password)
													{
														$extraClass = '';
													}
													else
													{
														$extraClass = 'eb-colorbox-addcart';
													}

													$text = JText::_('EB_REGISTER');
												}
												else
												{
													$url = JRoute::_('index.php?option=com_eventbooking&task=register.individual_registration&event_id=' . $event->id . '&Itemid=' . $Itemid, false, $ssl);

													if ($event->has_multiple_ticket_types)
													{
														$text = JText::_('EB_REGISTER');
													}
													else
													{
														$text = JText::_('EB_REGISTER_INDIVIDUAL');
													}

													$extraClass = '';
												}
												?>
		                      <li>
		                          <a class="<?php echo $btnClass . ' ' . $extraClass; ?>" href="<?php echo $url; ?>"><?php echo $text; ?></a>
		                      </li>
												<?php
											}

											if (($event->registration_type == 0 || $event->registration_type == 2) && !$config->multiple_booking && !$event->has_multiple_ticket_types)
											{
											?>
												<li>
													<a class="<?php echo $btnClass; ?>" href="<?php echo JRoute::_('index.php?option=com_eventbooking&task=register.group_registration&event_id=' . $event->id . '&Itemid=' . $Itemid, false, $ssl); ?>"><?php echo JText::_('EB_REGISTER_GROUP');; ?></a>
												</li>
											<?php
											}
										}
									}
									elseif ($event->waiting_list)
									{
										if ($event->registration_type == 0 || $event->registration_type == 1)
										{
										?>
											<li>
												<a class="<?php echo $btnClass; ?>"
												   href="<?php echo JRoute::_('index.php?option=com_eventbooking&task=register.individual_registration&event_id=' . $event->id . '&Itemid=' . $Itemid, false, $ssl); ?>"><?php echo JText::_('EB_REGISTER_INDIVIDUAL_WAITING_LIST');; ?></a>
											</li>
										<?php
										}

										if (($event->registration_type == 0 || $event->registration_type == 2) && !$config->multiple_booking)
										{
										?>
											<li>
												<a class="<?php echo $btnClass; ?>" href="<?php echo JRoute::_('index.php?option=com_eventbooking&task=register.group_registration&event_id=' . $event->id . '&Itemid=' . $Itemid, false, $ssl); ?>"><?php echo JText::_('EB_REGISTER_GROUP_WAITING_LIST');; ?></a>
											</li>
										<?php
										}
									}
								}

								if ($config->hide_detail_button !== '1' || $event->is_multiple_date)
								{
								?>
									<li>
										<span class="btn btn-outline-primary">
											<?php echo $event->is_multiple_date ? JText::_('EB_CHOOSE_DATE_LOCATION') : JText::_('EB_DETAILS');?>
										</span>
									</li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
				</a>

			</div>

		<?php
		}
	?>
</div>

<script type="text/javascript">
	function cancelRegistration(registrantId) {
		var form = document.adminForm ;
		if (confirm("<?php echo JText::_('EB_CANCEL_REGISTRATION_CONFIRM'); ?>")) {
			form.task.value = 'registrant.cancel' ;
			form.id.value = registrantId ;
			form.submit() ;
		}
	}
</script>

<?php
// Add Google Structured Data
JPluginHelper::importPlugin('eventbooking');
JFactory::getApplication()->triggerEvent('onDisplayEvents', [$events]);
