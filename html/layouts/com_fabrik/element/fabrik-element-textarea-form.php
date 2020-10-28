<?php

defined('JPATH_BASE') or die;

// Add span with id so that element fxs work.
$d = $displayData;
$d->attributes['class'] = 'fabrikinput inputbox input-xxlarge form-control'
?>

<textarea
	<?php foreach ($d->attributes as $key => $value) :
	echo $key . '="' . $value . '" ';
endforeach;
	?>><?php echo $d->value;?></textarea>

<?php if ($d->showCharsLeft) : ?>
	<?php echo $this->sublayout('charsleft', $d);?>
<?php endif; ?>
