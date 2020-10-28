<?php
/**
 * @package            Joomla
 * @subpackage         Event Booking
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2020 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$item = $this->item;

EventbookingHelperData::prepareDisplayData([$item], @$item->main_category_id, $this->config, $this->Itemid);

$socialUrl = JUri::getInstance()->toString(array('scheme', 'user', 'pass', 'host')) . $item->url;

/* @var EventbookingHelperBootstrap $bootstrapHelper*/
$bootstrapHelper   = $this->bootstrapHelper;
$iconPencilClass   = $bootstrapHelper->getClassMapping('icon-pencil');
$iconOkClass       = $bootstrapHelper->getClassMapping('icon-ok');
$iconRemoveClass   = $bootstrapHelper->getClassMapping('icon-remove');
$iconDownloadClass = $bootstrapHelper->getClassMapping('icon-download');
$btnClass          = $bootstrapHelper->getClassMapping('btn');
$iconPrint         = $bootstrapHelper->getClassMapping('icon-print');
$clearfixClass     = $bootstrapHelper->getClassMapping('clearfix');
$return = base64_encode(JUri::getInstance()->toString());

$isMultipleDate = false;

if ($this->config->show_children_events_under_parent_event && $item->event_type == 1)
{
	$isMultipleDate = true;
}

$offset = JFactory::getConfig()->get('offset');

if ($this->showTaskBar)
{
	$layoutData = array(
		'item'              => $this->item,
		'config'            => $this->config,
		'isMultipleDate'    => $isMultipleDate,
		'canRegister'       => $item->can_register,
		'registrationOpen'  => $item->registration_open,
		'waitingList'       => $item->waiting_list,
		'return'            => $return,
		'showInviteFriend'  => true,
		'ssl'               => (int) $this->config->use_https,
		'Itemid'            => $this->Itemid,
		'btnClass'          => $btnClass,
		'iconOkClass'       => $iconOkClass,
		'iconRemoveClass'   => $iconRemoveClass,
		'iconDownloadClass' => $iconDownloadClass,
		'iconPencilClass'   => $iconPencilClass,
	);

	$registerButtons = EventbookingHelperHtml::loadCommonLayout('common/buttons.php', $layoutData);
}

if (!$this->config->get('show_group_rates', 1))
{
    $this->rowGroupRates = [];
}
?>

	<div id="eb-event-page" class="eb-category-<?php echo $item->category_id; ?> eb-event<?php if ($item->featured) echo ' eb-featured-event'; ?>">
		<div class="hero">
			<?php if ($this->config->get('show_image_in_event_detail', 1) && $this->config->display_large_image && !empty($item->image_url)) : ?>
				<div class="item-img-fullwidth" style="background-image: url('<?php echo $item->image_url; ?>');">
					<div class="overlay"></div>

					<div class="container d-flex flex-column">
						<div class="page-header">
							<h1 itemprop="headline">
								<?php
									$categories = [];
									foreach ($item->categories as $category)
									{
										$categories[] = $category->name;
									}
									// echo '<h4 class="h3">' . implode(',' , $categories) . '</h4>';
									echo '<p class="small">' . $category->name . '</p>';
								?>

								<?php echo $item->title; ?>
							</h1>
						</div>
					</div>

				</div>
			<?php endif; ?>
		</div>

		<div>
			<?php
				// Facebook, twitter, Gplus share buttons
				if ($this->config->show_fb_like_button)
				{
					echo $this->loadTemplate('share', ['socialUrl' => $socialUrl]);
				}

				if ($this->showTaskBar && in_array($this->config->get('register_buttons_position', 0), array(1,2)))
				{
				?>
					<div class="eb-taskbar eb-register-buttons-top <?php echo $clearfixClass; ?>">
						<ul>
							<?php echo $registerButtons; ?>
						</ul>
					</div>
				<?php
				}
			?>

		<div class="container">
			<div class="eb-description-details <?php echo $clearfixClass; ?>">
				<div id="eb-event-info" class="card text-white bg-primary float-md-right ml-md-5 mb-3 mb-md-0">
					<?php
						if (!empty($this->items))
						{
							echo EventbookingHelperHtml::loadCommonLayout('common/events_children.php', array('items' => $this->items, 'config' => $this->config, 'Itemid' => $this->Itemid, 'nullDate' => $this->nullDate, 'ssl' => (int) $this->config->use_https, 'viewLevels' => $this->viewLevels, 'categoryId' => $this->item->category_id, 'bootstrapHelper' => $this->bootstrapHelper));
						}
						else
						{
						$leftCssClass = 'span8';

							if (empty($this->rowGroupRates))
							{
								$leftCssClass = 'span12';
							}
						?>

						<div id="eb-event-info-left" class="<?php echo $bootstrapHelper->getClassMapping($leftCssClass); ?>">
							<?php
							$layoutData = array(
								'item'           => $this->item,
								'config'         => $this->config,
								'location'       => $item->location,
								'showLocation'   => true,
								'isMultipleDate' => false,
								'nullDate'       => $this->nullDate,
								'Itemid'         => $this->Itemid,
							);

							echo EventbookingHelperHtml::loadCommonLayout('common/event_properties-cimes.php', $layoutData);
							?>
						</div>

					<?php
						if (count($this->rowGroupRates))
						{
							echo $this->loadTemplate('group_rates');
						}
					}
					?>
				</div>

				<?php
					echo $item->description;
				?>
			</div>

			<?php
			if ($this->config->show_location_info_in_event_details && $item->location && ($item->location->image || EventbookingHelper::isValidMessage($item->location->description)))
			{
				echo $this->loadTemplate('location', array('location' => $item->location));
			}

			foreach ($this->horizontalPlugins as $plugin)
		    {
		    ?>
		        <h3 class="eb-horizntal-plugin-header"><?php echo $plugin['title']; ?></h3>
		    <?php
		        echo $plugin['form'];
		    }

			if ($this->config->display_ticket_types && !empty($item->ticketTypes))
			{
				echo EventbookingHelperHtml::loadCommonLayout('common/tickettypes.php', array('ticketTypes' => $item->ticketTypes, 'config' => $this->config, 'event' => $item));
			?>
				<div class="<?php echo $clearfixClass; ?>"></div>
			<?php
			}

			if (!$item->can_register && $item->registration_type != 3 && $this->config->display_message_for_full_event && !$item->waiting_list && $item->registration_start_minutes >= 0 && empty($this->items))
			{
				if (@$item->user_registered)
				{
					$msg = JText::_('EB_YOU_REGISTERED_ALREADY');
				}
		        elseif (!in_array($item->registration_access, $this->viewLevels))
				{
					if (JFactory::getUser()->id)
					{
						$msg = JText::_('EB_REGISTRATION_NOT_AVAILABLE_FOR_ACCOUNT');
					}
					else
					{
						$loginLink = JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode(JUri::getInstance()->toString()));
						$msg       = str_replace('[LOGIN_LINK]', $loginLink, JText::_('EB_LOGIN_TO_REGISTER'));
					}
				}
				// else
				// {
				// 	$msg = JText::_('EB_NO_LONGER_ACCEPT_REGISTRATION');
				// }
			?>
		        <div class="text-info text-center"><p class="small font-weight-bold"><?php echo $msg; ?></p></div>
			<?php
			}

			if (count($this->plugins))
			{
				echo $this->loadTemplate('plugins');
			}

			if ($this->config->show_social_bookmark && !$this->print)
			{
				echo $this->loadTemplate('social_buttons', array('socialUrl' => $socialUrl));
			}
		?>
		</div>

	</div>
</div>


<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_eventbooking&Itemid=' . $this->Itemid); ?>" method="post">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>

<script language="javascript">
	function cancelRegistration(registrantId) {
		var form = document.adminForm ;
		if (confirm("<?php echo JText::_('EB_CANCEL_REGISTRATION_CONFIRM'); ?>")) {
			form.task.value = 'registrant.cancel' ;
			form.id.value = registrantId ;
			form.submit() ;
		}
	}
	<?php
	if ($this->print)
	{
	?>
		window.print();
	<?php
	}
?>
</script>
<?php
JFactory::getApplication()->triggerEvent('onDisplayEvents', [[$item]]);
