<?php get_header();?>
<?php pageBanner([
  'title'=>'Search Results',
  'subtitle'=>'You search for "'.esc_html(get_search_query()).'"',
  'photo'=>get_theme_file_uri('/images/bread.jpg'),
]);?>

<div class="container container--narrow page-section">
<?php while(have_posts()) : the_post();?>
	<div class="post-item">
		<h5 class="headline headline--medium headline--post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
		<div class="metabox">
			<p>Poseted by <?php the_author_posts_link();?> on <?php the_time('n.j.y');?> in <?php echo get_the_category_list(', ');?> </p>
		</div>
		<div class="generic-content">
			<?php the_excerpt();?>
			<p><a href="<?php the_permalink();?>" class="btn btn--blue">Continue Reading &raquo</a></p>
		</div>
	</div>
<?php endwhile;?>
<?php echo paginate_links();?>
</div>

<?php get_footer();?>