<?php

if (!function_exists('timeAgo')) {
    function timeAgo($datetime) {
        if (empty($datetime)) {
            return 'Unknown';
        }
        
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam lalu';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return $days . ' hari lalu';
        } else {
            return date('d M Y', $time);
        }
    }
}

if (!function_exists('getStatusBadgeColor')) {
    function getStatusBadgeColor($status) {
        if (empty($status)) {
            return 'secondary';
        }
        
        $status = strtolower($status);
        switch ($status) {
            case 'completed':
            case 'approved':
            case 'delivered':
            case 'received':
                return 'success';
            case 'pending':
            case 'waiting':
                return 'warning';
            case 'rejected':
            case 'cancelled':
            case 'failed':
                return 'danger';
            case 'processing':
            case 'in progress':
            case 'shipped':
                return 'info';
            default:
                return 'secondary';
        }
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        if (empty($amount)) {
            return 'Rp 0';
        }
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('getStatusText')) {
    function getStatusText($status) {
        $status = strtolower($status);
        $statusMap = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
            'processing' => 'Diproses',
            'cancelled' => 'Dibatalkan'
        ];
        
        return $statusMap[$status] ?? ucfirst($status);
    }
}