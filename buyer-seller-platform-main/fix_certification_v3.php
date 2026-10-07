<?php
$file = 'resources/views/user/dashboard.blade.php';
$content = file_get_contents($file);

$search1 = "standard_certification: '{{ \$businessProfile->standard_certification ?? \$businessProfile->additional_info['standard_certification'] ?? '' }}',";
$replace1 = "standard_certification: {{ Js::from(\$businessProfile?->standard_certification ?? \$businessProfile?->additional_info['standard_certification'] ?? '') }},";

$search2 = "membership_affiliations: '{{ \$businessProfile->membership_affiliations ?? \$businessProfile->additional_info['membership_affiliations'] ?? '' }}',";
$replace2 = "membership_affiliations: {{ Js::from(\$businessProfile?->membership_affiliations ?? \$businessProfile?->additional_info['membership_affiliations'] ?? '') }},";

$search3 = "award_recognition: '{{ \$businessProfile->award_recognition ?? \$businessProfile->additional_info['award_recognition'] ?? '' }}',";
$replace3 = "award_recognition: {{ Js::from(\$businessProfile?->award_recognition ?? \$businessProfile?->additional_info['award_recognition'] ?? '') }},";

$search4 = "certifications: {{ Js::from(\$businessProfile->certifications ?? \$businessProfile->additional_info['certifications'] ?? []) }},";
$replace4 = "certifications: {{ Js::from(\$businessProfile?->certifications ?? \$businessProfile?->additional_info['certifications'] ?? []) }},";

$search5 = "awards: {{ Js::from(\$businessProfile->awards ?? \$businessProfile->additional_info['awards'] ?? []) }},";
$replace5 = "awards: {{ Js::from(\$businessProfile?->awards ?? \$businessProfile?->additional_info['awards'] ?? []) }},";

$content = str_replace($search1, $replace1, $content);
$content = str_replace($search2, $replace2, $content);
$content = str_replace($search3, $replace3, $content);
$content = str_replace($search4, $replace4, $content);
$content = str_replace($search5, $replace5, $content);

if ($content) {
    file_put_contents($file, $content);
    echo "Successfully updated Certification tab strings.\n";
}
