<?php
// classes -> Users, Topics, Votes, Comments
// ---------- USER CLASS -> registerUser(), authenticateUser()
class User{
    //** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // register a new user
    // returns false if: username empty, password < 9, invalid email, username OR email already exists
    public function registerUser($username, $email, $password): bool
    {
        // basic validation
        // username empty
        if (empty($username)) {
            return false;
        }

        // password < 9
        if (strlen($password) < 9){
            return false;
        }

        // email invalid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }

        // check if username or email already exists
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()){
            // duplicate username or email
            return false;
        }

        // insert user (hashed password)
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

        try {
            return $stmt->execute([$username, $email, $hashed]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // returns true if username/password match a stored user
    public function authenticateUser($username, $password): bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user){
            return false;
        }

        return password_verify($password, $user['password']);
    }
    
}

// ---------- TOPIC CLASS -> createTopic(), getTopics(), getCreatedTopics()  
class Topic{
    //** @var PDO */
    private $pdo;

    public $id;
    public $user_id;
    public $title;
    public $description;
    public $created_at;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // create a new topic
    public function createTopic($userId, $title, $description): bool
    {
        // check if user id and title is filled
        if (empty($userId) || empty($title)){
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO topics (user_id, title, description) VALUES (?, ?, ?)");

        try {
            return $stmt->execute([$userId, $title, $description]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // get topics already made by all users as an array
    public function getTopics() : array 
    {
        $stmt = $this->pdo->query("SELECT * FROM topics ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $topics = [];

        foreach ($rows as $row) {
            $topic = new Topic($this->pdo);
            $topic->id = (int)$row['id'];
            $topic->user_id = (int)$row['user_id'];
            $topic->title = $row['title'];
            $topic->description = $row['description'];
            $topic->created_at = $row['created_at'];
            $topics[] = $topic;
        }

        return $topics;
    }

    // return array of associative arrays for topics created by a given user
    public function getCreatedTopics($userId) : array 
    {
        $stmt = $this->pdo->prepare("SELECT id, title, description, user_id FROM topics WHERE user_id = ? ORDER BY id ASC");
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// ---------- VOTE CLASS -> vote(), hasVoted(), getUserVoteHistory()
class Vote{
    //** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // record a vote
    // return false if vote type is invalid (not 'up' or 'down') OR if user a;ready voted on this topic
    public function vote($userId, $topicId, $voteType): bool
    {
        // validate vote type
        if (!in_array($voteType, ['up', 'down'], true)) {
            return false;
        }

        // prevent duplicate votes
        if ($this->hasVoted($topicId, $userId)) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO votes (user_id, topic_id, vote_type) VALUES (?, ?, ?)");

        try {
            return $stmt->execute([$userId, $topicId, $voteType]);
        } catch (PDOException $e) {
            return false;
        }
    }   


    // return true if user already has a vote on that topics
    public function hasVoted($topicId, $userId): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM votes WHERE topic_id = ? AND user_id = ?");
        $stmt->execute([$topicId, $userId]);

        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    // return an array of associative arrays describing the votes by this user
    public function getUserVoteHistory($userId): array
    {
        $stmt = $this->pdo->prepare("SELECT topic_id, vote_type, voted_at FROM votes WHERE user_id = ? ORDER BY voted_at DESC");
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// ---------- COMMENT CLASS -> addComment(), getComments()
class Comment{
    //** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function addComment($userId, $topicId, $comment): bool
    {
        if (empty($comment)){
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO comments (user_id, topic_id, comment) VALUES (?, ?, ?)");

        try {
            return $stmt->execute([$userId, $topicId, $comment]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // return an array of associative arrays
    // test checks count($comments) and $comments[0]['comment]
    public function getComments($topicId): array
    {
        $stmt = $this->pdo->prepare("SELECT user_id, topic_id, comment, commented_at FROM comments WHERE topic_id = ? ORDER BY id ASC");
        $stmt->execute([$topicId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// TimeFormatter
// satic utility class for human-readable times
class TimeFormatter{
    // If within last 12 months:
    //  - "X minutes ago"
    //  - "X hours ago"
    //  - "X days ago"
    // Else:
    //  - "M d, Y"  e.g. "Jan 01, 2022"
    public static function formatTimestamp(int $timestamp): string
    {
        $now = time();
        $diff = $now - $timestamp;
        if ($diff < 0){
            $diff = 0;
        }

        $minute = 60;
        $hour = 60 * $minute;
        $day = 24 * $hour;
        $year = 365 * $day;

        if ($diff < $hour){
            // minutes ago
            $minutes = (int) floor($diff / $minute);
            if ($minutes < 1){
                $minutes = 1;
            }
            return $minutes . ' minute' . ($minutes === 1 ? '' : 's') . ' ago';
        }
        elseif ($diff < $day){
            // hours ago
            $hours = (int) floor($diff / $hour);
            if ($hours < 1){
                $hours = 1;
            }
            return $hours . ' hour' . ($hours === 1 ? '' : 's') . ' ago';
        }
        elseif ($diff < $year){
            // days ago
            $days = (int) floor($diff / $day);
            if ($days < 1){
                $days = 1;
            }
            return $days . ' day' . ($days === 1 ? '' : 's') . ' ago';
        }
        else {
            // older than 12 months: show date
            return date('M d, Y', $timestamp);
        }
    }
} 