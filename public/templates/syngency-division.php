<ul class="syngency-gender-filter">
	<li>
		<a href="#" data-gender="all" class="active">TODOS</a>
	</li>
	<li>
		<a href="#" data-gender="male">HOMENS</a>
	</li>
	<li>
		<a href="#" data-gender="female">MULHERES</a>
	</li>
</ul>

<div class="syngency-division">

	<?php foreach ( $models as $model ): ?>

	<div class="syngency-division-model <?php echo $model->gender; ?>" data-index="<?php echo strtoupper($model->display_name[0]); ?>">

		<a href="<?php echo get_permalink(); ?>view?url=<?php echo sanitize_title($model->display_name); ?>">
			<img src="<?php echo $model->headshot_url; ?>" class="syngency-division-model-headshot">
			<div class="syngency-division-model-name">
				<?php echo $model->display_name; ?>
			</div>
		</a>
	
	</div>

	<?php endforeach; ?>

</div>
