<?php
$file = 'resources/views/user/dashboard.blade.php';
$content = file_get_contents($file);

// Fix Certification Tab x-data
$oldXData = '/<div x-show="subTab === \'certification\'"[^>]+x-data="{[^}]+additionalInfo: \{[^}]+},[^}]+certifications:[^,]+,[^}]+awards:[^,]+,/s';

$newXData = '<div x-show="subTab === \'certification\'" x-cloak class="px-6 py-6 pb-10" x-data="{ 
                                    additionalInfo: {
                                        standard_certification: {{ Js::from($businessProfile?->standard_certification ?? $businessProfile?->additional_info[\'standard_certification\'] ?? \'\') }},
                                        membership_affiliations: {{ Js::from($businessProfile?->membership_affiliations ?? $businessProfile?->additional_info[\'membership_affiliations\'] ?? \'\') }},
                                        award_recognition: {{ Js::from($businessProfile?->award_recognition ?? $businessProfile?->additional_info[\'award_recognition\'] ?? \'\') }},
                                    },
                                    certifications: {{ Js::from($businessProfile?->certifications ?? $businessProfile?->additional_info[\'certifications\'] ?? []) }},
                                    awards: {{ Js::from($businessProfile?->awards ?? $businessProfile?->additional_info[\'awards\'] ?? []) }},';

$content = preg_replace($oldXData, $newXData, $content);

if ($content) {
    file_put_contents($file, $content);
    echo "Successfully updated Certification tab data binding.\n";
} else {
    echo "Failed to find Certification tab x-data.\n";
}
