<?php

use Carbon_Fields\Container;
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Container::make( 'post_meta', __('SEO Settings', 'patriot-towing') )
    ->show_on_post_type('landing_page')
    ->add_tab(
        __('Hero', 'patriot-towing'),
        array(
            Field::make( 'text', 'hero_section_heading', __('Heading', 'patriot-towing') ),
			Field::make( 'radio_image', 'hero_section_bg', 'Hero Image' )
				->add_options( array(
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-hero.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-hero.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-dispatcher-hero.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-dispatcher-hero.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/01/whiterail-recruits-carpenter-hero.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/01/whiterail-recruits-carpenter-hero.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-barber-hero.jpeg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-barber-hero.jpeg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-mechanic-hero.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-mechanic-hero.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-battery-tech.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-battery-tech.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/04/whiterail-recruits-jiffy-lube-hero.jpeg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/04/whiterail-recruits-jiffy-lube-hero.jpeg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/06/whiterail-recruits-jiffy-lube-hero2.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/06/whiterail-recruits-jiffy-lube-hero2.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/06/whiterail-recruits-mover-hero.jpeg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/06/whiterail-recruits-mover-hero.jpeg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/06/whiterail-recruits-auto-tech-hero.jpeg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/06/whiterail-recruits-auto-tech-hero.jpeg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-roadside-tech.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-roadside-tech.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-aaa-roadside.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-aaa-roadside.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-heartland-hero.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-heartland-hero.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-heartland-hero-3.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-heartland-hero-3.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-heartland-hero-2.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-heartland-hero-2.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-aaa-roadside-2.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-aaa-roadside-2.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-finswash-hero.jpeg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-finswash-hero.jpeg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-flatbed-hero.jpeg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-flatbed-hero.jpeg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-movers-hero.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-movers-hero.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/09/whiterail-recruits-heavenly-crossroads-salon-spa-hero.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/09/whiterail-recruits-heavenly-crossroads-salon-spa-hero.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/09/whiterail-recruits-phoenix-hero-scaled.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/09/whiterail-recruits-phoenix-hero-scaled.jpg'
				) ),
        )
    )
    ->add_tab(
        __('Company', 'patriot-towing'),
        array(
            Field::make( 'text', 'company_name', __('Company Name', 'patriot-towing') )
				->set_default_value($site)
				->set_required(true),
			Field::make( 'image', 'company_logo', __('Company Logo', 'patriot-towing') )
				->set_width( 33.3 )
				->set_required(true),
			Field::make( 'color', 'company_color_main', __('Primary Company Color (optional)', 'patriot-towing') )
				->set_default_value('#E91A1A') 
				->set_width( 33.3 ),
			Field::make( 'color', 'company_color_secondary', __('Secondary Company Color (optional)', 'patriot-towing') )
				->set_default_value('#2196F3')
				->set_width( 33.3 ),
			Field::make( 'text', 'company_content_title', __('Heading', 'patriot-towing') ),
			Field::make( 'rich_text', 'company_content', __('Content', 'patriot-towing') )
				->set_rows(10),
			Field::make( 'complex', 'company_reviews', 'Testimonials' )
			->set_layout( 'tabbed-horizontal' )
			->add_fields( array(
				Field::make( 'text', 'review_title', 'Name' ),
				Field::make( 'text', 'review_desc', 'Review' ),
				Field::make( 'radio_image', 'review_img', 'Image' )
				->add_options( array(
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/Frank_bw.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/Frank_bw.png',
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/Joseph_bw.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/Joseph_bw.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/joseph-150x150.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/joseph-150x150.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/frank-150x150.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/frank-150x150.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/Becky-150x150.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/Becky-150x150.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/David-150x150.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/David-150x150.jpg',
				) ),
			) ),
        )
    )
	->add_tab(
        __('Benefits', 'patriot-towing'),
        array(
            Field::make( 'text', 'benefits_content_title', __('Heading', 'patriot-towing') )
				->set_default_value('Benefits'),
			Field::make( 'complex', 'benefits_content', 'Benefits' )
			->set_layout( 'tabbed-horizontal' )
			->add_fields( array(
				Field::make( 'text', 'benefits_title', 'Title' ),
				Field::make( 'text', 'benefits_desc', 'Description' ),
				Field::make( 'radio_image', 'benefits_icon', 'Icon' )
				->add_options( array(
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-flexible-hours.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-flexible-hours.png',
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-retirement-plan.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-retirement-plan.png',
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-stability.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-stability.png',
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-career-advancement.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-career-advancement.png',
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-insurance.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-insurance.png',
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-quality.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-quality.png',
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-tow-truck.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-tow-truck.png',
					'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-money.png' => 'https://whiterailrecruits.com/wp-content/uploads/2021/10/whiterail-recruits-money.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/doctor.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/doctor.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/life-insurance.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/life-insurance.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/piggy-bank.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/piggy-bank.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-sick-leave.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-sick-leave.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/calendar-3.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/calendar-3.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-dispatcher.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-dispatcher.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-sign-on-bonus.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-sign-on-bonus.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-dental-insurance.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-dental-insurance.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/11/whiterail-recruits-bonus.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/11/whiterail-recruits-bonus.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/11/whiterail-recruits-uniform.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/11/whiterail-recruits-uniform.png',
					'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-paid-vacation.png' => 'https://whiterailrecruits.com/wp-content/uploads/2023/10/whiterail-recruits-paid-vacation.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-vision-insurance.jpg' => 'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-vision-insurance.jpg',
					'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-house.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-house.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-car.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/02/whiterail-recruits-car.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-education.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-education.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-membership-card.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-membership-card.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-retirement.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-retirement.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-medical-insurance.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-medical-insurance.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-vacation.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-vacation.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-reward.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-reward.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-balance.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-balance.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-wall-clock.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-wall-clock.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-incentive.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-incentive.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-sunbed.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-sunbed.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-disability.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-disability.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiteail-recruits-vision.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiteail-recruits-vision.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-maintenance.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-maintenance.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-holiday.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-holiday.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-bars.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-bars.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-pension.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-pension.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-tools.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-tools.png',
					'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-insurance-2.png' => 'https://whiterailrecruits.com/wp-content/uploads/2024/07/whiterail-recruits-insurance-2.png'
				) ),
			) ),
			Field::make( 'image', 'benefits_content_img', __('Image', 'patriot-towing') ),
        )
	)
	->add_tab(
        __('More', 'patriot-towing'),
        array(
            Field::make( 'text', 'more_content_title', __('Heading', 'patriot-towing') )
				->set_default_value('1 Minute Application'),
            Field::make( 'rich_text', 'more_content', __('Content', 'patriot-towing') )
				->set_rows(10)
				->set_default_value('You read that right! We\'ve made the application process as smooth as possible for you! All you need to do is answer a few questions and our management team will contact you if they\'d like to set up an interview. The whole application process takes only one minute!<br>What are you waiting for? Apply now!'),
			Field::make( 'image', 'more_content_img', __('Image', 'patriot-towing') ),
        )
    )
	->add_tab(
        __('Apply Now', 'patriot-towing'),
        array(
            Field::make( 'text', 'recruits_url', __('Apply Now URL', 'patriot-towing') )
				->help_text('Enter URL linking applicant to the associated Recruits flow for this job.'),
        )
    )
;