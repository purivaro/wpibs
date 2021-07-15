<?php get_header();?>
<?php pageBanner([
  'title'=>'Our Campuses',
  'subtitle'=>'Have a look around.',
  'photo'=>get_theme_file_uri('/images/bread.jpg'),
]);?>

<div class="container container--narrow page-section">

	<div class="acf-map">
		<?php while(have_posts()) : the_post();?>
			<?php 
				$mapLocation = get_field('map_location');
			?>
			<div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php echo $mapLocation['address']; ?>
			</div>
		<?php endwhile;?>
	</div>
	
</div>

<?php get_footer();?>