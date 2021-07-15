<?php get_header();?>
<?php the_post();?>
<?php pageBanner();?>

  <div class="container container--narrow page-section">
    <?php //var_dump(get_field('page_banner_background_image')['sizes']['pageBanner']);?>
		<?php $theParent = wp_get_post_parent_id(get_the_ID());?>
    
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

    <div class="generic-content">
      <div class="row group">
        <div class="one-third">
          <?php the_post_thumbnail('professorPortrait'); ?>
        </div>
        <div class="two-third">
					<?php
						$likeCount = new WP_Query([
							'post_type'=> 'like',
							'meta_query'=> [
								['key'=> 'liked_professor_id', 'compare'=> '=', 'value'=> get_the_ID()],
							],
						]);

						if(is_user_logged_in()) {
							$existQuery = new WP_Query([
								'author'=> get_current_user_id(),
								'post_type'=> 'like',
								'meta_query'=> [
									['key'=> 'liked_professor_id', 'compare'=> '=', 'value'=> get_the_ID()],
								],
							]);
						}

					?>
					<span class="like-box" data-exists="<?php echo $existQuery->found_posts?"yes":"";?>" data-professor="<?php the_ID();?>" data-like="<?php echo $existQuery->posts[0]->ID;?>">
						<i class="fa fa-heart-o" aria-hidden="true"></i>
						<i class="fa fa-heart" aria-hidden="true"></i>
						<span class="like-count"><?php echo $likeCount->found_posts;?></span>
					</span>
          <?php the_content(); ?>
        </div>
      </div>
    </div>
		<?php $relatedPrograms = get_field('related_programs');	?>
		<?php	if($relatedPrograms) : ?>
		<hr class="section-break">
		<h2 class="headline headline--medium">Subject(s) Taught</h2>
		<ul class="link-list min-list">
			<?php	foreach($relatedPrograms as $program) : ?>
				<li><a href="<?php echo get_the_permalink($program);?>"><?php	echo get_the_title($program); ?></a></li>
			<?php endforeach;	?>
		</ul>
		<?php endif;	?>
			

  </div>
<?php get_footer();?>