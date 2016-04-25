<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://syngency.com/add-ons/wordpress
 * @since      1.0.0
 *
 * @package    Syngency
 * @subpackage Syngency/public/partials
 */
?>

<div class="syngency-model">

	<h2><?php echo $model->display_name; ?></h2>

	<ul class="syngency-model-measurements">
		<?php foreach ( $model->measurements as $measurement ): ?>
		<li>
			<span class="label"><?php echo $measurement->name; ?></span>
			<?php if ( isset($measurement->imperial) ): ?>
				<span class="value imperial"><?php echo $measurement->imperial; ?></span>
				<span class="value metric"><?php echo $measurement->metric; ?></span>
			<?php else: ?>
				<span class="value size"><?php echo $measurement->size; ?></span>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php foreach ( $model->galleries as $gallery ): ?>

	<div class="syngency-model-gallery <?php echo $gallery->url; ?>">
		<h3><?php echo $gallery->name; ?></h3>
		<ul>
			<?php foreach ( $gallery->files as $file ): ?>
				<?php if ( $file->is_image ): ?>
				<li class="syngency-model-gallery-image">
					<a href="<?php echo $file->large_url; ?>">
						<img src="<?php echo $file->small_url; ?>" alt="">
					</a>
				</li>
				<?php endif; ?>
				<?php if ( $file->is_video ): ?>
				<li class="syngency-model-gallery-video">
					<video controls>
						<source src="<?php echo $file->url; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
						<source src="<?php echo str_replace('mp4','ogv',$file->url); ?>" type='video/ogg; codecs="theora, vorbis"' />
			    		<source src="<?php echo str_replace('mp4','webm',$file->url); ?>" type='video/webm; codecs="vp8, vorbis"' />
					</video>
				</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endforeach; ?>

</div>