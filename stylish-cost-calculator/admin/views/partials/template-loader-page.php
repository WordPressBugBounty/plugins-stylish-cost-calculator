<?php
/**
 * Template Loader Page
 */
$scc_is_free_version = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
// declaring the templates to load from json file
$options = [
    [
        'title'       => 'Venue Rental',
        'description' => 'Streamline your venue rental process with our cost calculator template, enabling you to quickly estimate expenses and offer transparent pricing to your clients.',
        'url'         => 'https://stylishcostcalculator.com/templates/venue-rentals-calculator/',
        'preview'     => 'template-preview-0',
        'cover'       => 'template-cover-01',
        'type'        => '',
        'industry'    => 'services, other',
        'elements'    => 'dropdown, slider, checkbox, single-button, date, comment-box',
        'features'    => 'multi-step, email-quote, detailed-list,coupon-code',
        'premium'     => true,
        'tags'        => 'venue, rental, events, party, space, booking',
    ],
    [
        'title'       => 'Website Designer',
        'description' => 'This cost calculator quote form is configured for web design agencies looking to streamline their sales process.',
        'url'         => 'https://stylishcostcalculator.com/templates/web-developer-calculator/',
        'preview'     => 'template-preview-01',
        'cover'       => 'template-cover-02',
        'type'        => '',
        'industry'    => 'services, web',
        'elements'    => 'dropdown, slider, checkbox, single-button, image-button',
        'features'    => 'multi-step, stripe, email-quote, detailed-list',
        'premium'     => false,
        'tags'        => 'website, web, designer, developer, agency, quote, form',
    ],
    [
        'title'       => 'Wedding Photographer',
        'description' => 'Streamline your quote process with our Wedding Photographer template, enabling you to quickly provide accurate cost estimates for your photography services.',
        'url'         => 'https://stylishcostcalculator.com/templates/wedding-photographer-calculator/',
        'preview'     => 'template-preview-02',
        'cover'       => 'template-cover-03',
        'type'        => '',
        'industry'    => 'services, food-events',
        'elements'    => 'dropdown, slider, single-button, text-html',
        'features'    => 'email-quote, detailed-list, coupon-code',
        'premium'     => false,
        'tags'        => 'wedding, photographer, photography, photo, marriage, events, quote',
    ],
    [
        'title'       => 'Car Rental',
        'description' => 'Our cost calculator quote form simplifies pricing for Car Rental companies. Customizable options, including additional services, easily calculate the cost',
        'url'         => 'https://stylishcostcalculator.com/templates/car-rental-calculator/',
        'preview'     => 'template-preview-03',
        'cover'       => 'template-cover-04',
        'type'        => '',
        'industry'    => 'vehicles, services',
        'elements'    => 'date, distance, dropdown, slider, checkbox, comment-box, file-upload',
        'features'    => 'minimum-total-price, email-quote, detailed-list, tax',
        'premium'     => false,
        'tags'        => 'car, rental, vehicle, auto, transportation, booking',
    ],
    [
        'title'       => 'T-Shirt Printing',
        'description' => 'Effortlessly calculate the cost of custom t-shirt printing projects, ensuring you accurately price your services while meeting your clients\' unique needs.',
        'url'         => 'https://stylishcostcalculator.com/templates/t-shirt-printing-calculator/',
        'preview'     => 'template-preview-04',
        'cover'       => 'template-cover-05',
        'type'        => '',
        'industry'    => 'services, printing-publishing',
        'elements'    => 'checkbox, dropdown, slider',
        'features'    => 'multi-step, email-quote, detailed-list, paypal, stripe',
        'premium'     => false,
        'tags'        => 't-shirt, tshirt, printing, custom, apparel, clothing, design',
    ],
    [
        'title'       => 'Cleaning Company',
        'description' => 'Optimize your pricing strategy with our home cleaning cost calculator template, designed to help you provide competitive and fair quotes to your customers.',
        'url'         => 'https://stylishcostcalculator.com/templates/cleaning-company-template/',
        'preview'     => 'template-preview-05',
        'cover'       => 'template-cover-06',
        'type'        => '',
        'industry'    => 'cleaning, home',
        'elements'    => 'slider, dropdown, checkbox, single-button, image-button, date, comment-box',
        'features'    => 'email-quote, detailed-list, coupon-code, stripe, multi-step',
        'premium'     => false,
        'tags'        => 'cleaning, company, home, residential, commercial, services, maid',
    ],
    [
        'title'       => 'Funeral Home Company',
        'description' => 'Utilize our funeral home cost calculator template to compassionately and accurately provide estimates, helping families plan services within their budget.',
        'url'         => 'https://stylishcostcalculator.com/templates/funeral-home-calculator/',
        'preview'     => 'template-preview-06',
        'cover'       => 'template-cover-07',
        'type'        => '',
        'industry'    => 'services, food-events',
        'elements'    => 'text-html, dropdown,checkbox',
        'features'    => 'accordion, email-quote, detailed-list, coupon-code',
        'premium'     => false,
        'tags'        => 'funeral, home, company, services, burial, cremation, memorial',
    ],
    [
        'title'       => 'Content Writing Agency',
        'description' => 'Enhance your content writing service by using our cost calculator template, making it simple to quote projects based on word count, complexity, and deadlines.',
        'url'         => 'https://stylishcostcalculator.com/templates/content-writing-template/',
        'preview'     => 'template-preview-07',
        'cover'       => 'template-cover-08',
        'type'        => '',
        'industry'    => 'services, printing-publishing, web',
        'elements'    => 'dropdown, text-html, quantity-input-box, checkbox, slider',
        'features'    => 'accordion, email-quote, detailed-list, coupon-code',
        'premium'     => false,
        'tags'        => 'content, writing, agency, copywriting, articles, blog, marketing',
    ],
    [
        'title'       => 'Audio Editing Services',
        'description' => 'Simplify your audio editing pricing with our cost calculator template, ensuring you accurately account for the complexity, duration, and specific requirements of each project.',
        'url'         => 'https://stylishcostcalculator.com/templates/audio-editing-and-podcast-editing-calculculator/',
        'preview'     => 'template-preview-08',
        'cover'       => 'template-cover-09',
        'type'        => '',
        'industry'    => 'services, art',
        'elements'    => 'text-html, dropdown, slider, comment-box',
        'features'    => 'detailed-list',
        'premium'     => false,
        'tags'        => 'audio, editing, services, podcast, sound, music, production',
    ],
    [
        'title'       => 'Social Media Management',
        'description' => 'Streamline your social media management pricing with our cost calculator template, designed to tailor your services to various platforms and client needs efficiently.',
        'url'         => 'https://stylishcostcalculator.com/templates/social-media-manager-calculator/',
        'preview'     => 'template-preview-09',
        'cover'       => 'template-cover-10',
        'type'        => '',
        'industry'    => 'services, web',
        'elements'    => 'text-html, slider, checkbox, image-button',
        'features'    => 'multi-step, email-quote, detailed-list',
        'premium'     => false,
        'tags'        => 'social, media, management, marketing, facebook, instagram, twitter',
    ],
    [
        'title'       => 'Student Fees',
        'description' => 'Manage and calculate student fees effortlessly with our template, designed to help educational institutions provide clear and detailed breakdowns of costs.',
        'url'         => 'https://stylishcostcalculator.com/templates/student-fees-calculator/',
        'preview'     => 'template-preview-10',
        'cover'       => 'template-cover-11',
        'type'        => '',
        'industry'    => 'services, education',
        'elements'    => 'text-html, slider',
        'features'    => 'email-quote, detailed-list',
        'premium'     => false,
        'tags'        => 'student, fees, education, school, college, university, tuition',
    ],
    [
        'title'       => 'Digital Print and Lamination',
        'description' => 'Efficiently calculate costs for digital printing and lamination services with our template, ensuring competitive pricing and clear quotations for your customers.',
        'url'         => '',
        'preview'     => 'template-preview-11',
        'cover'       => 'template-cover-12',
        'type'        => '',
        'industry'    => 'services, printing-publishing',
        'elements'    => '',
        'features'    => '',
        'premium'     => false,
        'tags'        => 'digital, print, printing, lamination, services, documents, posters',
    ],
    [
        'title'       => 'Kitchens Renovations',
        'description' => 'Optimize kitchen renovation estimates with our template, ensuring accurate pricing for materials and labor.',
        'url'         => 'https://stylishcostcalculator.com/templates/kitchen-renovation-company-calculator/',
        'preview'     => 'template-preview-12',
        'cover'       => 'template-cover-13',
        'type'        => '',
        'industry'    => 'construction, home',
        'elements'    => 'text-html, slider, checkbox, dropdown, image-button, comment-box',
        'features'    => 'email-quote, detailed-list, multi-step',
        'premium'     => false,
        'tags'        => 'kitchen, renovation, remodel, home, improvement, construction',
    ],
    [
        'title'       => 'Simple Video Budget',
        'description' => 'Simplify your video project budgeting with our calculator template, designed for quick and precise cost estimations for production and post-production.',
        'url'         => '',
        'preview'     => 'template-preview-13',
        'cover'       => 'template-cover-14',
        'type'        => '',
        'industry'    => 'services, art',
        'elements'    => 'checkbox, text-html',
        'features'    => 'email-quote, detailed-list, coupon-code',
        'premium'     => false,
        'tags'        => 'video, budget, production, film, simple, cost, estimation',
    ],
    [
        'title'       => 'Food Catering',
        'description' => 'Enhance your food catering quotes with our cost calculator template, designed for accurate pricing of menus, services, and event specifics.',
        'url'         => 'https://stylishcostcalculator.com/templates/food-catering-template/',
        'preview'     => 'template-preview-14',
        'cover'       => 'template-cover-15',
        'type'        => '',
        'industry'    => 'food-events',
        'elements'    => 'slider, dropdown, checkbox, image-button, date, distance, comment-box',
        'features'    => 'multi-step, accordion, email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'food, catering, events, party, menu, service, quote',
    ],
    [
        'title'       => 'Pest Control Services',
        'description' => 'Streamline your pest control service quotes with our cost calculator template, ensuring accurate pricing for treatments based on infestation level and property size.',
        'url'         => '',
        'preview'     => '',
        'cover'       => '',
        'type'        => '',
        'industry'    => 'services, cleaning',
        'elements'    => '',
        'features'    => '',
        'premium'     => false,
        'disabled'    => true,
        'tags'        => 'pest, control, services, exterminator, insects, rodents, bugs',
    ],
    [
        'title'       => 'Book Publisher Service',
        'description' => 'Refine your book publishing cost estimates with our calculator template, tailored to cover editing, printing, and distribution expenses accurately.',
        'url'         => '',
        'preview'     => 'template-preview-16',
        'cover'       => 'template-cover-16',
        'type'        => '',
        'industry'    => 'services, printing-publishing ',
        'elements'    => 'text-html, checkbox',
        'features'    => 'detailed-list, email-quote',
        'premium'     => false,
        'tags'        => 'book, publisher, publishing, service, printing, editing, author',
    ],
    [
        'title'       => 'Loan Calculator for Monthly Payment',
        'description' => 'Facilitate precise loan calculations with our template, designed to provide clear monthly payment estimates based on interest rates and loan terms.',
        'url'         => '',
        'preview'     => 'template-preview-17',
        'cover'       => 'template-cover-17',
        'type'        => '',
        'industry'    => 'services, finance',
        'elements'    => 'variable-math',
        'features'    => 'email-quote',
        'premium'     => true,
        'tags'        => 'loan, calculator, monthly, payment, finance, interest, mortgage',
    ],
    [
        'title'       => 'Pricing Table (bronze)',
        'description' => 'Create dynamic pricing tables with our template, designed to showcase your service tiers, features, and costs clearly and effectively to customers.',
        'url'         => 'https://stylishcostcalculator.com/templates/pricing-table-calculator/',
        'preview'     => 'template-preview-18',
        'cover'       => 'template-cover-18',
        'type'        => '',
        'industry'    => 'products, services, finance',
        'elements'    => 'checkbox, text-html, variable-math',
        'features'    => 'stripe, detailed-list',
        'premium'     => true,
        'tags'        => 'pricing, table, bronze, services, products, tiers, cost',
    ],
    [
        'title'       => 'Home Furniture Calculator',
        'description' => 'Use our home furniture calculator template to easily estimate the cost of furnishing homes, ensuring clients receive transparent and tailored pricing.',
        'url'         => 'https://stylishcostcalculator.com/templates/home-furniture-calculator/',
        'preview'     => 'template-preview-19',
        'cover'       => 'template-cover-19',
        'type'        => '',
        'industry'    => 'home',
        'elements'    => 'dropdown, checkbox, text-html',
        'features'    => 'conditional-logic, email-quote, detailed-list',
        'premium'     => false,
        'tags'        => 'home, furniture, calculator, furnishing, interior, design, decor',
    ],
    [
        'title'       => 'Vehicle Parts Calculator',
        'description' => 'Streamline your vehicle parts pricing with our calculator template, designed for quick, accurate cost estimations for parts and associated services.',
        'url'         => 'https://stylishcostcalculator.com/templates/vehicle-parts-cost-calculator/',
        'preview'     => 'template-preview-20',
        'cover'       => 'template-cover-20',
        'type'        => '',
        'industry'    => 'vehicles',
        'elements'    => 'text-html, dropdown, comment-box, checkbox',
        'features'    => 'multi-step, email-quote, detailed-list',
        'premium'     => false,
        'tags'        => 'vehicle, parts, calculator, car, auto, components, repair',
    ],
    [
        'title'       => 'Landscape & Patio Cost Calculator',
        'description' => 'Simplify your landscape and patio project quotes with our cost calculator template, enabling accurate estimates for materials, design, and labor.',
        'url'         => 'https://stylishcostcalculator.com/templates/landscape-patio-cost-calculator/',
        'preview'     => 'template-preview-21',
        'cover'       => 'template-cover-21',
        'type'        => '',
        'industry'    => 'home',
        'elements'    => 'text-html, dropdown, slider, checkbox, quantity-input-box, image-button, comment-box',
        'features'    => 'conditional-logic, accordion, multi-step, email-quote, detailed-list',
        'premium'     => false,
        'tags'        => 'landscape, patio, cost, calculator, garden, outdoor, design',
    ],
    [
        'title'       => 'Home Improvement',
        'description' => 'Streamline estimating costs for home renovations with our calculator template, ensuring precise quotes for materials, labor, and design services.',
        'url'         => 'https://stylishcostcalculator.com/templates/home-renovation-cost-estimator/',
        'preview'     => 'template-preview-22',
        'cover'       => 'template-cover-22',
        'type'        => '',
        'industry'    => 'home, construction',
        'elements'    => 'text-html, slider, checkbox, dropdown, image-button, comment-box',
        'features'    => 'email-quote, detailed-list, multi-step',
        'premium'     => true,
        'tags'        => 'home, improvement, renovation, construction, remodel, repair, diy',
    ],
    [
        'title'       => 'Flooring, Carpet & Hardwood Estimator',
        'description' => 'Efficiently calculate costs for flooring projects, including carpet and hardwood, with our estimator template, ensuring accurate pricing for materials and installation.',
        'url'         => 'https://stylishcostcalculator.com/templates/flooring-carpet-hardwood-estimator/',
        'preview'     => 'template-preview-23',
        'cover'       => 'template-cover-23',
        'type'        => '',
        'industry'    => 'services, construction',
        'elements'    => 'text-html, slider, dropdown',
        'features'    => 'conditional-logic, multi-step, email-quote, detailed-list, tax',
        'premium'     => false,
        'tags'        => 'flooring, carpet, hardwood, estimator, installation, home, construction',
    ],
    [
        'title'       => 'Engaging Product & Service Quiz',
        'description' => 'Create engaging quizzes for products and services with our template, designed to captivate your audience and tailor offerings to their preferences.',
        'url'         => 'https://stylishcostcalculator.com/templates/product-service-quiz-template/',
        'preview'     => 'template-preview-24',
        'cover'       => 'template-cover-24',
        'type'        => '',
        'industry'    => 'products, services',
        'elements'    => 'text-html, checkbox, image-button',
        'features'    => 'conditional-logic, multi-step, email-quote',
        'premium'     => true,
        'tags'        => 'engaging, product, service, quiz, survey, lead, generation',
    ],
    [
        'title'       => 'Bakery Template',
        'description' => 'Enhance your bakery\'s efficiency with our cost calculator template, perfect for accurately pricing baked goods, ingredients, and custom orders.',
        'url'         => 'https://stylishcostcalculator.com/templates/bakery-template/',
        'preview'     => 'template-preview-25',
        'cover'       => 'template-cover-25',
        'type'        => '',
        'industry'    => 'food-events, products',
        'elements'    => 'text-html, dropdown, comment-box, checkbox, file-upload, date, quantity-input-box',
        'features'    => 'conditional-logic, accordion, multi-step, paypal, email-quote, detailed-list, tax',
        'premium'     => false,
        'tags'        => 'bakery, template, cake, bread, pastry, food, order',
    ],
    [
        'title'       => 'Grocery Store - Online Order Form',
        'description' => 'Streamline online grocery orders with our form template, designed for easy item selection and accurate cost calculations, improving customer experience.',
        'url'         => 'https://stylishcostcalculator.com/templates/grocery-costs-calculator/',
        'preview'     => 'template-preview-26',
        'cover'       => 'template-cover-26',
        'type'        => '',
        'industry'    => 'food-events, products',
        'elements'    => 'image-button',
        'features'    => 'accordion, paypal, stripe, detailed-list, tax',
        'premium'     => true,
        'tags'        => 'grocery, store, online, order, form, food, delivery',
    ],
    [
        'title'       => 'Residential Painting',
        'description' => 'Calculate your residential painting services costs with ease using our template, ensuring accurate quotes for paint, labor, and square footage.',
        'url'         => 'https://stylishcostcalculator.com/templates/residential-commercial-painting/',
        'preview'     => 'template-preview-27',
        'cover'       => 'template-cover-27',
        'type'        => '',
        'industry'    => 'home',
        'elements'    => 'text-html, checkbox, image-button',
        'features'    => 'conditional-logic, email-quote',
        'premium'     => true,
        'tags'        => 'residential, painting, home, house, painter, services, interior, exterior',
    ],
    [
        'title'       => 'ROI Calculator',
        'description' => 'Efficiently assess project or investment ROI with our calculator template, simplifying financial performance analysis.',
        'url'         => 'https://stylishcostcalculator.com/templates/roi-calculator/',
        'preview'     => 'template-preview-28',
        'cover'       => 'template-cover-28',
        'type'        => '',
        'industry'    => 'services, finance',
        'elements'    => 'variable-math',
        'features'    => 'accordion, email-quote, detailed-list',
        'premium'     => true,
        'tags'        => 'roi, calculator, return, investment, finance, business, analysis',
    ],
    [
        'title'       => 'Medicine Calculation',
        'description' => 'Ensure accurate medicine dosages and costs with our calculator template, designed for healthcare professionals and pharmacies.',
        'url'         => 'https://stylishcostcalculator.com/templates/medicine-dosage-calculation/',
        'preview'     => 'template-preview-29',
        'cover'       => 'template-cover-29',
        'type'        => '',
        'industry'    => 'health, services',
        'elements'    => 'dropdown, quantity-input-box, file-upload, text-html',
        'features'    => 'accordion, email-quote',
        'premium'     => true,
        'tags'        => 'medicine, calculation, dosage, health, pharmacy, medical, prescription',
    ],
    [
        'title'       => 'Lawn Care Calculator',
        'description' => 'Simplify your lawn care service quotes with our calculator template, allowing for precise pricing based on area size and service frequency.',
        'url'         => 'https://stylishcostcalculator.com/templates/lawn-care-template/',
        'preview'     => 'template-preview-30',
        'cover'       => 'template-cover-30',
        'type'        => '',
        'industry'    => 'home',
        'elements'    => 'text-html, variable-math, checkbox, slider, single-button',
        'features'    => 'price-hint, conditional-logic, email-quote, detailed-list, coupon-code, multi-step',
        'premium'     => true,
        'tags'        => 'lawn, care, calculator, mowing, garden, yard, landscaping',
    ],
    [
        'title'       => 'Home Alarm System',
        'description' => 'Calculate the cost of home alarm systems with our template, ensuring you provide accurate quotes for installation and monitoring services.',
        'url'         => 'https://stylishcostcalculator.com/templates/home-alarm-system-template/',
        'preview'     => 'template-preview-31',
        'cover'       => 'template-cover-31',
        'type'        => '',
        'industry'    => 'security,home, products',
        'elements'    => 'text-html, dropdown, image-button, checkbox, ',
        'features'    => 'multi-step, conditional-logic, email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'home, alarm, system, security, safety, monitoring, installation',
    ],
    [
        'title'       => 'Home Building Calculator',
        'description' => 'Estimate home building costs accurately with our calculator template, designed for clear pricing on materials, labor, and project timelines.',
        'url'         => 'https://stylishcostcalculator.com/templates/home-building/',
        'preview'     => 'template-preview-32',
        'cover'       => 'template-cover-32',
        'type'        => '',
        'industry'    => 'home, construction',
        'elements'    => 'text-html, dropdown, image-button, checkbox, distance, variable-math',
        'features'    => 'multi-step, conditional-logic, email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'home, building, calculator, construction, house, new, estimate',
    ],
    [
        'title'       => 'Air Conditioner Calculator',
        'description' => 'Streamline AC cost calculations with our template, ideal for unit sizing and expense estimation.',
        'url'         => 'https://stylishcostcalculator.com/templates/air-conditioner/',
        'preview'     => 'template-preview-33',
        'cover'       => 'template-cover-33',
        'type'        => '',
        'industry'    => 'home, construction',
        'elements'    => 'text-html, dropdown, image-button, distance, variable-math, slider',
        'features'    => 'multi-step,accordion, email-quote, detailed-list',
        'premium'     => true,
        'tags'        => 'air, conditioner, ac, calculator, hvac, cooling, installation',
    ],
    [
        'title'       => 'Beauty Quiz',
        'description' => 'Design beauty quizzes to connect clients with their ideal products, tailored to preferences and needs.',
        'url'         => 'https://stylishcostcalculator.com/templates/beauty-quiz/',
        'preview'     => 'template-preview-34',
        'cover'       => 'template-cover-34',
        'type'        => '',
        'industry'    => 'health, products',
        'elements'    => 'text-html, image-button, checkbox',
        'features'    => 'multi-step, email-quote',
        'premium'     => true,
        'tags'        => 'beauty, quiz, makeup, skincare, cosmetics, product, recommendation',
    ],
    [
        'title'       => 'Moving Cost Calculator',
        'description' => 'Streamline inquiries and feedback with our concise contact form template.',
        'url'         => 'https://stylishcostcalculator.com/templates/moving-cost-calculator/',
        'preview'     => 'template-preview-35',
        'cover'       => 'template-cover-35',
        'type'        => '',
        'industry'    => 'services, other',
        'elements'    => 'text-html, distance, dropdown, checkbox, slider, date',
        'features'    => 'email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'moving, cost, calculator, relocation, movers, estimate, services',
    ],
    [
        'title'       => 'Contact Form',
        'description' => 'Streamline inquiries and feedback with our concise contact form template.',
        'url'         => 'https://stylishcostcalculator.com/templates/contact-form-template/',
        'preview'     => 'template-preview-36',
        'cover'       => 'template-cover-36',
        'type'        => '',
        'industry'    => 'other',
        'elements'    => 'text-html, comment-box',
        'features'    => 'email-quote',
        'premium'     => true,
        'tags'        => 'contact, form, inquiry, feedback, message, lead, simple',
    ],
    [
        'title'       => 'Tree Removal Cost Calculator',
        'description' => 'Efficiently estimate the cost of tree removal services with our calculator template.',
        'url'         => 'https://stylishcostcalculator.com/templates/tree-removal-cost',
        'preview'     => 'template-preview-37',
        'cover'       => 'template-cover-37',
        'type'        => '',
        'industry'    => 'home, construction',
        'elements'    => 'text-html, checkbox, slider, distance, variable-math, dropdown',
        'features'    => 'email-quote, multi-step, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'tree, removal, cost, calculator, services, arborist, lumberjack',
    ],
    [
        'title'       => 'Roof Replacement Cost',
        'description' => 'Our Roof Replacement Cost Calculator offers swift, precise estimates for homeowners considering a roof upgrade.',
        'url'         => 'https://stylishcostcalculator.com/templates/roof-replacement-cost',
        'preview'     => 'template-preview-38',
        'cover'       => 'template-cover-38',
        'type'        => '',
        'industry'    => 'home, construction',
        'elements'    => 'text-html, checkbox, image-button, quantity-input-box, variable-math, dropdown',
        'features'    => 'email-quote, multi-step, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'roof, replacement, cost, calculator, roofing, home, construction',
    ],
    [
        'title'       => 'Lawn Mowing Cost Calculator',
        'description' => 'This intuitive tool takes the guesswork out of pricing, ensuring you get a clear understanding of the expenses involved for maintaining a pristine lawn.',
        'url'         => 'https://stylishcostcalculator.com/templates/lawn-mowing-cost-calculator',
        'preview'     => 'template-preview-39',
        'cover'       => 'template-cover-39',
        'type'        => '',
        'industry'    => 'home',
        'elements'    => 'text-html, checkbox,variable-math, dropdown, distance, custom-math',
        'features'    => 'email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'lawn, mowing, cost, calculator, garden, yard, landscaping, services',
    ],
    [
        'title'       => 'Construction Cost Calculator',
        'description' => 'With this Construction Cost Calculator you can estimate the expenses of your construction project.',
        'url'         => 'https://stylishcostcalculator.com/templates/construction-cost-calculator',
        'preview'     => 'template-preview-40',
        'cover'       => 'template-cover-40',
        'type'        => '',
        'industry'    => 'home, construction',
        'elements'    => 'text-html, checkbox, dropdown, quantity-input-box, slider',
        'features'    => 'email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'construction, cost, calculator, building, project, estimate, materials',
    ],
    [
        'title'       => 'Seller Closing Cost',
        'description' => 'Providing an accurate estimate of the fees and expenses incurred at the end of a real estate transaction.',
        'url'         => 'https://stylishcostcalculator.com/templates/seller-closing-cost-calculator',
        'preview'     => 'template-preview-41',
        'cover'       => 'template-cover-41',
        'type'        => '',
        'industry'    => 'home, business',
        'elements'    => 'text-html, checkbox, quantity-input-box, variable-math, custom-math',
        'features'    => 'email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'seller, closing, cost, calculator, real, estate, fees, transaction',
    ],
    [
        'title'       => 'Concrete Cost Calculator',
        'description' => 'A Concrete Bag Calculator is a practical tool for construction professionals and DIY enthusiasts alike.',
        'url'         => 'https://stylishcostcalculator.com/templates/concrete-cost-calculator',
        'preview'     => 'template-preview-42',
        'cover'       => 'template-cover-42',
        'type'        => '',
        'industry'    => 'construction, home',
        'elements'    => 'text-html, variable-math, signature-box',
        'features'    => 'email-quote',
        'premium'     => true,
        'tags'        => 'concrete, cost, calculator, construction, cement, bag, materials',
    ],
    [
        'title'       => 'Asphalt Driveway Cost',
        'description' => 'Calculate the cost of asphalt driveway installations and repairs with our template, considering factors like square footage, material costs, and labor.',
        'url'         => 'https://stylishcostcalculator.com/templates/asphalt-driveway-cost-calculator/',
        'preview'     => 'template-preview-43',
        'cover'       => 'template-cover-43',
        'type'        => '',
        'industry'    => 'construction',
        'elements'    => 'text-html, variable-math, signature-box, quantity-input-box, dropdown',
        'features'    => 'email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'asphalt, driveway, cost, calculator, paving, construction, repair',
    ],
    [
        'title'       => 'Solar Panel Cost',
        'description' => 'With our solar panel cost calculator, you can get a quick and accurate estimate of the cost of a custom solar system for your home or business.',
        'url'         => 'https://stylishcostcalculator.com/templates/solar-panel-cost-calculator/',
        'preview'     => 'template-preview-44',
        'cover'       => 'template-cover-44',
        'type'        => '',
        'industry'    => 'construction, home',
        'elements'    => 'text-html, variable-math, quantity-input-box, custom-math',
        'features'    => 'email-quote, detailed-list, coupon-code',
        'premium'     => true,
        'tags'        => 'solar, panel, cost, calculator, energy, renewable, installation',
    ],
    [
        'title'       => 'Fencing Cost Calculator',
        'description' => 'Attract more customers, boost sales, and streamline operations with a fence cost calculator on your website',
        'url'         => 'https://stylishcostcalculator.com/templates/fencing-cost-calcualtor/',
        'preview'     => 'template-preview-45',
        'cover'       => 'template-cover-45',
        'type'        => '',
        'industry'    => 'construction, home',
        'elements'    => 'text-html, variable-math, quantity-input-box, custom-math, dropdown, image-button, single-button',
        'features'    => 'email-quote, detailed-list, coupon-code, multi-step',
        'premium'     => true,
        'tags'        => 'fencing, fence, cost, calculator, installation, construction, home',
    ],
    [
        'title'       => 'Meal Plan Delivery Quote',
        'description' => 'This intuitive meal plan delivery calculator simplifies the process of planning and ordering your weekly meals',
        'url'         => 'https://stylishcostcalculator.com/templates/meal-plan-delivery-calculator/',
        'preview'     => 'template-preview-46',
        'cover'       => 'template-cover-46',
        'type'        => '',
        'industry'    => 'home, food-events',
        'elements'    => 'text-html, quantity-input-box, image-button, comment-box',
        'features'    => 'email-quote, detailed-list, multi-step, paypal, stripe',
        'premium'     => true,
        'tags'        => 'meal, delivery, service, calculator, food, subscription, order',
    ],
    [
        'title'       => 'Fitness Supplements Calculator',
        'description' => 'The "Fitness Supplements Calculator" helps you discover the right supplements for your needs, letting you see options and costs immediately',
        'url'         => 'https://stylishcostcalculator.com/templates/supplements-calculator/',
        'preview'     => 'template-preview-47',
        'cover'       => 'template-cover-47',
        'type'        => '',
        'industry'    => 'health',
        'elements'    => 'text-html, checkbox, quantity-input-box, image-button, comment-box',
        'features'    => 'email-quote, detailed-list, multi-step, accordion',
        'premium'     => true,
        'tags'        => 'fitness, supplements, calculator, health, workout, nutrition, vitamins',
    ],
    [
        'title'       => 'Custom Shutter Quote',
        'description' => 'This custom shutter calculator will help you make the necessary calculations to offer a tailor-made service.',
        'url'         => 'https://stylishcostcalculator.com/templates/design-your-dream-shutters/',
        'preview'     => 'template-preview-48',
        'cover'       => 'template-cover-48',
        'type'        => '',
        'industry'    => 'home, construction',
        'elements'    => 'text-html, checkbox, quantity-input-box, image-button, comment-box, dropdown',
        'features'    => 'email-quote, detailed-list, multi-step',
        'premium'     => true,
        'tags'        => 'custom, shutter, quote, window, coverings, blinds, home, installation',
    ],
];
$scc_industry_filters = [
    ['slug' => 'all', 'title' => 'All', 'premium' => false],
    ['slug' => 'home', 'title' => 'Home & Garden', 'premium' => false],
    ['slug' => 'construction', 'title' => 'Construction & Maintenance', 'premium' => false],
    ['slug' => 'food-events', 'title' => 'Food, Drink & Events', 'premium' => false],
    ['slug' => 'printing-publishing', 'title' => 'Printing & Publishing', 'premium' => false],
    ['slug' => 'web', 'title' => 'Web Design & Development', 'premium' => false],
    ['slug' => 'finance', 'title' => 'Finance', 'premium' => false],
    ['slug' => 'vehicles', 'title' => 'Vehicles Parts & Services', 'premium' => false],
    ['slug' => 'business', 'title' => 'Business Services', 'premium' => false],
    ['slug' => 'health', 'title' => 'Health & Beauty', 'premium' => false],
    ['slug' => 'art', 'title' => 'Arts, Design & Music', 'premium' => false],
    ['slug' => 'education', 'title' => 'Education', 'premium' => false],
    ['slug' => 'cleaning', 'title' => 'Cleaning Services', 'premium' => false],
    ['slug' => 'sports', 'title' => 'sports', 'premium' => false],
    ['slug' => 'security', 'title' => 'Security Products & Services', 'premium' => false],
    ['slug' => 'travel', 'title' => 'Travel', 'premium' => false],
    ['slug' => 'software', 'title' => 'Software', 'premium' => false],
    ['slug' => 'shopping', 'title' => 'shopping', 'premium' => false],
    ['slug' => 'other', 'title' => 'Other', 'premium' => false],
];
$scc_elements_filters = [
    ['slug' => 'all', 'title' => 'All', 'premium' => false],
    ['slug' => 'dropdown', 'title' => 'Dropdown', 'premium' => false],
    ['slug' => 'slider', 'title' => 'Slider', 'premium' => false],
    ['slug' => 'checkbox', 'title' => 'Checkbox', 'premium' => false],
    ['slug' => 'quantity-input-box', 'title' => 'Quantity Input Box', 'premium' => false],
    ['slug' => 'single-button', 'title' => 'Single Button', 'premium' => false],
    ['slug' => 'comment-box', 'title' => 'Comment Box', 'premium' => false],
    ['slug' => 'image-button', 'title' => 'Image Button', 'premium' => true],
    ['slug' => 'variable-math', 'title' => 'Advanced Pricing Formula', 'premium' => true],
    ['slug' => 'date', 'title' => 'Date', 'premium' => true],
    ['slug' => 'distance', 'title' => 'Distance Cost', 'premium' => true],
    ['slug' => 'text-html', 'title' => 'Text/HTML', 'premium' => true],
    ['slug' => 'custom-math', 'title' => 'Fee & Discount Adjuster', 'premium' => true],
    ['slug' => 'signature-box', 'title' => 'Signature Box', 'premium' => true],
];
$scc_features_filters = [
    ['slug' => 'all', 'title' => 'All', 'premium' => false],
    ['slug' => 'detailed-list', 'title' => 'Detailed List', 'premium' => false],
    ['slug' => 'coupon-code', 'title' => 'Coupon Code', 'premium' => false],
    ['slug' => 'accordion', 'title' => 'Accordion', 'premium' => false],
    ['slug' => 'email-quote', 'title' => 'Email Quote', 'premium' => true],
    ['slug' => 'multi-step', 'title' => 'Multi-Step Form', 'premium' => true],
    ['slug' => 'conditional-logic', 'title' => 'Conditional Logic', 'premium' => true],
    ['slug' => 'woocommerce', 'title' => 'WooCommerce', 'premium' => true],
    ['slug' => 'paypal', 'title' => 'Paypal', 'premium' => true],
    ['slug' => 'stripe', 'title' => 'Stripe', 'premium' => true],
    ['slug' => 'unit-price', 'title' => 'Unit Price', 'premium' => true],
    ['slug' => 'blur-total-price', 'title' => 'Blur Total Price', 'premium' => true],
    ['slug' => 'tax', 'title' => 'Tax', 'premium' => true],
    ['slug' => 'minimum-total-price', 'title' => 'Minimum Total Price', 'premium' => true],
];

function scc_process_template_image_url( $option, $type='preview' ) {
    $url             = '';
    $sanitized_title = sanitize_title_with_dashes( $option['title'] );

    if ( $type === 'preview' && !empty( $option['preview'] ) ) {
        $url = SCC_TEMPLATE_PREVIEW_BASEURL . '/' . esc_attr( $option['preview'] ) . '.webp';
    } elseif ( $type === 'cover' && !empty( $option['cover'] ) ) {
        $url = SCC_TEMPLATE_COVER_BASEURL . '/' . esc_attr( $option['cover'] ) . '.webp';
    } else {
        $url = SCC_TEMPLATE_PREVIEW_BASEURL . '/' . esc_attr( $sanitized_title ) . '.webp';
    }

    return esc_url( $url );
}
/**
 * Generate list items for the template selector
 *
 * @param array $options
 *
 * @return string industry, elements, features, premium
 */
function scc_generate_filter_list( $filter_array, $type, $scc_is_free_version ) {
    $listItems = '';

    foreach ( $filter_array as $filter ) {
        $icon = '';

        if ( ( $type === 'elements' || $type === 'features' ) && $scc_is_free_version && $filter['premium'] ) {
            $icon = '<i class="far fa-gem me-1 scc-color-secondary"></i>'; // replace 'icon-class' with your icon's class
        }
        $listItems .= '
            <li class="scc-template-filter-item scc-template-filter-' . esc_attr( $type ) . '-type" data-' . esc_attr( $type ) . '="' . esc_attr( $filter['slug'] ) . '" data-premium="' . ( $filter['premium'] ? '1' : '0' ) . '">
                <span class="scc-template-filter-name">' . $icon . $filter['title'] . '</span>
                <span class="scc-template-filter-badge"></span>
            </li>
        ';
    }

    return $listItems;
}
?>
<!-- Main container -->
<div id="template-selector-section" class="container scc-template-selector d-none">
  <input id="choose-a-template" type="hidden">
  <!-- Row for filters and templates -->
  <div class="row">

    <!-- Filters column -->
    <div class="col-md-3">
		<div class="scc-template-filters">
			<!-- Search -->
			<div class="scc-template-search-wrapper">
				<input id="scc-search-template-bar" type="search" class="form-control scc-template-search" placeholder="Search">
				<span class="scc-icn-wrapper scc-search-icon"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['search'] ); ?></span>
			</div>
		
			<div class="accordion" id="scc-accordion-template-filters">
				<div class="accordion-item">
					<h2 class="accordion-header" id="headingTwo">
					<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						Industries
					</button>
					</h2>
					<div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#scc-accordion-template-filters">
						<div class="accordion-body">

							<ul class="scc-template-filter-list">
								<?php echo scc_generate_filter_list( $scc_industry_filters, 'industry', $scc_is_free_version ); ?>
							</ul>
							
						</div>
					</div>
				</div>
				<div class="accordion-item">
					<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						Elements
					</button>
					</h2>
					<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#scc-accordion-template-filters">
						<div class="accordion-body">
							<ul class="scc-template-filter-list">
								<?php echo scc_generate_filter_list( $scc_elements_filters, 'elements', $scc_is_free_version ); ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="accordion-item">
					<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
						Features
					</button>
					</h2>
					<div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#scc-accordion-template-filters">
						<div class="accordion-body">
							<ul class="scc-template-filter-list">
								<?php echo scc_generate_filter_list( $scc_features_filters, 'features', $scc_is_free_version ); ?>
							</ul>
						</div>
					</div>
				</div>
				</div>
      </div>

      <!-- Types and industry filters -->
      <!-- ... -->

    </div>

    <!-- Templates column -->
    <div class="col-md-9 scc-new-calculator-cards-container">
      
      <!-- Action buttons -->
    <div class="pb-4 d-flex scc-new-calculator-action-container">
		<button class="scc-new-calculator-action-btn" type="button" data-btn-action="backToHome"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['chevron-left'] ); ?></span> Back</button>
	</div>
	  <div class="head">
			<div class="text-muted text-uppercase">Option B</div>
			<strong>Ready-to-Play Templates</strong>
	  </div>
	  <?php if ( $scc_is_free_version ) { ?>
	  <div class="d-flex scc-template-premium-filter justify-content-center">
	  	<button class="btn btn-sm scc-template-premium-btn mx-2 selected" type="button" data-type="all">All Templates</button>
        <button class="btn btn-sm scc-template-premium-btn mx-2" type="button" data-type="premium"><i class="far fa-gem"></i> Premium</button>
		<button class="btn btn-sm scc-template-premium-btn mx-2" type="button" data-type="free"><span class="scc-icn-wrapper">Free</button>
	  </div>
	  <?php } ?>
      <!-- Templates -->
      <div class="row">
	  	<div class="col-md-12 mb-4 scc-template-no-cards-wrapper scc-hidden">
			<div class="scc-template-no-cards">
				<p class="m-0">There are no results for that search</p>
			</div>
		</div>
	  <?php
      $scc_template_id = 0;

foreach ( $options as $option ) { ?>
        <!-- Individual template -->
		<?php if ( !isset( $option['disabled'] ) || $option['disabled'] === false ) { ?>
        <div class="col-md-4 mb-4 scc-template-card-wrapper"
			data-title="<?php echo esc_attr( $option['title'] ); ?>" 
			data-description="<?php echo esc_attr( $option['description'] ); ?>"
			data-preview="<?php echo esc_url( scc_process_template_image_url( $option ) ); ?>"
			data-cover="<?php echo esc_url( scc_process_template_image_url( $option ) ); ?>" 
			data-url="<?php echo esc_url( $option['url'] ); ?>" 
			data-type="<?php echo esc_attr( $option['type'] ); ?>" 
			data-industry="<?php echo esc_attr( $option['industry'] ); ?>" 
			data-elements="<?php echo esc_attr( $option['elements'] ); ?>"
			data-features="<?php echo esc_attr( $option['features'] ); ?>" 
			data-premium="<?php echo esc_attr( $option['premium'] ); ?>"
			data-tags="<?php echo esc_attr( $option['tags'] ); ?>">
          <div class="card scc-template-card p-0 <?php echo $option['premium'] && $scc_is_free_version ? 'scc-template-premium-true' : ''; ?>">
			<div class="scc-template-card-img">
				<div class="scc-template-preview-container">
					<div class="row m-0 justify-content-center">
						<div class="col-6 d-flex">
							<a class="scc-card-action-btn scc-template-preview-btn" href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#scc-preview-template-modal">
								<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['eye'] ); ?></span>
								<span>Preview</span>
							</a>
						</div>
						<?php if ( !empty( $option['url'] ) ) { ?>
						<div class="col-6 d-flex">
							<a class="scc-card-action-btn" target="_blank" href="<?php echo esc_url( $option['url'] ); ?>">
								<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['external-link'] ); ?></span>
								<span>Live Demo</span>
							</a>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="scc-lazyload"></div>
				<img src="<?php echo esc_url( SCC_URL . 'assets/images/placeholder-image.png' ); ?>" data-src="<?php echo esc_url( scc_process_template_image_url( $option, 'cover' ) ); ?>" class="card-img-top" alt="<?php echo esc_attr( $option['title'] ); ?>" loading="lazy">
			</div>
            <div class="card-body">
              <h5 class="card-title"><?php echo esc_html( $option['title'] ); ?></h5>
			  <div class="scc-select-template-card-content">
			  	<p class="card-text "><?php echo esc_html( $option['description'] ); ?></p>
				<div class="scc-select-template-btn-container">
					<?php
                  $scc_btn_disabled = $option['premium'] && $scc_is_free_version ? 'disabled' : '';
		    ?>
					<button type="button" data-relative-field="choose-a-template" data-template-id="<?php echo esc_attr( $scc_template_id ); ?>" class="btn btn-primary text-white scc-select-template-btn" <?php echo esc_attr( $scc_btn_disabled ); ?>>
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['layout'] ); ?></span> Use this template
					</button>
				</div>
			  </div>
			  
              
            </div>
          </div>
        </div>
		<?php } ?>
        <!-- More templates... -->
		
		<?php
        $scc_template_id++;
} ?>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="scc-preview-template-modal" tabindex="-1" role="dialog" aria-labelledby="scc-preview-template-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scc-preview-template-modal-label"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['eye'] ); ?></span> <span class="scc-preview-title-text"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img class="scc-preview-template-image">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>




<script>

sccInitializeTemplateFilters('industry');
sccInitializeTemplateFilters('elements');
sccInitializeTemplateFilters('features');
sccFilterPremiumTemplates();
sccSearchByTemplateTitle();
sccInitializeTemplateModalPreview();
sccLazyLoadingTemplates();

function sccInitializeTemplateModalPreview() {
    const modal = document.getElementById('scc-preview-template-modal');
    const previewImage = document.querySelector('.scc-preview-template-image');
	const previewTitle = document.querySelector('#scc-preview-template-modal-label .scc-preview-title-text');
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
		const wrapper = button.closest('.scc-template-card-wrapper');
        const imageSrc = wrapper.getAttribute('data-preview'); 

        previewImage.setAttribute('src', imageSrc);
		previewTitle.textContent = wrapper.getAttribute('data-title');
    });
}

function sccInitializeTemplateFilters(filterType) {
    let filter = [];
    let currentSelection = null;

    document.querySelectorAll('.scc-template-filter-' + filterType +'-type').forEach(function(li) {

        const filterValue = li.getAttribute('data-' + filterType);
        let count;

        // If 'all' is selected, count all cards
        if (filterValue === 'all') {
            count = document.querySelectorAll('.scc-template-card-wrapper').length;
        } else {
            count = document.querySelectorAll('.scc-template-card-wrapper[data-' + filterType + '*="' + filterValue + '"]').length;
        }

        li.querySelector('.scc-template-filter-badge').textContent = count;
		if (count === 0) {
			li.classList.add('scc-hidden');
		}

        li.addEventListener('click', function() {
            if (this.classList.contains('selected')) {
                return;
            }

            // Deselect the current selection
            if (currentSelection) {
                currentSelection.classList.remove('selected');
            }

            // Select the new li
            this.classList.add('selected');
            currentSelection = this;

            // If 'all' is selected, show all cards and clear the filter
            if (filterValue === 'all') {
                document.querySelectorAll('.scc-template-card-wrapper').forEach(function(card) {
                    card.classList.remove('scc-hidden');
                });
                filter = [];
            } else {
                filter = [filterValue];

                // Hide all cards
                document.querySelectorAll('.scc-template-card-wrapper').forEach(function(card) {
                    card.classList.add('scc-hidden');
                });

                // Show cards that match the filter
                document.querySelectorAll('.scc-template-card-wrapper[data-' + filterType + '*="' + filterValue + '"]').forEach(function(card) {
                    card.classList.remove('scc-hidden');
                });
            }
        });
    });
}

function sccFilterPremiumTemplates(){
	const filterButtons = document.querySelectorAll('.scc-template-premium-btn');
    const cards = document.querySelectorAll('.scc-template-card-wrapper');

    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const filter = button.getAttribute('data-type');

            cards.forEach(function(card) {
                const isPremium = card.getAttribute('data-premium') === '1';

                if (filter === 'all' || (filter === 'premium' && isPremium) || (filter === 'free' && !isPremium)) {
                    card.classList.remove('scc-hidden');
                } else {
                    card.classList.add('scc-hidden');
                }
            });
        });
    });
}

function sccSearchByTemplateTitle() {
    document.querySelector('.scc-template-search').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const searchIcon = document.querySelector('.scc-search-icon');
        const noCardsWrapper = document.querySelector('.scc-template-no-cards-wrapper');
        let matchCount = 0;

        if (this.value.length > 0) {
            searchIcon.classList.add('scc-hidden');
        } else {
            searchIcon.classList.remove('scc-hidden');
        }

        document.querySelectorAll('.scc-template-card-wrapper').forEach(function(card) {
            const title = card.getAttribute('data-title').toLowerCase();
            const tags = card.getAttribute('data-tags').toLowerCase(); // Get tags attribute

            if (title.includes(searchValue) || tags.includes(searchValue)) { // Check against title and tags
                card.classList.remove('scc-hidden');
                matchCount++;
            } else {
                card.classList.add('scc-hidden');
            }
        });

        if (matchCount > 0) {
            noCardsWrapper.classList.add('scc-hidden');
        } else {
            noCardsWrapper.classList.remove('scc-hidden');
        }
    });
}
function sccLazyLoadingTemplates(){
	document.addEventListener("DOMContentLoaded", function() {
	var lazyImages = [].slice.call(document.querySelectorAll("img.card-img-top"));

	if ("IntersectionObserver" in window) {
		let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
		entries.forEach(function(entry) {
			if (entry.isIntersecting) {
				let lazyImage = entry.target;
				lazyImage.src = lazyImage.dataset.src;
				lazyImage.classList.remove("card-img-top");
				lazyImage.previousElementSibling.classList.add("scc-hidden");
				lazyImageObserver.unobserve(lazyImage);
			}
		});
		});

		lazyImages.forEach(function(lazyImage) {
			lazyImageObserver.observe(lazyImage);
		});
	} else {
		// Fallback for browsers that don't support IntersectionObserver
		lazyImages.forEach(function(lazyImage) {
		lazyImage.src = lazyImage.dataset.src;
		});
	}
	});
}

</script>