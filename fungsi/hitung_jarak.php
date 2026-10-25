<?php
function hitungJarak($lat1, $lon1, $lat2, $lon2) {
    // Radius Bumi dalam meter
    $earthRadius = 6371000; 
    
    // Konversi ke Radian
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
         
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    // Hasil dalam meter (Bulatkan 2 desimal)
    return round($earthRadius * $c, 2);
}
?>