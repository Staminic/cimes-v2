<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$msgList = $displayData['msgList'];

?>
<div id="system-message-container" style="position: absolute; width: 100%; z-index: 1;">
	<?php if (is_array($msgList) && !empty($msgList)) : ?>
		<div class="container">
			<div id="system-message">
				<?php foreach ($msgList as $type => $msgs) : ?>
					<div class="alert alert-<?php echo $type; ?>" style="margin-top: 1rem; background-color: #f46b06; color: #ffffff;">
						<?php // This requires JS so we should add it through JS. Progressive enhancement and stuff. ?>
						<a class="close" data-dismiss="alert" style="margin-right: 5px;">×</a>

						<?php if (!empty($msgs)) : ?>
							<div>
								<?php foreach ($msgs as $msg) : ?>
									<div class="alert-message"><?php echo $msg; ?></div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
