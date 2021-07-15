<?php get_header();?>
<?php the_post();?>
<?php pageBanner();?>

  <div class="container container--narrow page-section">
		<?php $theParent = wp_get_post_parent_id(get_the_ID());?>
    <div class="metabox metabox--position-up metabox--with-home-link">
			<p>
				<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event');?>"><i class="fa fa-home" aria-hidden="true"></i> Event Home</a> 
    </div>
    
		<?php if($theParent || get_pages(['child_of'=>get_the_ID()])):?>
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent);?>"><?php echo get_the_title($theParent);?></a></h2>
      <ul class="min-list">
				<?php
					wp_list_pages([
						'title_li'=>NULL,
						'child_of'=>$theParent?$theParent:get_the_ID(),
						'sort_column'=>'menu_order',
					]);
				?>
      </ul>
    </div>
		<?php endif;?>

    <div class="generic-content"><?php the_content(); ?></div>
		<?php $relatedPrograms = get_field('related_programs');	?>
		<?php	if($relatedPrograms) : ?>
		<hr class="section-break">
		<h2 class="headline headline--medium">Related Program(s)</h2>
		<ul class="link-list min-list">
			<?php	foreach($relatedPrograms as $program) : ?>
				<li><a href="<?php echo get_the_permalink($program);?>"><?php	echo get_the_title($program); ?></a></li>
			<?php endforeach;	?>
		</ul>
		<?php endif;	?>
			

  </div>
<?php get_footer();?>