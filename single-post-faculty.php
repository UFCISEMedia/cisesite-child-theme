<?php
/**
 * Template Name: Faculty Single Page
 * Template Post Type: post
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package HWCOE_UFL
 */
get_header(); ?>

<div id="main" class="container main-content">
<div class="row">
  <div class="col-sm-8 faculty_details">
	  <div class="row">
		<div class="col-md-3">
			<?php 
				$image    = get_field( 'faculty_portrait' );
				$alt      = $image['alt'];
				$img_src  = $image['sizes']['large'];
			?>
			<p><img src="<?php echo $img_src; ?>" alt="<?php echo $alt; ?>" class="img-full"></p>
		</div>
		<div class="col-md-9">
			<?php 
				if(get_field('faculty_title')){ //if the field is not empty
					echo '<h1>' . get_field('faculty_title') . '</h1>'; //display it
				} 

				if(get_field('faculty_job_title')){ //if the field is not empty
					echo '<p><em>' . get_field('faculty_job_title') . '</em></p>'; //display it
				} 
			?>    
		</div>
	  </div>
	  <div class="row">
		  <div class="col-md-12">
			  <?php 
					if(get_field('faculty_bio')){ //if the field is not empty
						echo '<h3>Bio</h3>' . get_field('faculty_bio'); //display it
					} 
			  
			  		if(get_field('faculty_primary_research_area')){ //if the field is not empty
						echo '<h3>Primary Research Area</h3><p>' . get_field('faculty_primary_research_area') . '</p>'; //display it
					} 

			  		//Research areas needs these three units because it is a list of selections
					if(get_field('faculty_research_areas')){ //if the field is not empty
						echo '<h3>Research Areas</h3><p>'; //display it
					} 
			  
					if(the_field('faculty_research_areas')){ //if the field is not empty
						echo the_field('faculty_research_areas'); //display it
					} 
			  		if(get_field('faculty_research_areas')){ //if the field is not empty
						echo '</p>'; //display it
					} 
			  
					if(get_field('faculty_current_courses')){ //if the field is not empty
						echo '<h3>Current Courses</h3><p>' . get_field('faculty_current_courses') . '</p>'; //display it
					} 

					if(get_field('faculty_education')){ //if the field is not empty
						echo '<h3>Education</h3><p>' . get_field('faculty_education') . '</p>'; //display it
					} 

					if(get_field('faculty_research_interests')){ //if the field is not empty
						echo '<h3>Research Interests</h3><p>' . get_field('faculty_research_interests') . '</p>'; //display it
					} 

					if(get_field('faculty_publications')){ //if the field is not empty
						echo '<h3>Publications</h3>' . get_field('faculty_publications'); //display it
					} 

					if(get_field('faculty_awards')){ //if the field is not empty
						echo '<h3>Awards &amp; Distinctions</h3>' . get_field('faculty_awards'); //display it
					} 
				?>
		  </div>
	  </div>
  </div>
  <div class="col-md-4 faculty_contact_information">
	  <h3>Contact Information</h3>
	  	<?php 
	  		if(get_field('faculty_telephone')){ //if the field is not empty
        		echo '<p><strong>Telephone:</strong> ' . get_field('faculty_telephone') . '</p>'; //display it
			} 
	  	 
	  		if(get_field('faculty_fax')){ //if the field is not empty
        		echo '<p><strong>Fax:</strong> ' . get_field('faculty_fax') . '</p>'; //display it
			} 

	  		if(get_field('faculty_email')){ //if the field is not empty
        		echo '<p><strong>Email:</strong> ' . get_field('faculty_email') . '</p>'; //display it
			} 

	  		if(get_field('faculty_website')){ //if the field is not empty
        		echo '<p><strong>Website:</strong> ' . get_field('faculty_website') . '</p>'; //display it
			} 

	  		if(get_field('faculty_office')){ //if the field is not empty
        		echo '<p><strong>Office:</strong> ' . get_field('faculty_office') . '</p>'; //display it
			} 

	  		if(get_field('faculty_lab_room')){ //if the field is not empty
        		echo '<p><strong>Lab Room:</strong> ' . get_field('faculty_lab_room') . '</p>'; //display it
			} 

	  		if(get_field('faculty_mailing_address')){ //if the field is not empty
        		echo '<p><strong>Mailing Address:</strong></p> <p>' . get_field('faculty_mailing_address') . '</p>'; //display it
			} 
	  	?>
  </div>
</div>
</div>

<?php get_footer(); ?>
