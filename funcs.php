<?php
function human_time_diff($start_date, $end_date = null)
{
    if (is_null($end_date)) {
        $end_date = time();
    }

    $diff = abs($end_date - $start_date);

    if ($diff < 60) {
        return 'just now';
    }

    $intervals = array(
        31556926 => array('year', 31556926),
        2629744 => array('month', 2629744),
        604800 => array('week', 604800),
        86400 => array('day', 86400),
        3600 => array('hour', 3600),
        60 => array('minute', 60),
        1 => array('second', 1)
    );

    $count = 0;
    $result = '';

    foreach ($intervals as $seconds => $data) {
        if ($diff >= $seconds) {
            $count = floor($diff / $seconds);
            $result = $count . ' ' . $data[0] . ($count > 1 ? 's' : '') . ' ago';
            break;
        }
    }

    return $result;
}
?>