<?php get_header();?>
<?php pageBanner([
  'title'=>'All Programs',
  'subtitle'=>'There are something for everyone. Have a look around.',
  'photo'=>get_theme_file_uri('/images/bread.jpg'),
]);?>

<div class="container container--narrow page-section">
<?php while(have_posts()) : the_post();?>
	<div class="event-summary">
		<div class="event-summary__content">
			<h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
		</div>
	</div>
<?php endwhile;?>
<?php echo paginate_links();?>
</div>

<?php get_footer();?>