<?php get_header();?>
<?php the_post();?>
<?php pageBanner();?>


  <div class="container container--narrow page-section">
		<?php $theParent = wp_get_post_parent_id(get_the_ID());?>
    <div class="metabox metabox--position-up metabox--with-home-link">
			<p>
				<a class="metabox__blog-home-link" href="<?php echo site_url('/blogs');?>"><i class="fa fa-home" aria-hidden="true"></i> Blog Home</a> 
				<span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time(); ?> in <?php echo get_the_category_list(', '); ?></span></p>
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

    <div class="generic-content">
      <p><?php the_content(); ?></p>
    </div>

  </div>
<?php get_footer();?>