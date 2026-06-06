<?php
namespace Enteraddons\AI;

/**
 * Enteraddons ai
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */


class AI_Slash_Commands {

    public $prompt = '';

	public function get_slash_commands( $prompt ) {
        $this->prompt = $prompt;
        return $this->slash_commands_mapping();
    }

    public function section_commands_list() {

        return [

            [ 'command' => '/hero', 'label' => 'Create a modern hero section with heading, subheading, button, and background image or gradient. Center aligned and visually strong.'],

            ['command' => '/hero-modern-saas','label' => 'Create a modern SaaS hero section with bold heading, subheading, CTA button, and gradient background. Clean and centered.'],

            ['command' => '/hero-minimal-agency', 'label' => 'Create a minimal agency hero section with large typography, subtle background, and a call-to-action button.'],

            ['command' => '/hero-dark-startup', 'label' => 'Create a dark style startup hero section with glowing gradient, bold headline, and primary CTA.'],

            [ 'command' => '/banner', 'label' => 'Create a banner section with bold heading, short description, and call-to-action button.'],
            
            [ 'command' => '/intro' , 'label' => 'Create an intro section with heading and short paragraph introducing the business.'],
            
            [ 'command' => '/about', 'label' => 'Create an about section with heading, description, image, and 2-column layout.'],
            
            [ 'command' => '/services', 'label' => 'Create a services section with 3 service cards in 3 columns. Each card includes icon, title, description, and subtle shadow.'],
            
            [ 'command' => '/features', 'label' => 'Create a features section with 3 to 4 feature cards including icon, title, and short description.'],
            
            [ 'command' =>  '/why-us', 'label' => 'Create a why choose us section with heading and 3 highlighted reasons in columns.'],
            
            [ 'command' => '/portfolio', 'label' => 'Create a portfolio section with grid layout showing projects with image, title, and hover effect.'],
            
            [ 'command' => '/projects', 'label' => 'Create a projects showcase section with 3 to 6 items in a responsive grid.'],
            
            [ 'command' => '/gallery', 'label' => 'Create an image gallery section with grid or masonry layout.'],
            
            [ 'command' => '/team', 'label' => 'Create a team section with 3 team members including image, name, designation, and social icons.'],
            
            [ 'command' => '/testimonial', 'label' => 'Create a testimonial section with 3 client reviews in slider or card layout.'],
            
            [ 'command' => '/reviews', 'label' => 'Create a reviews section with customer feedback cards including rating and text.'],
            
            [ 'command' => '/pricing', 'label' => 'Create a pricing section with 3 pricing tables including title, price, features list, and button.'],
            
            [ 'command' => '/faq', 'label' => 'Create an FAQ section using accordion layout with 4 to 6 questions and answers.'],
            
            [ 'command' => '/contact', 'label' => 'Create a contact section with contact form, heading, and optional Google map.'],
            
            [ 'command' => '/newsletter', 'label' => 'Create a newsletter subscription section with heading, input field, and subscribe button.'],
            
            [ 'command' => '/blog', 'label' => 'Create a blog section showing recent posts in 3 column grid.'],
            
            [ 'command' => '/posts', 'label' => 'Create a posts grid section with latest articles including image, title, and excerpt.'],
            
            [ 'command' => '/cta', 'label' => 'Create a call-to-action section with strong heading, short text, and button.'],
            
            [ 'command' => '/call-to-action', 'label' => 'Create a visually strong call-to-action section with background color, heading, and button.'],
            
            [ 'command' => '/header', 'label' => 'Create a website header with logo, navigation menu, and optional button.'],
            
            [ 'command' => '/footer', 'label' => 'Create a footer section with multiple columns including links, contact info, and social icons.'],

            // SaaS Pages
            [
                'command' => '/page-modern-saas',
                'label' => 'Create a modern SaaS landing page with hero, features, services, testimonials, pricing, and call-to-action. Clean layout with gradient colors and strong typography.'
            ],
            [
                'command' => '/page-minimal-saas',
                'label' => 'Create a minimal SaaS landing page with simple hero, features, pricing, and CTA. Focus on whitespace and clean UI.'
            ],

            // Agency Pages
            [
                'command' => '/page-modern-agency',
                'label' => 'Create a modern agency website with hero, services, portfolio, team, testimonials, and contact section. Stylish cards and hover effects.'
            ],
            [
                'command' => '/page-creative-agency',
                'label' => 'Create a creative agency page with bold typography, colorful sections, portfolio showcase, and interactive elements.'
            ],

            // Startup Pages
            [
                'command' => '/page-startup-growth',
                'label' => 'Create a startup landing page focused on growth with hero, features, metrics, testimonials, and strong call-to-action sections.'
            ],
            [
                'command' => '/page-dark-startup',
                'label' => 'Create a dark themed startup landing page with glowing gradients, modern UI, features, pricing, and CTA.'
            ],

            // Business / Corporate
            [
                'command' => '/page-corporate-business',
                'label' => 'Create a corporate business website with hero, about section, services, team, testimonials, and contact form. Professional and clean.'
            ],
            [
                'command' => '/page-minimal-business',
                'label' => 'Create a minimal business page with simple sections, clean typography, and soft colors.'
            ],

            // E-commerce
            [
                'command' => '/page-modern-ecommerce',
                'label' => 'Create a modern ecommerce homepage with hero banner, product showcase, categories, featured products, testimonials, and CTA.'
            ],
            [
                'command' => '/page-fashion-store',
                'label' => 'Create a fashion store homepage with stylish hero, product grid, categories, and promotional sections.'
            ],

            // Portfolio
            [
                'command' => '/page-portfolio-modern',
                'label' => 'Create a modern portfolio page with hero, about, projects gallery, testimonials, and contact section.'
            ],
            [
                'command' => '/page-creative-portfolio',
                'label' => 'Create a creative portfolio with unique layout, animations, project showcase, and bold typography.'
            ],

            // Blog / Content
            [
                'command' => '/page-blog-modern',
                'label' => 'Create a modern blog homepage with hero, featured posts, post grid, categories, and newsletter section.'
            ],
            [
                'command' => '/page-magazine-style',
                'label' => 'Create a magazine style blog page with multiple post layouts, categories, and featured sections.'
            ],

            // Landing / Conversion
            [
                'command' => '/page-conversion-landing',
                'label' => 'Create a high-converting landing page with hero, benefits, features, testimonials, pricing, and strong CTA sections.'
            ],
            [
                'command' => '/page-product-launch',
                'label' => 'Create a product launch landing page with hero, product features, benefits, testimonials, pricing, and CTA.'
            ]
            
        ];

    }

    private function slash_commands_mapping() {
        $prompt = $this->prompt;

        $getPrompt = '';

        foreach ( $this->section_commands_list() as $list ) {
            if ( strpos( $prompt, $list['command'] ) !== false ) {
                $getPrompt = str_replace( $list['command'], $list['label'], $prompt );
            }
        }
        return $getPrompt;

    }
	
    public function is_slash_command( $prompt ) {
        preg_match_all('/\/[a-zA-Z0-9\-]+/', $prompt, $matches);
        return !empty( $matches[0] ) ? true : false;
    }


} // End Class
