-- Demo Providers Data for TripBazaar
-- This file contains comprehensive demo data for providers table
-- Run this after importing the main schema.sql

USE tripbazaar;

-- Insert demo provider users first
INSERT INTO users (email, password, first_name, last_name, phone, role, status) VALUES
('sarah.travels@tripbazaar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah', 'Williams', '+1-555-2001', 'provider', 'active'),
('adventure.hub@tripbazaar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Michael', 'Rodriguez', '+1-555-2002', 'provider', 'active'),
('luxury.escapes@tripbazaar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Emma', 'Thompson', '+1-555-2003', 'provider', 'active'),
('family.fun@tripbazaar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'David', 'Kumar', '+1-555-2004', 'provider', 'active'),
('beach.paradise@tripbazaar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lisa', 'Chen', '+1-555-2005', 'provider', 'active'),
('mountain.trails@tripbazaar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Robert', 'Patel', '+1-555-2006', 'provider', 'active'),
('cultural.journeys@tripbazaar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Priya', 'Singh', '+1-555-2007', 'provider', 'active'),
('wildlife.safaris@tripbazaar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'James', 'Anderson', '+1-555-2008', 'provider', 'active');

-- Insert provider profiles
-- Sarah Williams - Romantic Getaways Specialist
INSERT INTO provider_profiles (user_id, company_name, description, license_number, address, city, state, country, bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) VALUES
((SELECT id FROM users WHERE email = 'sarah.travels@tripbazaar.com'), 
'Sarah\'s Romantic Escapes', 
'Specializing in creating magical romantic getaways for couples. With over 8 years of experience in the travel industry, we craft personalized honeymoon packages and anniversary celebrations. Our expertise lies in secluded destinations, luxury accommodations, and intimate dining experiences that create lasting memories.',
'RT-LIC-2019-001234',
'Suite 205, Romance Tower, Love Lane',
'Mumbai',
'Maharashtra',
'India',
'HDFC Bank',
'50100123456789',
'Sarah Williams',
'HDFC0001234',
8.50,
TRUE);

-- Michael Rodriguez - Adventure Hub
INSERT INTO provider_profiles (user_id, company_name, description, license_number, address, city, state, country, bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) VALUES
((SELECT id FROM users WHERE email = 'adventure.hub@tripbazaar.com'),
'Adventure Hub India',
'Your ultimate destination for adrenaline-pumping adventures across India. We specialize in trekking, rock climbing, white water rafting, paragliding, and extreme sports. Our certified guides and safety-first approach ensure thrilling yet secure adventures for all skill levels.',
'ADV-LIC-2018-005678',
'Plot 45, Adventure Complex, Sector 18',
'Gurgaon',
'Haryana',
'India',
'ICICI Bank',
'60200234567890',
'Michael Rodriguez',
'ICIC0002345',
9.00,
TRUE);

-- Emma Thompson - Luxury Escapes
INSERT INTO provider_profiles (user_id, company_name, description, license_number, address, city, state, country, bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) VALUES
((SELECT id FROM users WHERE email = 'luxury.escapes@tripbazaar.com'),
'Elite Luxury Escapes',
'Curating exclusive luxury travel experiences for discerning travelers. From private jets and yacht charters to five-star resorts and Michelin-starred dining, we provide unparalleled luxury and personalized service. Our connections with premium hotels worldwide ensure exclusive benefits and VIP treatment.',
'LUX-LIC-2017-009876',
'Penthouse Floor, Luxury Plaza, Business District',
'Bangalore',
'Karnataka',
'India',
'Axis Bank',
'70300345678901',
'Emma Thompson',
'UTIB0003456',
7.00,
TRUE);

-- David Kumar - Family Fun Adventures
INSERT INTO provider_profiles (user_id, company_name, description, license_number, address, city, state, country, bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) VALUES
((SELECT id FROM users WHERE email = 'family.fun@tripbazaar.com'),
'Happy Family Travels',
'Creating magical family memories through carefully designed packages that cater to all age groups. We understand the unique needs of families traveling with children and offer kid-friendly accommodations, educational activities, and safe transportation. Our destinations include theme parks, wildlife sanctuaries, and cultural sites.',
'FAM-LIC-2020-112233',
'Building C-12, Family Gardens, Kids Zone',
'Pune',
'Maharashtra',
'India',
'SBI Bank',
'80400456789012',
'David Kumar',
'SBIN0004567',
9.50,
TRUE);

-- Lisa Chen - Beach Paradise
INSERT INTO provider_profiles (user_id, company_name, description, license_number, address, city, state, country, bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) VALUES
((SELECT id FROM users WHERE email = 'beach.paradise@tripbazaar.com'),
'Coastal Paradise Tours',
'Discover pristine beaches and coastal wonders with our expertly crafted beach packages. From the backwaters of Kerala to the beaches of Goa, we offer water sports, beach resorts, sunset cruises, and seafood experiences. Perfect for relaxation, water activities, and coastal adventures.',
'BCH-LIC-2019-445566',
'Ocean View Complex, Beach Road',
'Kochi',
'Kerala',
'India',
'Bank of Baroda',
'90500567890123',
'Lisa Chen',
'BARB0005678',
8.00,
TRUE);

-- Robert Patel - Mountain Trails
INSERT INTO provider_profiles (user_id, company_name, description, license_number, address, city, state, country, bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) VALUES
((SELECT id FROM users WHERE email = 'mountain.trails@tripbazaar.com'),
'Himalayan Mountain Trails',
'Explore the majestic Himalayas and hill stations with our mountain specialists. We offer trekking expeditions, hill station retreats, monastery visits, and scenic train journeys. Our experienced guides know the mountains like the back of their hands and ensure safe, memorable mountain experiences.',
'MTN-LIC-2018-778899',
'Hill View Lodge, Mountain Road, Sector 7',
'Shimla',
'Himachal Pradesh',
'India',
'Punjab National Bank',
'10600678901234',
'Robert Patel',
'PUNB0006789',
8.75,
TRUE);

-- Priya Singh - Cultural Journeys
INSERT INTO provider_profiles (user_id, company_name, description, license_number, address, city, state, country, bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) VALUES
((SELECT id FROM users WHERE email = 'cultural.journeys@tripbazaar.com'),
'Heritage Cultural Tours',
'Immerse yourself in India\'s rich cultural heritage with our authentic cultural experiences. We offer guided tours of historical monuments, traditional craft workshops, local cuisine experiences, and interactions with local communities. Our packages showcase the diverse traditions, arts, and customs of different regions.',
'CUL-LIC-2019-334455',
'Heritage House, Culture Street, Old City',
'Jaipur',
'Rajasthan',
'India',
'Canara Bank',
'11700789012345',
'Priya Singh',
'CNRB0007890',
9.25,
TRUE);

-- James Anderson - Wildlife Safaris
INSERT INTO provider_profiles (user_id, company_name, description, license_number, address, city, state, country, bank_name, account_number, account_holder_name, ifsc_code, commission_rate, is_verified) VALUES
((SELECT id FROM users WHERE email = 'wildlife.safaris@tripbazaar.com'),
'Wild India Safaris',
'Experience India\'s incredible wildlife with our expert-led safari packages. From tiger reserves to bird sanctuaries, we provide thrilling wildlife encounters while promoting conservation. Our packages include jungle lodges, guided safaris, photography tours, and educational programs about wildlife conservation.',
'WLD-LIC-2020-667788',
'Safari Lodge Complex, Forest Gate Area',
'Nagpur',
'Maharashtra',
'India',
'Union Bank of India',
'12800890123456',
'James Anderson',
'UBIN0008901',
8.25,
TRUE);

-- Insert demo packages for each provider
-- Sarah's Romantic Packages
INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) VALUES
((SELECT id FROM users WHERE email = 'sarah.travels@tripbazaar.com'), 2, 'Romantic Goa Honeymoon', 'romantic-goa-honeymoon', 'Experience the perfect honeymoon in beautiful Goa with beachside luxury, candlelight dinners, and romantic sunsets. This package includes stays at premium beach resorts, couple spa treatments, private beach dinners, and sunset cruises.', 'Perfect honeymoon package in Goa with luxury beach resort, spa, and romantic experiences', 'Goa', 5, 4, 45000.00, 15000.00, 8000.00, 'Luxury beach resort accommodation, Daily breakfast and dinner, Couple spa session, Private candlelight dinner on beach, Sunset cruise, Airport transfers, Welcome drink and honeymoon amenities', 'Airfare, Lunch, Personal expenses, Adventure activities, Tips and gratuities', 'Booking must be made 15 days in advance. 50% advance payment required. Cancellation charges apply.', '/assets/images/packages/goa-honeymoon.jpg', TRUE, 4.8, 156, 89),

((SELECT id FROM users WHERE email = 'sarah.travels@tripbazaar.com'), 2, 'Kashmir Paradise for Couples', 'kashmir-paradise-couples', 'Discover the beauty of Kashmir with your loved one. Enjoy houseboat stays, Shikara rides, beautiful gardens, and snow-capped mountains creating the perfect romantic backdrop.', 'Romantic Kashmir getaway with houseboat stays and scenic beauty', 'Kashmir', 6, 4, 52000.00, 18000.00, 9000.00, 'Deluxe houseboat accommodation, Dal Lake Shikara ride, Mughal Gardens tour, Gulmarg day trip, All meals, Local sightseeing, Airport transfers', 'Airfare, Pony rides, Shopping, Personal expenses, Tips', 'Valid for couples only. Weather dependent activities. Advance booking recommended.', '/assets/images/packages/kashmir-couples.jpg', TRUE, 4.9, 203, 134),

((SELECT id FROM users WHERE email = 'sarah.travels@tripbazaar.com'), 2, 'Udaipur Royal Romance', 'udaipur-royal-romance', 'Live like royalty in the City of Lakes with palace stays, private boat rides, rooftop dinners, and traditional Rajasthani cultural experiences designed for couples.', 'Royal romantic experience in Udaipur with palace hotels and cultural activities', 'Udaipur', 4, 4, 38000.00, 12000.00, 7000.00, 'Heritage palace hotel stay, Lake Pichola boat ride, City Palace tour, Traditional dinner with folk dance, Airport transfers, Welcome amenities', 'Airfare, Personal shopping, Tips, Extra activities', 'Package valid for couples only. Subject to hotel availability.', '/assets/images/packages/udaipur-romance.jpg', FALSE, 4.7, 89, 67);

-- Michael's Adventure Packages
INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) VALUES
((SELECT id FROM users WHERE email = 'adventure.hub@tripbazaar.com'), 1, 'Rishikesh Adventure Sports', 'rishikesh-adventure-sports', 'Ultimate adventure package in Rishikesh featuring white water rafting, bungee jumping, rock climbing, and camping under the stars.', 'Thrilling adventure sports package in Rishikesh', 'Rishikesh', 4, 20, 15000.00, 8000.00, 3000.00, 'Adventure sports activities, Camping accommodation, All meals, Safety equipment, Professional guides, Certificates', 'Transportation to Rishikesh, Personal gear, Insurance, Tips', 'Minimum age 12 years. Medical fitness required.', '/assets/images/packages/rishikesh-adventure.jpg', TRUE, 4.7, 89, 67),

((SELECT id FROM users WHERE email = 'adventure.hub@tripbazaar.com'), 1, 'Manali Trekking Expedition', 'manali-trekking-expedition', 'Experience the thrill of trekking in the beautiful Manali region with expert guides, camping equipment, and stunning mountain views.', 'Exciting trekking adventure in Manali mountains', 'Manali', 7, 15, 22000.00, 12000.00, 4000.00, 'Trekking equipment, Professional guides, Camping gear, All meals during trek, Permits and fees, First aid kit', 'Transportation to base camp, Personal trekking gear, Insurance, Tips', 'Good physical fitness required. Weather dependent.', '/assets/images/packages/manali-trek.jpg', FALSE, 4.6, 45, 32);

-- Emma's Luxury Packages  
INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) VALUES
((SELECT id FROM users WHERE email = 'luxury.escapes@tripbazaar.com'), 8, 'Rajasthan Royal Experience', 'rajasthan-royal-experience', 'Live like royalty in magnificent palaces of Rajasthan. Stay in heritage hotels, enjoy private tours, royal dining experiences, and cultural performances.', 'Luxury royal experience in Rajasthan palaces', 'Rajasthan', 8, 8, 120000.00, 40000.00, 25000.00, 'Palace hotel accommodation, Private guided tours, Royal dining experiences, Cultural shows, Luxury transportation, Butler service', 'Airfare, Shopping, Spa treatments, Tips, Personal expenses', 'Advance booking required. Subject to palace hotel availability.', '/assets/images/packages/rajasthan-luxury.jpg', TRUE, 4.9, 87, 45),

((SELECT id FROM users WHERE email = 'luxury.escapes@tripbazaar.com'), 8, 'Kerala Luxury Backwaters', 'kerala-luxury-backwaters', 'Experience the serene backwaters of Kerala in ultimate luxury with premium houseboats, gourmet dining, and spa treatments.', 'Luxury houseboat experience in Kerala backwaters', 'Kerala', 5, 6, 75000.00, 25000.00, 15000.00, 'Luxury houseboat, Gourmet meals, Spa treatments, Private tours, Airport transfers, Welcome amenities', 'Airfare, Personal shopping, Tips, Adventure activities', 'Weather dependent. Advance booking recommended.', '/assets/images/packages/kerala-luxury.jpg', FALSE, 4.8, 92, 56);

-- David's Family Packages
INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) VALUES
((SELECT id FROM users WHERE email = 'family.fun@tripbazaar.com'), 3, 'Goa Family Beach Holiday', 'goa-family-beach-holiday', 'Perfect family beach vacation in Goa with kids clubs, water sports, family resorts, and safe beach activities for all family members.', 'Family-friendly Goa beach holiday with kids activities', 'Goa', 5, 20, 35000.00, 20000.00, 8000.00, 'Family resort accommodation, Kids club access, Beach activities, Water sports, All meals, Airport transfers', 'Airfare, Personal expenses, Shopping, Alcoholic beverages, Tips', 'Child-friendly activities included. Safety supervision provided.', '/assets/images/packages/goa-family.jpg', TRUE, 4.6, 134, 98),

((SELECT id FROM users WHERE email = 'family.fun@tripbazaar.com'), 3, 'Rajasthan Family Heritage Tour', 'rajasthan-family-heritage', 'Explore the rich heritage of Rajasthan with family-friendly accommodations, cultural shows, camel rides, and educational activities for children.', 'Educational and fun family tour of Rajasthan heritage sites', 'Rajasthan', 7, 25, 42000.00, 25000.00, 10000.00, 'Heritage hotel stays, Guided tours, Camel safari, Cultural shows, All meals, Transportation', 'Airfare, Shopping, Personal expenses, Tips', 'Suitable for all ages. Educational activities included.', '/assets/images/packages/rajasthan-family.jpg', FALSE, 4.5, 78, 45);

-- Lisa's Beach Packages
INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) VALUES
((SELECT id FROM users WHERE email = 'beach.paradise@tripbazaar.com'), 4, 'Andaman Tropical Paradise', 'andaman-tropical-paradise', 'Escape to the pristine beaches of Andaman with crystal clear waters, coral reefs, water sports, and beachside relaxation in tropical paradise.', 'Tropical beach getaway in Andaman with water sports and relaxation', 'Andaman Islands', 6, 15, 48000.00, 24000.00, 12000.00, 'Beach resort accommodation, Scuba diving session, Island hopping tour, Water sports, All meals, Airport transfers, Snorkeling equipment', 'Airfare, Personal expenses, Tips, Extra diving sessions, Shopping', 'Swimming ability required for water sports. Weather dependent activities.', '/assets/images/packages/andaman-beach.jpg', TRUE, 4.7, 112, 78),

((SELECT id FROM users WHERE email = 'beach.paradise@tripbazaar.com'), 4, 'Kerala Backwater Cruise', 'kerala-backwater-cruise', 'Serene backwater experience in Kerala with traditional houseboat stays, local cuisine, village visits, and peaceful cruising through coconut groves.', 'Peaceful Kerala backwater cruise with houseboat experience', 'Kerala', 4, 12, 28000.00, 14000.00, 8000.00, 'Traditional houseboat stay, Kerala cuisine meals, Village tour, Sunset cruise, Fishing experience, Local guide', 'Transportation to departure point, Personal expenses, Tips, Shopping', 'Suitable for all ages. Vegetarian meal options available.', '/assets/images/packages/kerala-backwaters.jpg', FALSE, 4.6, 87, 65);

-- Robert's Mountain Packages
INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) VALUES
((SELECT id FROM users WHERE email = 'mountain.trails@tripbazaar.com'), 5, 'Himachal Hill Station Tour', 'himachal-hill-station-tour', 'Explore the beautiful hill stations of Himachal Pradesh including Shimla, Manali, and Dharamshala with scenic views, pleasant weather, and mountain adventures.', 'Complete Himachal hill station tour with scenic mountain views', 'Himachal Pradesh', 8, 20, 32000.00, 18000.00, 8000.00, 'Hotel accommodations, All meals, Local sightseeing, Transportation, Professional guide, Entry fees to attractions', 'Airfare to nearest airport, Personal expenses, Tips, Adventure activities, Shopping', 'Weather dependent. Warm clothing recommended for higher altitudes.', '/assets/images/packages/himachal-hills.jpg', TRUE, 4.5, 95, 62),

((SELECT id FROM users WHERE email = 'mountain.trails@tripbazaar.com'), 5, 'Leh Ladakh Adventure', 'leh-ladakh-adventure', 'Epic journey to the roof of the world with breathtaking landscapes, high altitude lakes, monasteries, and unique Ladakhi culture in the Himalayas.', 'High altitude adventure in Leh Ladakh with stunning landscapes', 'Leh Ladakh', 10, 12, 55000.00, 35000.00, 15000.00, 'Hotel and camp accommodation, All meals, Oxygen cylinder support, Local permits, Professional guide, Monastery visits', 'Airfare, Personal gear, Tips, Personal expenses, Medical insurance', 'Medical fitness required. High altitude acclimatization needed.', '/assets/images/packages/leh-ladakh.jpg', FALSE, 4.8, 67, 38);

-- Priya's Cultural Packages
INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) VALUES
((SELECT id FROM users WHERE email = 'cultural.journeys@tripbazaar.com'), 6, 'Golden Triangle Cultural Tour', 'golden-triangle-cultural-tour', 'Discover India\'s rich heritage through Delhi, Agra, and Jaipur with guided tours of monuments, local markets, traditional crafts, and authentic cultural experiences.', 'Classic Golden Triangle tour with cultural immersion and heritage sites', 'Delhi-Agra-Jaipur', 6, 25, 25000.00, 15000.00, 6000.00, 'Heritage hotel stays, Professional guide, Monument entry fees, Cultural shows, Local cuisine meals, Transportation', 'Airfare, Personal shopping, Tips, Extra activities, Personal expenses', 'Comfortable walking required. Respectful dress code for religious sites.', '/assets/images/packages/golden-triangle.jpg', TRUE, 4.6, 143, 89),

((SELECT id FROM users WHERE email = 'cultural.journeys@tripbazaar.com'), 6, 'South India Temple Trail', 'south-india-temple-trail', 'Spiritual journey through South India\'s magnificent temples, traditional arts, classical music, local festivals, and authentic South Indian culture.', 'Spiritual and cultural exploration of South Indian temples and traditions', 'South India', 9, 18, 38000.00, 22000.00, 9000.00, 'Temple town accommodations, Temple tours with priest, Classical music performance, Traditional craft workshop, South Indian meals, Local guide', 'Airfare, Personal donations at temples, Shopping, Tips, Personal expenses', 'Respectful attire required. Remove footwear at temples. Vegetarian meals preferred.', '/assets/images/packages/south-temple-trail.jpg', FALSE, 4.7, 76, 54);

-- James's Wildlife Packages
INSERT INTO packages (provider_id, category_id, title, slug, description, short_description, destination, duration_days, max_guests, base_price, child_price, extra_room_price, inclusions, exclusions, terms_conditions, featured_image, is_featured, rating, total_reviews, total_bookings) VALUES
((SELECT id FROM users WHERE email = 'wildlife.safaris@tripbazaar.com'), 7, 'Ranthambore Tiger Safari', 'ranthambore-tiger-safari', 'Experience the thrill of spotting Royal Bengal Tigers in their natural habitat at Ranthambore National Park with expert naturalists and comfortable jungle lodges.', 'Tiger safari adventure in famous Ranthambore National Park', 'Ranthambore', 4, 16, 22000.00, 12000.00, 5000.00, 'Jungle lodge accommodation, Safari jeep rides, Expert naturalist guide, Park entry fees, All meals, Wildlife photography tips', 'Transportation to park, Personal gear, Tips, Extra safaris, Personal expenses', 'Early morning safaris included. Quiet behavior required during safaris.', '/assets/images/packages/ranthambore-safari.jpg', TRUE, 4.8, 98, 72),

((SELECT id FROM users WHERE email = 'wildlife.safaris@tripbazaar.com'), 7, 'Jim Corbett Wildlife Experience', 'jim-corbett-wildlife-experience', 'Explore India\'s oldest national park with diverse wildlife, elephant safaris, bird watching, and conservation programs in the beautiful Corbett landscape.', 'Wildlife experience in historic Jim Corbett National Park', 'Jim Corbett', 5, 20, 18000.00, 10000.00, 4000.00, 'Forest lodge stay, Jeep and elephant safaris, Bird watching tour, Nature walks, Conservation center visit, All meals', 'Transportation to park, Personal equipment, Tips, Extra activities, Personal expenses', 'Suitable for all ages. Silence required during wildlife viewing.', '/assets/images/packages/corbett-wildlife.jpg', FALSE, 4.5, 84, 56); 