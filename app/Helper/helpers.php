<?php
    function shortAbsolute_time($date)
    {
        $diff = abs(strtotime($date) - strtotime(now()));
                                    $time = '';
                                    $year = 31536000;
                                    $month = 2592000;
                                    $week = 604800;
                                    $day = 86400;
                                    $hour = 3600;
                                    $minute = 60;
                                    $second = 1;
                                    if ($diff >= $year) {
                                        $count = round($diff / $year);
                                        if ($count > 1) {
                                            $time = $count . ' Years ago';
                                        } else {
                                            $time = 'Year ago';
                                        }
                                    } elseif ($diff >= $month && $diff < $year) {
                                        $count = round($diff / $month);
                                        if ($count > 1) {
                                            $time = $count . ' Months ago';
                                        } else {
                                            $time = 'Month ago';
                                        }
                                    } elseif ($diff >= $week && $diff < $month) {
                                        $count = round($diff / $week);
                                        if ($count > 1) {
                                            $time = $count . ' Weeks ago';
                                        } else {
                                            $time = 'Week ago';
                                        }
                                    } elseif ($diff >= $day && $diff < $week) {
                                        $count = round($diff / $day);
                                        if ($count > 1) {
                                            $time = $count . ' Days ago';
                                        } else {
                                            $time = 'Day ago';
                                        }
                                    } elseif ($diff >= $hour && $diff < $day) {
                                        $count = round($diff / $hour);
                                        if ($count > 1) {
                                            $time = $count . ' Hours ago';
                                        } else {
                                            $time = 'Hour ago';
                                        }
                                    } elseif ($diff > $minute && $diff < $hour) {
                                        $count = round($diff / $minute);
                                        if ($count > 1) {
                                            $time = $count . ' Minutes ago';
                                        } else {
                                            $time = 'Minute ago';
                                        }
                                    } elseif ($diff > $second && $diff < $minute) {

                                            $time ='now';

                                    }
return $time;

}
