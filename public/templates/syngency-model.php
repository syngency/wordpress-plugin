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

	<!-- Model Name -->
	<h2 class="syngency-model-name"><?php echo $model->display_name; ?></h2>

	<!-- Gallery Links -->
	<ul class="syngency-model-galleries">
	<?php $i = 0; foreach ( $model->galleries as $gallery ): ?>
		<li>
			<a href="#<?php echo $gallery->url; ?>"<?php if ( $i == 0 ) echo ' class="active"'; $i++; ?>
><?php echo $gallery->name; ?></a>
		</li>
	<?php endforeach; ?>
	</ul>

	<?php $i = 0; foreach ( $model->galleries as $gallery ): ?>

	<!-- Gallery -->
	<div class="syngency-model-gallery<?php if ( $i > 0 ) echo ' hide'; ?>" id="<?php echo $gallery->url; ?>">
		<h3 class="syngency-model-gallery-name"><?php echo $gallery->name; ?></h3>
		<ul>
			<?php foreach ( $gallery->files as $file ): ?>

				<?php if ( $file->is_image ): ?>
				<li class="syngency-model-gallery-image">
					<?php if ( !empty($this->options['link_size']) ): ?>
					<a href="<?php echo $file->{$this->options['link_size'] . '_url'}; ?>" rel="<?php echo $gallery->url; ?>">
						<img src="<?php echo $file->{$this->options['image_size'] . '_url'}; ?>" alt="">
					</a>
					<?php else: ?>
					<img src="<?php echo $file->{$this->options['image_size'] . '_url'}; ?>" alt="">
					<?php endif; ?>
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

	<?php $i++; endforeach; ?>

	<!-- Measurements -->
	<ul class="syngency-model-measurements">
		<?php foreach ( $model->measurements as $measurement ):
			// Skip measurement if not set as visible
			if ( !in_array($measurement->name, $this->options['measurements']) ) continue;	
		?>
		<li>
			<span class="label"><?php echo __( $measurement->name, 'syngency' ); ?></span>
			<?php if ( isset($measurement->imperial) ): ?>
				<span class="value imperial"><?php echo $measurement->imperial; ?></span>
				<span class="value metric"><?php echo $measurement->metric; ?></span>
			<?php else: ?>
				<span class="value size"><?php echo $measurement->size; ?></span>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
		<?php if ( in_array('Hair', $this->options['measurements']) ): ?>
		<li>
			<span class="label"><?php echo __( 'Hair', 'syngency' ); ?></span>
			<span class="value"><?php echo $model->hair_color; ?></span>
		</li>
		<?php endif; ?>
		<?php if ( in_array('Eyes', $this->options['measurements']) ): ?>
		<li>
			<span class="label"><?php echo __( 'Eyes', 'syngency' ); ?></span>
			<span class="value"><?php echo $model->eye_color; ?></span>
		</li>
		<?php endif; ?>
	</ul>

</div>