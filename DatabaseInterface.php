<?php
interface DatabaseInterface {
    function __construct();
    public function getUserByLogin(string $login): array;
    public function getUserByKey(string $key): array;
    public function getUserByID(int $id): array;
    public function getLoginKeyUsage(string $key): int;
    public function assignKeyToUserID(string $key, int $user_id): void;
    public function insertUser(string $email, string $name, string $hash): void;
    public function arrayUpdateUser(int $user_id, array $array): void;
    public function updateAvatarLocation(int $user_id, string $new_location): void;
    public function setUserPassword(string $email, string $hash): void;
    public function getData(): array;
    public function getSources(int $tour_id): array;
    public function insertErrorMessage(string $message, User $sender): void;
    public function insertReview(User $author, int $tour_id, int $mark, ?string $review): void;
    public function getReviews(int $tour_id): array;
    public function getRoutes(): array;
    function __destruct();
}