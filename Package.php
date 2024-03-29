<?php
require_once 'User.php';
require_once 'Exceptions.php';

enum Action: string {
    case LOGIN = 'login';
    case VALIDATE_KEY = 'kval';
    case REGISTER = 'reg';
    case REG_CONF = 'regconf';
    case DATA_REQUEST = 'data';
    case REMIND_PASS = 'remind';
    case REMIND_CONF = 'remindconf';
    case ERROR_MSG = 'emsg';
    case EDIT_USER_DATA = 'pupd';
    case UPLOAD_PROFILE_PIC = 'picupd';
    case TOUR_DATA_REQUEST = 'tourdata';
    case USER_DATA_REQUEST = 'userdata';
    case CREATE_REVIEW = 'crrev';
    case GET_REVIEWS = 'getrev';
    case GET_ROUTES = 'groutes';
}

class Package {

    readonly public ?User $invoker;
    readonly public Action $action;
    readonly public ?object $data;
    readonly public ?array $image;

    /**
     * @param object $json_object JSON object received over HTTPS
     * @return void
     * @throws UnexpectedValueException 0xF - No action is specified, 0xFF - Action is unknown
     * @throws DatabaseException If user does not exist
     */
    function __construct(object $json_object) {
        if (!isset($json_object->action)) throw new UnexpectedValueException("No action specified!", 0xF);
        try {
            $this->invoker = (isset($json_object->user_key)) ? new User($json_object->user_key) : NULL;
            $this->image = (isset($_FILES['new_avatar_img'])) ? $_FILES['new_avatar_img'] : NULL;
            $this->action = Action::from($json_object->action);
        } catch (ValueError) {
            throw new UnexpectedValueException("Action is unknown!", 0xFF);
        } catch (DatabaseException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        }
        $this->data = $json_object->data ?? NULL;
    }
}