<?php
if(!isset($_GET['name'])) {
    die("โปรดใส่ชื่อแบบทดสอบ");
} else {
    $name = $_GET['name'];
}
?>
<html>
    <head>
        <title><?=htmlentities($name)?> | Sunny420x</title>
        <?php
        include 'include/head.php';
        require 'database.php';
        ?>
    </head>
    <body>
        <div class="container my-10 p-20">
            <div class="card white rounded p-30">
                <h2 class="bl-red mt-10 mb-30">แบบทดสอบ <?=htmlentities($name)?></h2>
                <hr>
                <?php
                $query = mysqli_prepare($db, "SELECT * FROM questions WHERE name = ? ORDER BY id ASC");
                mysqli_stmt_bind_param($query, 's', $name);
                mysqli_stmt_execute($query);
                $rows = mysqli_stmt_get_result($query);
                ?>
                <form action="result.php?name=<?=$name?>" method="POST">                    
                    <?php
                    $i = 1;
                    while($row = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
                    ?>
                        <h3><?=$i;?>. <?=$row['question']?></h3>
                        <div class="grid-choice strict">
                            <input type="radio" name="<?=$i?>" value="A">
                            <span><?=$row['A']?></span>
                            <input type="radio" name="<?=$i?>" value="B">
                            <span><?=$row['B']?></span>
                            <input type="radio" name="<?=$i?>" value="C">
                            <span><?=$row['C']?></span>
                            <input type="radio" name="<?=$i?>" value="D">
                            <span><?=$row['D']?></span>
                        </div>
                    <?php
                        $i++;
                    }
                    ?>
                    <hr class="mt-30">
                    <div class="mt-20">
                        <input type="text" name="student_id" placeholder="โปรดกรอกรหัสนักศึกษา" length="8" required>
                        <input type="submit" value="ส่งคำตอบ" class="btn darkblue">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>