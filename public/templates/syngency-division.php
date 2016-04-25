<div class="syngency-division">

	<?php foreach ( $models as $model ): ?>

	<div class="syngency-division-model" data-index="<?php echo strtoupper($model->display_name[0]); ?>">

		<a href="<?php echo get_permalink(); ?>view?url=<?php echo sanitize_title($model->display_name); ?>">
			<img src="<?php echo $model->headshot_url; ?>" class="syngency-division-model-headshot">
			<div class="syngency-division-model-name">
				<?php echo $model->display_name; ?>
			</div>
		</a>
	
	</div>

	<?php endforeach; ?>

</div>
