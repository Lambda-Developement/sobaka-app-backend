<?php
interface DatabaseInterface {
    function __construct();
    public function getUserByLogin(string $login): array;
    public function getUserByKey(string $key): array;
    public function getUserByID(int $id): array;
    public function getUserByConfKey(string $key): array;
    public function getUserByRemindKey(string $key): array;
    public function getLoginKeyUsage(string $key): int;
    public function getConfirmationKeyUsage(string $key): int;
    public function getRemindKeyUsage(string $key): int;
    public function assignKeyToUserID(string $key, int $user_id): void;
    public function assignRemindKeyToUserID(string $key, int $user_id): void;
    public function insertUser(string $email, string $name, string $hash, string $mail_conf): void;
    public function arrayUpdateUser(int $user_id, array $array): void;
    public function updateAvatarLocation(int $user_id, string $new_location): void;
    public function setUserPassword(string $email, string $hash): void;
    public function getData(): array;
    public function getSources(int $tour_id): array;
    public function insertErrorMessage(string $message, User $sender): void;
    public function insertReview(User $author, int $tour_id, int $mark, ?string $review): void;
    public function insertRouteReview(User $author, int $route_id, int $mark, ?string $review): void;
    public function getReviews(int $tour_id): array;
    public function getRouteReviews(int $route_id): array;
    public function getRoutes(): array;
    public function activateUser(int $user_id): void;
    function __destruct();
}