<?php
require_once 'DatabaseInterface.php';
require_once 'Config.php';
require_once 'Exceptions.php';

class Database extends mysqli implements DatabaseInterface {
    function __construct() {
        parent::__construct(Config::DB_URL, Config::DB_USER, Config::DB_PASS, Config::DB)
            or throw new DatabaseException("Unable to connect!");
    }
    private function fastPrepare(string $query, string $types, &$var1, &...$vars): mysqli_result {
        $prep = self::prepare($query);
        $prep->bind_param($types, $var1, ...$vars);
        $prep->execute();
        $r = $prep->get_result();
        $prep->close();
        return $r;
    }
    public function getUserByLogin(string $login): array {
        $f = self::fastPrepare("SELECT * FROM users WHERE login = ?", 's', $login);
        if ($f->num_rows != 1) throw new DatabaseException("User does not exists!");
        return $f->fetch_assoc();
    }
    public function getUserByKey(string $key): array {
        $f = self::fastPrepare("SELECT * FROM users WHERE loginkey = ?", 's', $key);
        if ($f->num_rows != 1) throw new DatabaseException("User does not exists!");
        return $f->fetch_assoc();
    }
    public function getLoginKeyUsage(string $key): int {
        return self::fastPrepare("SELECT id FROM users WHERE loginkey = ?", 's', $key)->num_rows;
    }
    public function assignKeyToUserID(string $key, int $user_id): void {
        self::fastPrepare("UPDATE users SET loginkey = ? WHERE id = ?", 'ss', $key, $user_id);
    }
    public function insertUser(string $email, string $name, string $hash): void {
        try {
            $this->getUserByLogin($email);
            throw new UserAlreadyRegisteredException();
        } catch (DatabaseException $e) {
            self::fastPrepare("INSERT INTO users(login, name, hash) VALUES (?, ?, ?)", 'sss', $email, $name, $hash);
        }
    }
    public function arrayUpdateUser(int $user_id, array $array): void {
        $stmt = self::prepare("UPDATE users SET ? = ? WHERE id = ?");
        $stmt->bind_param('ssi', $key, $value, $user_id);
        foreach ($array as $key => $value) {
            $stmt->execute();
        }
        $stmt->close();
    }
    public function setUserPassword(string $email, string $hash): void {
        try {
            $this->getUserByLogin($email);
        } catch (DatabaseException $e) {
            throw new UserNotFoundException(previous: $e);
        }
        self::fastPrepare("UPDATE users SET hash = ? WHERE login = ?", 'ss', $hash, $email);
    }
    public function getData(): array {
        $q = self::query("SELECT lat, lon, description FROM points");
        return $q->fetch_all();
    }
    public function getSources(int $tour_id): array {
        $r = self::fastPrepare("SELECT audio, subtitles FROM points WHERE id = ?", 'i', $tour_id);
        if ($r->num_rows == 0) throw new ElementNotFoundException();
        return $r->fetch_array(MYSQLI_NUM);
    }
    public function insertErrorMessage(string $message, User $sender): void {
        $senderid = $sender->id;
        self::fastPrepare("INSERT INTO error_messages(senderid, text) VALUES (?, ?)", 'is', $senderid, $message);
    }
    function __destruct() {
        self::close();
    }
}
