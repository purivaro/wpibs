<?php if(!is_user_logged_in()) {wp_redirect(esc_url(site_url('/')));exit;};?>
<?php get_header();?>
<?php pageBanner([
  'title'=>'My Notes',
  'subtitle'=>'All about my notes',
]);?>
<div class="container container--narrow page-section">

	<div class="create-note">
		<h2 class="headline headline--medium">Create New Note</h2>
		<input type="text" placeholder="Title" class="new-note-title">
		<textarea placeholder="Your note here..." class="new-note-body" ></textarea>
		<span class="submit-note">Create Note</span>
		<span class="note-limit-message">Note limit reached: delete an existing note to make room for a new one.</span>
	</div>

	<ul class="min-list link-list" id="my-notes">
		<?php
			$userNotes = new WP_Query([
				'post_type'=>'note',
				'posts_per_page'=>-1,
				'author'=>get_current_user_id()
			]);
			while($userNotes->have_posts()) : $userNotes->the_post();
		?>
			<li data-id="<?php the_id();?>">
				<input type="text" class="note-title-field" value="<?php echo str_replace('Private: ', '', esc_attr(get_the_title()));?>" readonly >
				<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"> Edit</i></span>
				<span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"> Delete</i></span>
				<textarea readonly class="note-body-field"><?php echo esc_textarea(get_the_content());?></textarea>
				<span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"> Save</i></span>
			</li>
		<?php endwhile;?>
			
	</ul>
</div>
<?php get_footer();?>