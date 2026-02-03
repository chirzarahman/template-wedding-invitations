CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_title` varchar(255) DEFAULT 'syifazharstudio',
  `hero_title` varchar(255) DEFAULT 'Undangan Digital',
  `hero_description` text,
  `logo_text` varchar(50) DEFAULT 'syifazharstudio',
  `address` text,
  `map_embed` text,
  `instagram_link` varchar(255) DEFAULT '#',
  `tiktok_link` varchar(255) DEFAULT '#',
  `whatsapp_number` varchar(50) DEFAULT '#',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`id`, `site_title`, `hero_title`, `hero_description`, `logo_text`, `address`, `map_embed`, `instagram_link`, `tiktok_link`, `whatsapp_number`) VALUES
(1, 'syifazharstudio Landing Page', 'Undangan <span class="text-champagne font-serif italic pr-1">Digital</span>', 'Bagikan kabar bahagia dengan sentuhan elegan dan modern.', 'syifazharstudio', 'Jl. Sugeng Jeroni No.48A, Gedongkiwo, Kec. Mantrijeron, Kota Yogyakarta, DIY 55142', '<iframe allowfullscreen="" height="100%" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.9234479914755!2d110.36070637411626!3d-7.802661677395027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a57864c3c3a9d%3A0x6b4f73024840506!2sYogyakarta%2C%20Yogyakarta%20City%2C%20Special%20Region%20of%20Yogyakarta!5e0!3m2!1sen!2sid!4v1714886618456!5m2!1sen!2sid" style="border:0; filter: grayscale(1) invert(0.9) contrast(1.2);" width="100%"></iframe>', '#', '#', '#');

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`name`) VALUES 
('Spesial Foto'), 
('Tanpa Foto'), 
('Minimalist'), 
('Vintage'), 
('Adat');

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discounted_price` decimal(10,2) DEFAULT NULL,
  `image` TEXT DEFAULT NULL,
  `link` varchar(255) DEFAULT '#',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`name`, `category_id`, `price`, `discounted_price`, `image`) VALUES
('Elegant Sage 01', 1, 210000, 132000, 'https://lh3.googleusercontent.com/aida-public/AB6AXuDgDdbPiToFHnhGvBy5uAj7dFniLF-Yo5JtTkO39SIrtiQ1gjDu_sDBoYy-5eJoCnhOyxh9kxiHnbpkPAii-HaS2itAtaTph4KwcQYesopH8D2MRTSj8CKOHLYY5sEJ_r2dle9fTTsH2EY6qU73hLa-ncVxd3OmC8xhonJINeIhF9C8cdqIl8wpGTMKvblZNJAlfnYlCBHQpk0cV6mfZW5AA9SOhcFiYXApJXMdqmLOpCAVJzy8sejUeceWJAT3LuK45DuKuWdKl5Sq'),
('Gold Floral 02', 1, 210000, 132000, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCERkE_-yJD0up8WdnnEWWo9T3AbwUlUwMHMJxCX8Q8kYZ07b3d3l0-fhYbGQBlKglY7kd9qWieN8vGpqkU2rX3Oqbh3_PiSpCgwFN03IO7mfdmYcMiEoTK92jrVoPenrM02D6566StGIeDCQM10kgrCj2vIdH3Hlu2Y2Rm5PJ5Ih65qyh8LsgFTT4mzLuO1z0KcDXZXCfpCVKE8lofMlBh-qYZXmriAHcIOD5A5qGUB_TPrC-8H9V_7ZMPUfm_j-qxAYdVR1xvMhbQ'),
('Modern Leaf 03', 1, 210000, 132000, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAeV5J2om_cFGqlHaxefwoG_A2JMcwDN4gIwLz-lD9ZUucxVlLQTGjpiVAXeQvova2rwaeNCVyJKdIatf23fhlkw8Uy8pGHTVlp1Yep9VrG5qyEFYzdLC-xaYboN-yx9lny-F-0msaZFCmmtumKoRKaf6oDwLImecMFGfJxJVgKygGIxWqQj0c6Pj1zajujnmgaaEsRKLAuM8xK-92seXqjcNSzVY3RypBKZAmDNcO0u62bCOFF15tdCK2r6BI6tsgFQokc4dyP221p'),
('Classic Love 04', 1, 210000, 132000, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCKPJZF0QVfOyBYBzFC9zvUKDQrp3ZPcEfYiTFcua94rq1HB_UAqF3SdPVHyocdvoVm8dSEfPYlGA45om8GrMhKVsM1OKoxCX64rixaFXSAttJfj7vXwrlkW-MdENylsKY19qjwi-hjEVhx3wynI6aSMscHkZoAwyGV7Kapc-WS7Ud0DTnEAB8qO632I1UOHm_6PSzzCSiaFivA48jPHXbjmvIfJIl92lncs4AuNFriliEvmbSXWxH_bd5byI0k6L9b79btTrSzt9q6');

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `couple_name` varchar(100) NOT NULL,
  `rating` int(1) DEFAULT 5,
  `quote` text NOT NULL,
  `image` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `testimonials` (`couple_name`, `rating`, `quote`, `image`) VALUES
('Putri & Dimas', 5, 'Suka banget sama hasilnya! Admin ramah, revisi cepat, dan desainnya elegan banget sesuai ekspektasi. Recommended buat yang mau nikah!', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAG_1JPzYvdt4JMz4OYN-_yf_pasvwbQDMDuXOLTCRryzNnuP6tmTmILljKtxaOv__Z7PllszL31q-spCOeRQKVPQSCTzO2k_CVtvludJgLpPfa4FHsKKd2_qDhJC-FABWSTYF0Kcg2GO6lvNl7_SFlu4djWYFE5q0riQTreRDO1MLH5Qc2Wu00MVC5p4B_WOHLMzQzv6Kafnrng3Ene0dCymum0qUM5qOHV8HXk5i4gJDnCEOGdsAxMDEedWP-vLe7__35R4uRGBY'),
('Sarah & Rizky', 5, 'Prosesnya sat set banget, sehari jadi. Fiturnya juga lengkap, tamu undangan pada muji desain undangannya bagus. Makasih syifazharstudio!', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAeV5J2om_cFGqlHaxefwoG_A2JMcwDN4gIwLz-lD9ZUucxVlLQTGjpiVAXeQvova2rwaeNCVyJKdIat23fhlkw8Uy8pGHTVlp1Yep9VrG5qyEFYzdLC-xaYboN-yx9lny-F-0msaZFCmmtumKoRKaf6oDwLImecMFGfJxJVgKygGIxWqQj0c6Pj1zajujnmgaaEsRKLAuM8xK-92seXqjcNSzVY3RypBKZAmDNcO0u62bCOFF15tdCK2r6BI6tsgFQokc4dyP221p'),
('Bella & Andre', 5, 'Harganya terjangkau tapi kualitasnya premium. Seneng banget bisa custom lagu favorit kita berdua. Sukses terus ya!', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCERkE_-yJD0up8WdnnEWWo9T3AbUUlUwMHMJxCX8Q8kYZ07b3d3l0-fhYbGQBlKglY7kd9qWieN8vGpqkU2rX3Oqbh3_PiSpCgwFN03IO7mfdmYcMiEoTK92jrVoPenrM02D6566StGIeDCQM10kgrCj2vIdH3Hlu2Y2Rm5PJ5Ih65qyh8LsgFTT4mzLuO1z0KcDXZXCfpCVKE8lofMlBh-qYZXmriAHcIOD5A5qGUB_TPrC-8H9V_7ZMPUfm_j-qxAYdVR1xvMhbQ');
