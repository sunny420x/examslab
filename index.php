<?php
include 'database.php';
if(isset($_GET['name'])) {
    $name = $_GET['name'];
} else {
    header("Location: menu.php");
    die();
}

$check_name = mysqli_prepare($db, "SELECT * FROM questions WHERE name = ?");
mysqli_stmt_bind_param($check_name, 's', $name);
mysqli_stmt_execute($check_name);
$check_name = mysqli_stmt_get_result($check_name);

if(mysqli_num_rows($check_name) <= 0) {
    header("Location: menu.php");
    die();
}

$sum = 0;
?>
<html>
    <head>
        <title><?=htmlentities($name)?> | Exams Lab</title>
        <?php
        include 'include/head.php';
        ?>
    </head>
    <body>
        <div class="container my-10 p-20">
            <div class="w-100 card white rounded p-30">
                <div>
                    <div class="mb-20">
                        <button onclick="window.location.href='exam.php?name=<?=$name?>'" class="btn red">เริ่มทำแบบทดสอบ</button>
                    </div>
                    <h2 class="bl-orange mt-0">Most highest scores!</h2>
                    <table class="full">
                        <tr>
                            <th>Student_ID</th>
                            <th>Score</th>
                        </tr>
                        <?php
                        $get_amounts = mysqli_prepare($db, "SELECT id FROM questions WHERE name = ? ORDER BY id ASC");
                        mysqli_stmt_bind_param($get_amounts, 's', $name);
                        mysqli_stmt_execute($get_amounts);
                        $get_amounts = mysqli_stmt_get_result($get_amounts);
                        ?>
                        <?php
                        $getrows = mysqli_prepare($db, "SELECT r.student_id, r.name, r.answer, COUNT(*) as scores FROM results as r JOIN questions as q ON r.answer = q.answer AND q.id = r.question_id WHERE r.name = ? GROUP BY r.student_id ORDER BY scores DESC");
                        mysqli_stmt_bind_param($getrows, 's', $name);
                        mysqli_stmt_execute($getrows);
                        $getrows = mysqli_stmt_get_result($getrows);

                        while($row = mysqli_fetch_array($getrows, MYSQLI_ASSOC)) {
                            $sum += $row['scores'];
                        ?>
                        <tr>
                            <td><?=$row['student_id']?></td>
                            <td><?=$row['scores']?>/<?=mysqli_num_rows($get_amounts)?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <?php
                        $counts_all_peoples = mysqli_prepare($db, "SELECT count(DISTINCT student_id) as counts FROM results WHERE name = ?");
                        mysqli_stmt_bind_param($counts_all_peoples, 's', $name);
                        mysqli_stmt_execute($counts_all_peoples);
                        $counts_all_peoples = mysqli_stmt_get_result($counts_all_peoples);

                        $all_peoples = mysqli_fetch_array($counts_all_peoples, MYSQLI_ASSOC);

                        $counts_all_peoples = $all_peoples['counts'];

                        if($counts_all_peoples == 0) {
                            die();
                        }
                    ?>
                    <h2 class="bl-red">Percentage.</h2>
                    <p>There are <?=$counts_all_peoples?> students</p>
                    <p>Mean Score are <?=round($sum/$counts_all_peoples, 2)?></p>
                    <table class="full">
                            <?php
                            $i = 1;
                            $get_amounts = mysqli_prepare($db, "SELECT id FROM questions WHERE name = ? ORDER BY id ASC");
                            mysqli_stmt_bind_param($get_amounts, 's', $name);
                            mysqli_stmt_execute($get_amounts);
                            $get_amounts = mysqli_stmt_get_result($get_amounts);
                            while($amounts = mysqli_fetch_array($get_amounts, MYSQLI_ASSOC)) {
                            ?>
                            <tr>
                            <td>exams <?=$i?></td>
                            <?php
                                $get_info = mysqli_prepare($db, "SELECT count(*) as counts FROM results as r JOIN questions as q ON r.answer = q.answer AND q.id = r.question_id WHERE q.id = ?");
                                mysqli_stmt_bind_param($get_info, 's', $amounts['id']);
                                mysqli_stmt_execute($get_info);
                                $get_info = mysqli_stmt_get_result($get_info);
                                while($info = mysqli_fetch_array($get_info, MYSQLI_ASSOC)) {
                            ?>
                                <td><?=$info['counts']?> students (<?=round($info['counts'] / $counts_all_peoples * 100, 2)?>%)</td>
                            <?php
                                }
                            ?>
                            <?php
                            $i++;
                            }
                            ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>