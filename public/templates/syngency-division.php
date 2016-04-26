<ul class="syngency-gender-filter">
	<li>
		<a href="#all" class="active"><?php echo __( 'All', 'syngency' ); ?></a>
	</li>
	<li>
		<a href="#male"><?php echo __( 'Men', 'syngency' ); ?></a>
	</li>
	<li>
		<a href="#female"><?php echo __( 'Women', 'syngency' ); ?></a>
	</li>
</ul>

<ul class="syngency-index-filter">
	<?php foreach ( range('A','Z') as $letter ): ?>
	<li>
		<a href="#<?php echo $letter; ?>"><?php echo $letter; ?></a>
	</li>
	<?php endforeach; ?>
</ul>

<div class="syngency-division">

	<?php foreach ( $models as $model ): ?>

	<div class="syngency-division-model" data-gender="<?php echo $model->gender; ?>" data-index="<?php echo strtoupper($model->display_name[0]); ?>">

		<a href="<?php echo get_permalink(); ?>view?url=<?php echo sanitize_title($model->display_name); ?>">
			<img src="<?php echo $model->headshot_url; ?>" class="syngency-division-model-headshot">
			<div class="syngency-division-model-name">
				<?php echo $model->display_name; ?>
			</div>
		</a>
	
	</div>

	<?php endforeach; ?>

</div>
