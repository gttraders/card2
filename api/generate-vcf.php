<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Set headers for VCF download
header('Content-Type: text/vcard; charset=utf-8');
header('Content-Disposition: attachment; filename="contact.vcf"');

// Get site settings
$settings = getSiteSettings();

// Generate VCF content
$vcf = generateVCF($settings);

echo $vcf;
?>