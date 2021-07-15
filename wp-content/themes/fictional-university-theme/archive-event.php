<?php get_header();?>
<?php pageBanner([
  'title'=>'All Events',
  'subtitle'=>'See what is going on in our world',
  'photo'=>get_theme_file_uri('/images/bus.jpg'),
]);?>

<div class="container container--narrow page-section">
<?php while(have_posts()) : the_post();?>
<?php get_template_part('template-parts/content', get_post_type()); ?>
<?php endwhile;?>
<?php echo paginate_links();?>
<hr class="section-break">
<p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events'); ?>">Check out our past events</a></p>
</div>

<?php get_footer();?>