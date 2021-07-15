<?php
add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
	register_rest_route('university/v1', 'like', [
		'methods'=> 'POST',
		'callback'=> 'createLike',
	]);

	register_rest_route('university/v1', 'like', [
		'methods'=> 'DELETE',
		'callback'=> 'deleteLike',
	]);
}

function createLike($request) {
	if(is_user_logged_in()) {

		$existQuery = new WP_Query([
			'author'=> get_current_user_id(),
			'post_type'=> 'like',
			'meta_query'=> [
				['key'=> 'liked_professor_id', 'compare'=> '=', 'value'=> sanitize_text_field($request['professorId'])],
			],
		]);
		
		if($existQuery->found_posts > 0 && get_post_type($request['professorId'])=='professor') {
			die("Like already");
		}

		$likeId = wp_insert_post([
			'post_type'=> 'like',
			'post_status'=> 'publish',
			'post_title'=> 'PHP Create',
			// 'post_content'=> 'Hello world',
			'meta_input' => [
				'liked_professor_id'=> sanitize_text_field($request['professorId']),
			],
		]);
		return ["success"=>true, "likeId"=>$likeId ];
	}else{
		die("Only logged in users can create like.");
	}
}

function deleteLike($request) {

	$likeId = sanitize_text_field($request['likeId']);
	
	if(get_current_user_id() == get_post_field('post_author', $likeId) 
			&& get_post_type($likeId) =='like') {

		wp_delete_post($likeId, true);
		return ["success"=>true];

	}else{
		die("You do not have permission");
	}

}