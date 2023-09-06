<head>
    <?php
    include 'include/head.php';
    ?>
</head>

<body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
require 'database.php';

$score = 0;
$i = 1;
$input = array();
$name = $_GET['name'];
$student_id = $_POST['student_id'];

if(!preg_match("/^[0-9]+$/", $student_id)) {
    die("Student ID must be numbers.");
}

while(!empty($_POST[strval($i)])) {
    array_push($input,$_POST[strval($i)]);
    $i++;
}

$get_answer = mysqli_prepare($db, "SELECT id,answer FROM questions WHERE name = ? ORDER BY id ASC");
mysqli_stmt_bind_param($get_answer, 's', $name);
mysqli_stmt_execute($get_answer);
$answers = mysqli_stmt_get_result($get_answer);

$counts = mysqli_num_rows($answers);
mysqli_stmt_close($get_answer);

$j = 0;
while($answer = mysqli_fetch_array($answers, MYSQLI_ASSOC)) {
    if($input[$j] == NULL || $input[$j] == '') {
        echo "<script>
        Swal.fire(
            'Error!!',
            'Please fill all the answers.',
            'error'
        ).then(() => {
            window.location.href='index.php?name=$name';
        })
        </script>";
    }
    if($answer['answer'] == $input[$j]) {
        $score++;
    }
    $insert_result = mysqli_prepare($db, "INSERT INTO results(student_id,question_id,answer,name) VALUES(?,?,?,?)");
    mysqli_stmt_bind_param($insert_result, 'ssss', $student_id, $answer['id'], $input[$j], $name);
    mysqli_stmt_execute($insert_result);
    $j++;
}

echo "<script>
Swal.fire(
    'Good Job!',
    'Your score is $score/$counts',
    'success'
).then(() => {
    window.location.href='index.php?name=$name';
})
</script>";

mysqli_stmt_close($insert_result);
?>
</body>