<?php 
include '../../../../../wp-load.php';
$term_id = 60;

query_posts(array(
    'post_type' => 'project-elements',
    'showposts' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'phonak-projects',
            'field' => 'term_id',
            'terms' => array($term_id)
        )
    )
));

echo '<h3>Choose from Template Images</h3>';
echo '<div class="project_elements">';

if(have_posts()){
	while ( have_posts() ){
		the_post();
	?>
		<input type="hidden" name="section_project_elements" value="PROJECT ELEMENTS" />
		<div>
			<label>
				<?php if(!empty($sku_code)) { echo '<p class="skualign">'; echo get_post_meta(get_the_ID(),'sku_code',true); echo '</p>'; } ?>
				<?php echo the_post_thumbnail('thumbnail'); ?>

				<span class="button"><?php the_title(); ?></span>
			</label>
			<label class="tgl">
				<input type="checkbox" class="checkbox_class" name="project_element[]" value="<?php the_title(); ?>|<?php echo get_post_meta(get_the_ID(),'artwork_url',true); ?>">
				<span data-on="Selected" data-off="Deselected"></span>
			</label>
			<a class="view_example" target="_blank" href="<?php echo get_post_meta(get_the_ID(),'example_url',true); ?>">View Example</a>
		</div>
		<?php
	}
}
echo '</div>';
