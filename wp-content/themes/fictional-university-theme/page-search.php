<?php get_header();?>
<div class="page-banner">
	<div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg');?>);"></div>
	<div class="page-banner__content container container--narrow">
		<h1 class="page-banner__title">
			Search
		</h1>
		<div class="page-banner__intro">
		</div>
	</div>  
</div>
<div class="container container--narrow page-section">
	<div class="generic-content">
		<form class="search-form" action="<?php echo esc_url(site_url('/'));?>">
			<label for="s" class="headline headline--medium">Perform a New Search</label>
			<div class="search-form-row">
				<input type="search" name="s" id="s" class="s" placeholder="What are you looking for?">
				<button type="submit" class="search-submit">Search</button>
			</div>
		</form>
	</div>
</div>

<?php get_footer();?>