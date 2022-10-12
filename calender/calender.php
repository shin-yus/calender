
<?php
require_once 'inc/functions.php'; 

function getholidays(){
try {
    $dbh = db_open();
    $sql = 'SELECT * FROM holiday';
    $stmt = $dbh->query($sql);
    $holidays_date = array();
    foreach($stmt as $out){
        $date_out = strtotime((string) $out['date']);
        $name_out = (string)$out['name'];
        $holidays_date[date('Y-m-d',$date_out)] = $name_out;
    }
} catch (PDOException $e) {
    echo "表示エラー: <br>";
    exit;
}
return $holidays_date;
}

$Holidays_array = getholidays();

function add_Holidays($date,$Holidays_array) {
	if(array_key_exists($date,$Holidays_array)){
		$holidays = "<br/>".$Holidays_array[$date];
		return $holidays; 
	}
}
?>

<?php
date_default_timezone_set('Asia/Tokyo');

if(isset($_GET['ym'])){ 
    $ym = $_GET['ym'];
}else{
    $ym = date('Y-m');
}
 
$timestamp = strtotime($ym . '-01'); 
if($timestamp === false){
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}
 
$today = date('Y-m-j');
 
$html_title = date('F Y', $timestamp);
$prev = date('Y-m', strtotime('-1 month', $timestamp));
$next = date('Y-m', strtotime('+1 month', $timestamp));
$return_today = date('Y-m');
 
$day_count = date('t', $timestamp);
$youbi = date('w', $timestamp);

$weeks = [];
$week = '';

$week .= str_repeat('<td></td>', $youbi);
 
for($day = 1; $day <= $day_count; $day++, $youbi++){
    $date = $ym . '-' . $day; 
    $Holidays_day = add_Holidays(date("Y-m-d",strtotime($date)),$Holidays_array);
    if($today == $date){
        $week .= '<td class="today">' . $day .$Holidays_day;
    }elseif(add_Holidays(date("Y-m-d",strtotime($date)),$Holidays_array)){
        $week .= '<td class="holiday">' . $day . $Holidays_day;
    }else{
        $week .= '<td>' . $day;
    }
    $week .= '</td>';
    
    if($youbi % 7 == 6 || $day == $day_count){
        if($day == $day_count){
            $week .= str_repeat('<td></td>', 6 - ($youbi % 7));
        }
        $weeks[] = '<tr>' . $week . '</tr>';
        $week = '';
    }
}   
?>

<?php include __DIR__ . '/inc/header.php'; ?>
         
    <div class="container">
        <h3 class="title">
            <a id="prev" href="?ym=<?= $prev ?>">&lt;&lt;&lt;</a><?= $html_title; ?><a id="next" href="?ym=<?= $next; ?>">&gt;&gt;&gt;</a>
        </h3>
        <table>
            <tr>
                <th>Sun</th>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
            </tr>
            <p class="day">
            <?php
                foreach ($weeks as $week) {
                    echo $week;
                }
            ?>
            </p>
        </table>
        <h3><a id="return" href="?ym=<?= $return_today; ?>">r e t u r n</a></h3>
    </div>   

<?php include __DIR__ . '/inc/footer.php'; ?>