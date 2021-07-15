<?php get_header();?>
<?php the_post();?>
<?php pageBanner();?>

  <div class="container container--narrow page-section">
		<?php $theParent = wp_get_post_parent_id(get_the_ID());?>
    <div class="metabox metabox--position-up metabox--with-home-link">
			<p>
				<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program');?>"><i class="fa fa-home" aria-hidden="true"></i> Program Home</a> 
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

    <div class="generic-content"><?php the_field('main_body_content'); ?></div>

    <?php 
      $relatedProfessors = new WP_Query([
        'posts_per_page'=>-1,
        'post_type'=>'professor',
        'orderby'=>'title',
        'order'=>'ASC',
        'meta_query'=> [
          ['key'=> 'related_programs', 'compare'=>'LIKE', 'value'=> '"'.get_the_ID().'"'],
        ],
      ]);
    ?>

    <?php if($relatedProfessors->have_posts()): ?>
      <hr class="section-break">
      <h2 class="headline headline--small-plus">Professor(s) of <?php the_title(); ?></h2>
      <ul class="professor-cards">
        <?php while($relatedProfessors->have_posts()): $relatedProfessors->the_post(); ?>
          <li class="professor-card__list-item">
            <a href="<?php the_permalink();?>" class="professor-card">
              <img src="<?php the_post_thumbnail_url('professorLandscape');?>" class="professor-card__image">
              <span class="professor-card__name"><?php the_title();?></span>
            </a>
          </li>
        <?php endwhile; wp_reset_postdata(); ?>
      </ul>
    <?php endif; ?>

    <?php
      $homepageEvents = new WP_Query([
        'posts_per_page'=>2,
        'post_type'=>'event',
        'meta_key'=>'event_date',
        'orderby'=>'meta_value',
        'order'=>'ASC',
        'meta_query'=> [
          ['key'=> 'event_date', 'compare'=>'>=', 'value'=> date('Ymd'), 'type'=>'numeric'],
          ['key'=> 'related_programs', 'compare'=>'LIKE', 'value'=> '"'.get_the_ID().'"'],
        ],
      ]);
    ?>
    
    <?php if($homepageEvents->have_posts()): ?>
      <hr class="section-break">
      <h2 class="headline headline--small-plus">Upcoming <?php the_title(); ?> Events</h2>
      <?php while($homepageEvents->have_posts()): $homepageEvents->the_post(); ?>
      <?php get_template_part('template-parts/content', get_post_type()); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php endif; ?>

    <hr class="section-break">
    <?php
      $relatedCampuses = get_field('related_campus');
      if($relatedCampuses) {
        echo '<h2 class="headline headline--small-plus">'.get_the_title().' is available at these campuses:</h2>';
        echo '<ul class="min-list link-list">';
        foreach($relatedCampuses as $campus) {
          echo '<li><a href="'.get_the_permalink($campus).'">'.get_the_title($campus).'</a></li>';
        }
        echo "</ul>";
      }
    ?>

  </div>
<?php get_footer();?>