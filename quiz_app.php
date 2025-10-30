<?php
//quiz questions and answers array
$quizArray = [
        // topic 1
        "Music" => [
            [
            "question" => "Which songs are performed by Michael Jackson?",
            "answers" => ["Beautiful Things", "Dangerous", "Roar", "Why", "Thriller"],
            "correct" => ["Dangerous", "Thriller"]
            ],
            [
            "question" => "What was Elvis Presley's first No. 1 hit in the United States?",
            "answers" => ["Heartbreak Hotel", "Can't Help Falling in Love", "If I Can Dream"],
            "correct" => ["Heartbreak Hotel"]
            ],
            [  
            "question" => "Which classical composer was deaf?",
            "answers" => ["Johann Sebastian Bach", "Ludwig van Beethoven", "Antonio Vivaldi"],
            "correct" => ["Ludwig van Beethoven"]
            ],
            [
            "question" => "True or false: Billie Eilish's real name is Billie Eilish Pirate Baird O'Connell?",
            "answers" => ["True", "False"],
            "correct" => ["True"]
            ],
            [
            "question" => "Which movies' songs are written by Lin-Manuel Miranda?",
            "answers" => ["Moana (2016)", "Frozen (2013)", "Hamilton (2020)", "Encanto (2021)", "Aladdin (2019)"],
            "correct" => ["Moana (2016)", "Hamilton (2020)", "Encanto (2021)"]
            ]
        ],

        // topic 2
        "Movie and Series" => [
            [
            "question" => "Name 2 stars of the movie 'Interstellar'.",
            "answers" => ["Tom Hanks", "Matthew McConaughey", "Timothee Chalamet", "Scarlett Johansson", "Zendaya"],
            "correct" => ["Matthew McConaughey", "Timothee Chalamet"]
            ],
            [
            "question" => "Which one of Voldemort's Horcruxes was destroyed last in 'Harry Potter and the Deathly Hallows - Part 2'?",
            "answers" => ["Tom Riddle's Diary", "Harry Potter himself", "Nagini, the snake"],
            "correct" => ["Nagini, the snake"]
            ],
            [
            "question" => "For what show was Peter Dinklage nominated for an Emmy for all seven seasons?",
            "answers" => ["Game of Thrones", "Dexter", "How to Become a Tyrant"],
            "correct" => ["Game of Thrones"]
            ],
            [
            "question" => "What instrument did The Mother play on How I met your Mother?",
            "answers" => ["Guitar", "Cello", "Bass"],
            "correct" => ["Bass"]
            ],
            [
            "question" => "What was the name of Sponge Bob's driving instructor?",
            "answers" => ["Ms. Dolores", "Mrs. Puff", "Mr. Crab"],
            "correct" => ["Mrs. Puff"]
            ]
        ],

        // Topic 3
        "History" => [
        [
            "question" => "During which war was the Christmas Truce called?",
            "answers" => ["The Korean War", "World War 2", "World War 1"],
            "correct" => ["World War 1"]
        ],
        [
            "question" => "Which two options are the seven wonders of ancient world?",
            "answers" => ["The Hanging Gardens", "The Bermuda Triangle", "The Statue of Christ the Redeemer", "The Statue of Zeus", "The Colosseum"],
            "correct" => ["The Hanging Gardens", "The Statue of Zeus"]
        ],
        [
            "question" => "What year did Google launch?",
            "answers" => ["1990", "2001", "1998"],
            "correct" => ["1998"]
        ],
        [
            "question" => "Who was the first woman to be elected vice president of the United States?",
            "answers" => ["Kamala Harris", "Hillary Clinton", "Victoria Woodhull"],
            "correct" => ["Kamala Harris"]
        ],
        [
            "question" => "What year was the first iPhone released?",
            "answers" => ["2000", "2012", "2007"],
            "correct" => ["2007"]
        ]
    ]
];

// quiz logic
$stage = "choose"; //default
$chosenCat = null;
$q_index = null;
$question = null;
$userAnswers = [];
$result = "";

// user chooses category
if (isset($_POST['submit_category'])) {
    $chosenCat = $_POST['category'] ?? "";
    if ($chosenCat && isset($quizArray[$chosenCat])) {
        $questions = $quizArray[$chosenCat];
        $q_index = array_rand($questions);
        $question = $questions[$q_index];
        $stage = "question";
    }
}

// user chose their answer and submitted
if (isset($_POST['submit_answer'])) {
    $chosenCat = $_POST['category'];
    $q_index = $_POST['q_index'];
    $question = $quizArray[$chosenCat][$q_index];
    
    $userAnswers = $_POST['answers'] ?? [];
    $correct = $question['correct'];
    
    sort($userAnswers);
    sort($correct);
    
    // printing result message with colorful style
    if ($userAnswers === $correct) {
        $result = "<p style='color:green; font-weight:bold;'>Correct! </p>";
    } else {
        $result = "<p style='color:red; font-weight:bold;'>Wrong! </p>";
    }
    
    $stage = "result";
}

// next question button logic
if (isset($_POST['next_question'])) {
    $chosenCat = $_POST['category'];
    if ($chosenCat && isset($quizArray[$chosenCat])) {
        $questions = $quizArray[$chosenCat];
        $q_index = array_rand($questions);
        $question = $questions[$q_index];
        $stage = "question"; // show another random question
    }
}

// home button logic
if (isset($_POST['go_home'])) {
    $stage = "choose"; // back to category selection
}

?>

<!-- main html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quiz App - Rojan Jafarnezhad</title>

    <!-- stylesheet -->
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        h1 {
            margin: 0;
            padding-bottom: 5px;
        }
        form {
            display: inline-block;
            background: #fff;
            padding: 20px;
        }
        label, p {
            font-size: 16px;
            margin: 8px 0;
            display: block;
            padding: 15px;
            border-radius: 12px;
        }
        select, input[type="radio"], input[type="checkbox"] {
            margin: 6px 0;
        }
        button {
            padding: 10px 20px;
            margin: 8px 4px;
            background-color: #4CAF50;
            font-size: 14px;
        }
        .correct {
            background-color: #a6f3a6;
        }
        .wrong {
            background-color: #f3a6a6;
        }
    </style>

    <!-- javascript validation -->
    <script>
        function validateCategory(){
            let cat = document.getElementById("category").value;

            if (!cat) {
                alert("Please choose a category!");
                return false;
            }
            return true;
        }
        function validateAnswer(){
            let inputs = document.querySelectorAll("input[name='answers[]']");
            let checked = [...inputs].some(i => i.checked);

            if (!checked) {
                alert("Please select at least one answer!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h1 style="text-align: center;">FORM</h1>

<?php
    if ($stage === "choose") { ?>
    <!-- category selection -->
    <form method="post" onsubmit="return validateCategory()">
        <label for="category">Choose Category:</label>
        <select name="category" id="category">
            <option value="">--Select a Category--</option>
        
            <?php
            foreach (array_keys($quizArray) as $cat) {
                echo "<option value=\"$cat\">$cat</option>";
            }
            ?>

        </select><br><br>
        <button type="submit" name="submit_category">START</button>
    </form>

<?php 
    } elseif ($stage === "question") {
?>
    <!-- show questions and options (main part of the quiz) -->
    <form method="post" onsubmit="return validateAnswer()">
        <p><strong>
            <?php
            echo $question['question']; 
            ?>
        </strong></p>

        <!-- if the question has more than one answers -->
        <?php
        $isMulti = count($question['correct']) > 1;

        foreach ($question['answers'] as $ans) {
            $inputType = $isMulti ? "checkbox" : "radio";
            echo "<label><input type=\"$inputType\" name=\"answers[]\" value=\"$ans\"> $ans</label><br>";
        }
        ?>

        <input type="hidden" name="category" value="<?php echo $chosenCat; ?>">
        <input type="hidden" name="q_index" value="<?php echo $q_index; ?>">
        <br>
        <button type="submit" name="submit_answer">Submit</button>
    </form>

<?php 
    } elseif ($stage === "result") { 
?>
    <!-- showing results to the user -->
    <p><strong>
        <?php 
        echo $question['question'];
        ?>
    </strong></p>

<?php
    foreach ($question['answers'] as $ans) {
        $cls = "";

        // if the answer is correct:
        if (in_array($ans, $question['correct'])) $cls = "correct";
        // if the answer was incorrect:
        if (in_array($ans, $userAnswers) && !in_array($ans, $question['correct'])) $cls = "wrong";

        echo "<div class='$cls'>$ans</div>";
    }

    echo $result;
?>

    <!-- NEXT QUESTION and HOME button for better user experience -->
    <form method="post">
        <!-- Next question in same category -->
        <input type="hidden" name="category" value="<?php echo $chosenCat; ?>">
        <button type="submit" name="next_question">Next Question</button>

        <!-- Go back to home -->
        <button type="submit" name="go_home">Home</button>
    </form>

    <!-- source code included -->
    <hr>
    <h3>Source Code:</h3>
    <?php
    show_source(__FILE__);
    ?>
    <?php } ?>

</body>
</html>
