<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($num)
    {
        return 'Rp ' . number_format($num, 0, ',', '.');
    }
}
