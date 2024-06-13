<?php

$user_id = get_current_user_id();
$company_name = get_user_meta($user_id, 'company_name', true);
$company_logo = get_user_meta($user_id, 'company_logo', true);
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$badge_value_total = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1961, 'user_id' => 'current'));
$c_in_org_full_name = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1945, 'user_id' => 'current'));
$c_in_org_phone = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1946, 'user_id' => 'current'));
$c_in_org_function = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1948, 'user_id' => 'current'));
$c_in_org_email = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1947, 'user_id' => 'current'));

$employee_survey_enabled = get_user_meta($user_id, 'employee_survey_enabled', true);
$employer_survey_link = get_field('employer_survey_link');

$margin = get_field_object('margin');
$padding = get_field_object('padding');

$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

$class = 'il_block user-profile';
if ( ! empty( $block['className'] ) ) {
    $class .= ' ' . $block['className'];
}
if ( ! empty( $margin ) ) {
    $class .=  ' ' . $margin['value'];
}

if ( ! empty( $padding) ) {
    $class .=  ' ' . $padding['value'];
}

$form_id = get_user_meta($user_id, 'employee_survey_id', true);;

// Get all entries for the specified form
$entries = FrmEntry::getAll(array('form_id' => $form_id));

$fields = FrmField::get_all_for_form( $form_id, '', 'include' );

// Filter fields based on type (radio or checkbox)
$filteredFields = array_filter($fields, function ($field) {
	return $field->type === 'radio' || $field->type === 'checkbox';
});

$field_ids = [];
// Extract 'id' values from the filtered fields and add to field_ids array
$filteredIds = array_map(function ($field) use (&$field_ids) {
	$field_ids[] = $field->id;
	return $field->id;
}, $filteredFields);

// Create a comma-separated string of field IDs
$fields_string = implode(',', $filteredIds);

// Assuming $entries is the array of form entries
$total_entries = count($entries);
if ( $field_ids ) {
	$total_caregivers = do_shortcode('[frm-stats id=' . $field_ids[0] . ' type=count ' . $field_ids[0] . '="כן"]');
}

?>
<div <?php echo $anchor; ?> class="<?php echo $class ?>">
<?php get_template_part('components/background'); ?>
<div class="container profile-container">
<?php get_template_part('components/intro'); ?>

	<h1 class="ud-main-h1">איזור מעסיק</h1>

	<div class="ud-width-1_4 ud-company-logo">
		<?php if ( $company_logo) {
			function convertArrayToString($var) {
				if (is_array($var)) {
					// If $company_logo is an array, convert it to a string
					$convertedString = implode(',', $var);
					return $convertedString;
				} else {
					// If $company_logo is not an array, return it as is
					return $var;
				}
			}
			echo wp_get_attachment_image( convertArrayToString($company_logo), 'full', "" );
		} else {
			echo do_shortcode('[formidable id=164]');
		} ?>
		<?php if ( $company_name ) { ?>
			<p class="ud-company-name"><?php echo $company_name; ?></p>
		<?php } ?>
	</div>

	<div class="ud-width-3_4 ud-main-info">
		<div class="ud-main-info-col-1">
			<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
			<dotlottie-player src="https://lottie.host/4125c8cf-95d0-4db5-b6ce-813f33a3f2b2/G9H6cmPF4i.json" background="transparent" speed="1" class="ud-main-info-lottie" loop autoplay></dotlottie-player>
		</div>
		<div class="ud-main-info-col-2">
			<p class="ud-org-details"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">  <circle cx="9" cy="9.521" r="9" fill="#F9B142"/></svg> פרטי ארגון</p>
			<p class="ud-welcome-full-name"><span style="color: var(--color-1);">שלום</span> <?php echo $last_name . ' ' . $first_name; ?></p>
			<?php if ( ! $badge_value_total ) { ?><p class="ud-main-info-last-p">אנו שמחים על הצטרפותך! על מנת להפוך לארגון ידידותי לעובדים-בני משפחה מטפלים, יש למלא את השאלון המצורף. בהצלחה!</p><?php } ?>
			<?php if ( $badge_value_total ) { ?><p class="ud-main-info-last-p">באזור המעסיק תוכל לערוך את האמנה הארגונית, להוריד את התג הארגוני ולמפות את בני המשפחה המטפלים בארגון.</p><?php } ?>
			<?php if ( ! $badge_value_total ) { ?><a class="il_btn  button-color-pink button-hover-color-blue" href="<?php echo $employer_survey_link; ?>" target="_self"><img src="/wp-content/uploads/2024/01/ICON.svg"> צור אמנה</a><?php } ?>
		</div>
	</div>

	<div class="ud-width-1_4 ud-badge-section <?php if ( ! $badge_value_total ) { echo 'ud-blurred'; } ?>">
		<div class="ud-badge-top"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26" fill="none"><path d="M18.125 11.75C21.922 11.75 25 14.828 25 18.625C25 22.422 21.922 25.5 18.125 25.5C14.328 25.5 11.25 22.422 11.25 18.625C11.25 14.828 14.328 11.75 18.125 11.75ZM18.4375 0.5C20.6811 0.5 22.5 2.31884 22.5 4.5625L22.5015 11.7782C21.9222 11.4073 21.2928 11.1076 20.6255 10.892L20.625 7.375H1.875V18.9375C1.875 20.1456 2.85438 21.125 4.0625 21.125L10.392 21.1255C10.6076 21.7928 10.9073 22.4222 11.2782 23.0015L4.0625 23C1.81884 23 0 21.1811 0 18.9375V4.5625C0 2.31884 1.81884 0.5 4.0625 0.5H18.4375ZM17.48 14.3069L17.4307 14.4301L16.7345 16.6639H14.4814C13.8159 16.6639 13.5162 17.4994 13.9602 17.9589L14.0522 18.0404L15.8751 19.421L15.1789 21.6547C14.9725 22.3169 15.6476 22.8736 16.2051 22.5685L16.3021 22.5055L18.125 21.125L19.9479 22.5055C20.4881 22.9148 21.2048 22.4175 21.0986 21.7704L21.0711 21.6547L20.3749 19.421L22.1978 18.0404C22.7361 17.6326 22.5074 16.7731 21.8891 16.6734L21.7686 16.6639H19.5155L18.8193 14.4301C18.6144 13.7729 17.7654 13.7317 17.48 14.3069ZM18.4375 2.375H4.0625C2.85438 2.375 1.875 3.35438 1.875 4.5625V5.5H20.625V4.5625C20.625 3.35438 19.6456 2.375 18.4375 2.375Z" fill="white"/></svg> ארגונך מוכר כ:</div>
		<?php if ( ! $badge_value_total ) { ?><img src="/wp-content/uploads/2024/01/BADGE-Empowring.png" class="ud-badge-img"><?php } ?>
		<?php if ( $badge_value_total > 0 && $badge_value_total < 25 ) { ?><img src="/wp-content/uploads/2024/02/Supportive-1.png" class="ud-badge-img"><?php } ?>
		<?php if ( $badge_value_total >= 25 && $badge_value_total < 500  ) { ?><img src="/wp-content/uploads/2024/02/Empowring-1.png" class="ud-badge-img"><?php } ?>
		<?php if ( $badge_value_total >= 500  ) { ?><img src="/wp-content/uploads/2024/02/Ambasador-1.png" class="ud-badge-img"><?php } ?>
	</div>

	<div class="ud-width-1_4 ud-dl-badges <?php if ( ! $badge_value_total ) { echo 'ud-blurred'; } ?>">
		<div class="ud-dl-badges-top"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none"><path d="M14.9743 11.0335H9.50884C8.19028 11.0335 7.11768 9.96087 7.11768 8.64232V1.81043C7.11768 1.24475 7.57678 0.785645 8.14246 0.785645H16.3407C16.9064 0.785645 17.3655 1.24475 17.3655 1.81043V8.64232C17.3655 9.96087 16.2929 11.0335 14.9743 11.0335ZM9.16724 2.83521V8.64232C9.16724 8.83088 9.32028 8.98391 9.50884 8.98391H14.9743C15.1629 8.98391 15.3159 8.83088 15.3159 8.64232V2.83521H9.16724Z" fill="white"/><path d="M20.4397 0.785645H4.04318C1.97039 0.785645 0.285645 2.47039 0.285645 4.54318V20.9397C0.285645 23.0125 1.97039 24.6973 4.04318 24.6973H20.4397C22.5125 24.6973 24.1973 23.0125 24.1973 20.9397V4.54318C24.1973 2.47039 22.5125 0.785645 20.4397 0.785645ZM22.1477 20.9397C22.1477 21.8811 21.3811 22.6477 20.4397 22.6477H4.04318C3.10175 22.6477 2.33521 21.8811 2.33521 20.9397V4.54318C2.33521 3.60175 3.10175 2.83521 4.04318 2.83521H20.4397C21.3811 2.83521 22.1477 3.60175 22.1477 4.54318V20.9397ZM10.5335 18.207C10.5335 18.7726 10.0744 19.2317 9.50869 19.2317H6.77594C6.21026 19.2317 5.75115 18.7726 5.75115 18.207C5.75115 17.6413 6.21026 17.1822 6.77594 17.1822H9.50869C10.0744 17.1822 10.5335 17.6413 10.5335 18.207Z" fill="white"/></svg> תג תקן</div>
		<p>לנוחיותכם, באפשרותכם להוריד את חבילת קבצי התג הארגוני בפורמטים שונים לשימוש והטמעה לפי צורך.</p>
		<?php if ( ! $badge_value_total ) { ?><a class="il_btn" href="#" target="_self">לצפייה במדיניות הארגון <img src="/wp-content/uploads/2024/01/dl-button-icon.png"></a><?php } ?>
		<?php if ( $badge_value_total > 0 && $badge_value_total < 25 ) { ?><a class="il_btn" href="/wp-content/uploads/2024/06/Supportive.zip" target="_self">להורדת קבצים - תג ארגוני <img src="/wp-content/uploads/2024/01/dl-button-icon.png"></a><?php } ?>
		<?php if ( $badge_value_total >= 25 && $badge_value_total < 500  ) { ?><a class="il_btn" href="/wp-content/uploads/2024/06/Empowering.zip" target="_self">להורדת קבצים - תג ארגוני <img src="/wp-content/uploads/2024/01/dl-button-icon.png"></a><?php } ?>
		<?php if ( $badge_value_total >= 500  ) { ?><a class="il_btn" href="/wp-content/uploads/2024/06/Ambassador.zip" target="_self">להורדת קבצים - תג ארגוני <img src="/wp-content/uploads/2024/01/dl-button-icon.png"></a><?php } ?>
	</div>

	<div class="ud-width-2_4 ud-corp-charter <?php if ( ! $badge_value_total ) { echo 'ud-blurred'; } ?>">
		<div class="ud-corp-charter-top"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none"><path d="M15.2638 10.9502L11.5124 14.4934L9.71205 12.6412C9.51246 12.4357 9.18381 12.431 8.97853 12.6306C8.77305 12.8304 8.76837 13.1589 8.96796 13.3643L11.1273 15.581C11.3279 15.7828 11.6521 15.7897 11.861 15.5964L15.9871 11.7046C16.0873 11.6101 16.1459 11.4796 16.1497 11.342C16.1536 11.2044 16.1026 11.0709 16.0079 10.9709C15.8071 10.7621 15.476 10.753 15.2638 10.9502Z" fill="white" stroke="white" stroke-width="0.5"/><path d="M12.48 5.49707C8.19266 5.49707 4.70654 8.98319 4.70654 13.2705C4.70654 17.558 8.19266 21.0442 12.48 21.0442C16.7675 21.0442 20.2536 17.558 20.2536 13.2705C20.2536 8.98319 16.7675 5.49707 12.48 5.49707ZM12.48 20.0035C8.76501 20.0035 5.74718 16.9804 5.74718 13.2705C5.74718 9.56062 8.76501 6.5377 12.48 6.5377C16.1986 6.5377 19.213 9.55208 19.213 13.2705C19.213 16.9889 16.1986 20.0035 12.48 20.0035Z" fill="white" stroke="white" stroke-width="0.5"/><path d="M23.1775 10.4089L23.3492 7.32339C23.3612 7.11628 23.2486 6.92177 23.0631 6.82909L20.3158 5.43988L18.9266 2.69257C18.832 2.50863 18.6388 2.39664 18.4323 2.4064L15.3415 2.57306L12.7659 0.871466C12.5923 0.757037 12.3673 0.757037 12.1935 0.871466L9.61797 2.57306L6.53266 2.40132C6.32535 2.38912 6.13104 2.50172 6.03836 2.68749L4.64895 5.4348L1.90185 6.82401C1.7177 6.91831 1.60592 7.1116 1.61567 7.31831L1.78722 10.4038L0.0858217 12.9794C-0.0286072 13.153 -0.0286072 13.378 0.0858217 13.5517L1.78213 16.1324L1.61039 19.2179C1.5984 19.425 1.71079 19.6195 1.89656 19.7122L4.64387 21.1014L6.03308 23.8487C6.12738 24.0329 6.32067 24.1446 6.52738 24.1349L9.61289 23.9631L12.1885 25.6645C12.361 25.7826 12.5882 25.7826 12.7608 25.6645L15.3364 23.9631L18.4219 24.1349C18.629 24.1469 18.8233 24.0343 18.9162 23.8487L20.3054 21.1014L23.0527 19.7122C23.2366 19.6177 23.3486 19.4246 23.3389 19.2179L23.1671 16.1324L24.8685 13.5568C24.983 13.3832 24.983 13.158 24.8685 12.9845L23.1775 10.4089ZM22.2096 15.7058C22.1462 15.7989 22.1167 15.9108 22.1265 16.0232L22.2877 18.9421L19.6914 20.2532C19.5924 20.3028 19.5121 20.3833 19.4625 20.4823L18.1512 23.0786L15.2323 22.9174C15.1205 22.9134 15.0102 22.9422 14.9148 23.0006L12.4797 24.6085L10.0448 23.0006C9.96024 22.9434 9.86065 22.9125 9.75862 22.9121H9.73261L6.81355 23.0735L5.50239 20.477C5.4528 20.378 5.37252 20.2978 5.27333 20.2482L2.67175 18.9421L2.83313 16.0232C2.8372 15.9115 2.80813 15.8011 2.7498 15.7058L1.1421 13.2706L2.7498 10.8355C2.81342 10.7426 2.84289 10.6304 2.83313 10.5183L2.67175 7.5992L5.26825 6.28804C5.36723 6.23845 5.44752 6.15816 5.49711 6.05918L6.80827 3.46268L9.72732 3.62406C9.83891 3.62792 9.94927 3.59906 10.0448 3.54073L12.4797 1.93303L14.9148 3.54073C15.0079 3.60435 15.1201 3.63382 15.2323 3.62406L18.1512 3.46268L19.4625 6.05918C19.5121 6.15816 19.5924 6.23845 19.6914 6.28804L22.2877 7.5992L22.1265 10.5183C22.1224 10.6298 22.1515 10.7402 22.2096 10.8355L23.8175 13.2706L22.2096 15.7058Z" fill="white" stroke="white" stroke-width="0.5"/></svg> אמנה ארגונית</div>
		<p>האמנה הארגונית מכילה את מדיניות הארגון כלפי עובדים-בני משפחה מטפלים - כאן תוכלו להוריד את קובץ האמנה, לשדרג ולערוך  אותה בכל שלב.</p>
		<div class="ud-corp-charter-buttons">
			<a class="il_edit_charter_btn" href="<?php echo $employer_survey_link; ?>" target="_self"><img src="/wp-content/uploads/2024/01/edit-charter-icon-1.png">ערוך אמנה</a>
		
			<?php if ( ! $badge_value_total ) { ?><a class="il_btn" href="#" target="_self">קיט פרסומי <img src="/wp-content/uploads/2024/01/dl-button-icon.png"></a><?php } ?>
			<?php if ( $badge_value_total ) { ?><a class="il_btn" href="/wp-content/uploads/2024/04/WorCare_Kit.zip" target="_blank">קיט פרסומי <img src="/wp-content/uploads/2024/01/dl-button-icon.png"></a><?php } ?>

			<?php if ( ! $badge_value_total ) { ?><a class="il_btn" href="#" target="_self">לצפייה במדיניות הארגון <img src="/wp-content/uploads/2024/01/dl-button-icon.png"></a><?php } ?>
			<?php if ( $badge_value_total ) { ?><a class="il_btn" href="[e2pdf-save id='3' dataset='1895' output='url' download='true' overwrite='true' apply='true']" target="_blank">לצפייה במדיניות הארגון <img src="/wp-content/uploads/2024/01/dl-button-icon.png"></a><?php } ?>
		</div>
	</div>

	<div class="ud-width-2_4 ud-contact-in-org <?php if ( ! $badge_value_total ) { echo 'ud-blurred'; } ?>">
		<div class="ud-contact-in-org-main-cont">
			<a class="il_edit_password_btn" href="#" target="_self"><img src="/wp-content/uploads/2024/01/edit-charter-icon-1.png">עריכת סיסמא</a>

			<p class="ud-contact-in-org-title"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">  <circle cx="9" cy="9.521" r="9" fill="#F9B142"/></svg> איש קשר בארגונך</p>
		</div>
		<div class="ud-contact-in-org-info-cont">
			<div><p><strong>תפקיד: </strong><?php echo !empty($c_in_org_function) ? $c_in_org_function : 'מנהל משאבי אנוש'; ?></p></div>
			<div><p><strong>שם מלא: </strong><?php echo !empty($c_in_org_full_name) ? $c_in_org_full_name : 'John Doe'; ?></p></div>
		</div>
		<div class="ud-contact-in-org-info-cont">
			<div><p><strong>אימייל: </strong><?php echo !empty($c_in_org_email) ? $c_in_org_email : 'HR@astra.com'; ?></p></div>
			<div><p><strong>טלפון: </strong><?php echo !empty($c_in_org_phone) ? $c_in_org_phone : '054-5699452'; ?></p></div>
		</div>
	</div>

	<div class="ud-width-2_4 ud-emp-q-status <?php if ( ! $badge_value_total ) { echo 'ud-blurred'; } ?>" <?php if ( ( ! $employee_survey_enabled && $badge_value_total ) || ( $employee_survey_enabled && $badge_value_total && $total_entries == 0 ) ) { echo 'id="ud-zero-entries"'; } ?>>
		<p class="ud-emp-q-status-title"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">  <circle cx="9" cy="9.521" r="9" fill="#F9B142"/></svg> סטאטוס שאלון עובדים</p>
		
		<div class="ud-emp-q-status-title-info-cont">
			<div><img src="/wp-content/uploads/2024/01/total-entries-icon.png"><p>סך כניסות <br><strong><?php echo !empty($total_entries) ? $total_entries : '3,6k'; ?></strong></p></div>
			<div><img src="/wp-content/uploads/2024/01/total-caregivers-icon.png"><p>מספר Cargivers <br><strong><?php echo !empty($total_entries) ? $total_caregivers : '3,6k'; ?></strong></p></div>
			<a class="il_btn button-color-pink button-hover-color-blue" href="#graphs-container" target="_self">תוצאות הסקר <img src="/wp-content/uploads/2024/01/g810.png"></a>
		</div>
	</div>

	<div class="ud-width-4_4 ud-knowledge-area <?php if ( ! $badge_value_total ) { echo 'ud-blurred'; } ?>">
		<div class="ud-knowledge-area-col-1">
			<div class="il_inner_posts_container">
				<?php
					$args = array(
						'post_type'      => 'post',
						'post_status'    => 'publish',
						'posts_per_page' => 2,
						'category_name'  => 'user-dashboard-posts',
					);
					
					$posts = new WP_Query( $args );
					
					if ( $posts->have_posts() ) :
					
						while ( $posts->have_posts() ) :
							$posts->the_post(); 

							$external_link = get_field('external_link', get_the_ID());
							?>

							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<a href="<?php echo $external_link; ?>" target="_blank">
									<?php
										the_post_thumbnail( array(508, 250) );
									?>
									<div class="article-container">
										<header class="entry-header">
											<h3 class="entry-title"><?php the_title(); ?></h3>
										</header>
											<span class="entry_btn">
												<svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none"><path d="M0.156197 5.44511L4.53141 0.947592C4.63261 0.843486 4.76769 0.786133 4.91173 0.786133C5.05577 0.786133 5.19085 0.843486 5.29204 0.947592L5.61425 1.27873C5.82391 1.4945 5.82391 1.84519 5.61425 2.06063L1.94027 5.83738L5.61832 9.61831C5.71952 9.72242 5.77539 9.8612 5.77539 10.0092C5.77539 10.1573 5.71952 10.2961 5.61832 10.4003L5.29612 10.7313C5.19485 10.8355 5.05984 10.8928 4.91581 10.8928C4.77177 10.8928 4.63668 10.8355 4.53549 10.7313L0.156197 6.22973C0.0547638 6.12529 -0.000948429 5.98585 -0.000628471 5.83762C-0.000948429 5.68882 0.0547638 5.54946 0.156197 5.44511Z" fill="#002D69"/></svg>
												<?php echo 'לקריאה נוספת'; ?>
											</span>
									</div>
								</a>
							</article>
							<?php
						endwhile;
						wp_reset_query();
					endif;
				?>
			</div>
		</div>
		<div class="ud-knowledge-area-col-2">
			<p class="ud-knowledge-area-title"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">  <circle cx="9" cy="9.521" r="9" fill="#979CE8"/></svg> איזור ידע</p>
			<p class="ud-knowledge-area-text">ריכזנו לך כאן את כל הכתבות והידיעות אודות עובדים-בני משפחה מטפלים, כיצד מתמודדים עם טיפול בבן משפחה ובו זמנית מנהלים משק בית וקריירה ומהם הכלים המסייעים להקל במצב ולהפחית את הלחץ.</p>
			<a class="il_btn button-color-pink" href="https://caregivers.org.il/library/" target="_blank">לתכנים נוספים <img src="/wp-content/uploads/2024/01/direct-EDIT-1.png"></a>
		</div>
	</div>

	<?php if( $employee_survey_enabled ) { ?>
		<div class="ud-survey-results-top-buttons">
			<?php if( $total_entries > 0 ) { ?><a class="il_btn" href="/wp-admin/admin-ajax.php?frm_action=0&action=frm_entries_csv&form=<?php echo $form_id; ?>" target="_self">להורדת נתונים ב-CSV <img src="/wp-content/uploads/2024/01/dl-button-icon-small.png"></a><?php } ?>
				<?php
					$formatted_company_name = strtolower($company_name);
					$formatted_company_name = str_replace(" ", "-", $formatted_company_name);
				?>
			<a class="il_copy_emp_survey_link" id="copyEmpSurveyLink" href="<?php echo '/' . $formatted_company_name . '-employee-survey'; ?>" target="_self">העתקת לינק לשאלון עובדים <img src="/wp-content/uploads/2024/01/direct-EDIT-2.png"></a>

			<?php if( $total_entries > 0 ) { ?><p class="ud-survey-results-title"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">  <circle cx="9" cy="9.521" r="9" fill="#D84379"/></svg> תוצאות השאלון</p><?php } ?>
		</div>
	<?php } ?>

	<?php if( $employee_survey_enabled && $total_entries > 0 ) { ?>
		<div id="graphs-container" class="graphs-container">
			<div class="single-graph-container">
				<h3>אני מטפל ומסייע ל:</h3>
				<?php echo do_shortcode('[frm-graph fields="' . $field_ids[1] . '" width="100%" chart_area="width:90%, height:60%" type="pie" pie_hole=".4" title="" show_key="1" legend_position="bottom" legend_size="14" colors="#6C25B8, #A456F7, #2F51CA, #7EA9E9, #1386EF, #5385F7, #D030CC, #ED6095"]'); ?>
			</div>
			
			<div class="single-graph-container">
				<h3>כמה שעות בשבוע הינך מקדיש לסיוע לקרוב משפחתך</h3>
				<?php echo do_shortcode('[frm-graph fields="' . $field_ids[4] . '" width="100%" chart_area="width:90%, height:60%" type="column" title="" colors="#5385F7, #1386EF, #7EA9E9, #2F51CA, #0C1E5B"]'); ?>
			</div>
			
			<div class="single-graph-container">
				<div class="single-graph-inner">
					<h3>ניקוד</h3>
					<h3>קרוב המשפחה שלי מתמודד עם:</h3>
				</div>
				<?php echo do_shortcode('[frm-graph id=' . $field_ids[2] . ' type=table width="100%"]'); ?>
			</div>
			
			<div class="single-graph-container">
				<div class="single-graph-inner">
					<h3>ניקוד</h3>
					<h3>אני מטפל ומסייע לקרוב משפחתי בפעולות הבאות:</h3>
				</div>
				<?php echo do_shortcode('[frm-graph id=' . $field_ids[3] . ' type=table width="100%"]'); ?>
			</div>
			
			<div class="single-graph-container">
				<div class="single-graph-inner">
					<h3>ניקוד</h3>
					<h3>האם בשנה האחרונה, בעקבות הסיוע שהנך מעניק לקרוב משפחתך, קרה ש:</h3>
				</div>
				<?php echo do_shortcode('[frm-graph id=' . $field_ids[5] . ' type=table width="100%"]'); ?>
			</div>
			
			<div class="single-graph-container">
				<div class="single-graph-inner">
					<h3>ניקוד</h3>
					<h3>באיזו מידה העומס המוטל עליך בעקבות הסיוע לקרוב משפחתך מקשה עליך?</h3>
				</div>
				<?php echo do_shortcode('[frm-graph id=' . $field_ids[8] . ' type=table width="100%"]'); ?>
			</div>
			
			<div class="single-graph-container">
				<div class="single-graph-inner">
					<h3>ניקוד</h3>
					<h3>באילו תחומים היית מעוניין לקבל סיוע במטרה להקל על תפקידך כעובד-בן משפחה מטפל?</h3>
				</div>
				<?php echo do_shortcode('[frm-graph id=' . $field_ids[11] . ' type=table width="100%"]'); ?>
			</div>
			
			<div class="single-graph-container">
				<div class="single-graph-inner">
					<h3>ניקוד</h3>
					<h3>אנא ציין באיזו דרך היית מעוניין לקבל סיוע כעובד-בן משפחה מטפל:</h3>
				</div>
				<?php echo do_shortcode('[frm-graph id=' . $field_ids[12] . ' type=table width="100%"]'); ?>
			</div>
		</div>
	<?php } ?>
	<div id="triggerTextMessage">על מנת לקבל גישה לאמנה הארגונית, תו תקן, חבילת תקשורת שיווקית וסקר העובדים עליך ראשית להשלים את יצירת האמנה הארגונית</div>
	<div id="ud-zero-entries-message">איזור זה יפתח לצפיה לאחר מילוי השאלון על ידי העובדים</div>
	<div id="copyEmpSurveyLinkMessage">הקישור הועתק ללוח</div>
</div>

<div class="ud-password-change-form">
	<div class="ud-password-change-form-inner">
		<p class="ud-password-change-form-title"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">  <circle cx="9" cy="9.521" r="9" fill="#F9B142"/></svg> עדכון סיסמא</p>
		<div class="ud-password-change-form-main">[formidable id=5]</div>
	</div>
</div>
</div>
