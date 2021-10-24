<?php

namespace App\Messages;

class Messages
{
    const TITLE_EXISTS = "Product already exists";
    const TITLE_REQUIRED = "Title is required";
    const CATEGORY_REQUIRED = "Category is required";
    const QUANTITY_REQUIRED = "Quantity is required";
    const QUANTITY_NUMERIC = "Quantity must be a number";
    const INVALID_CATEGORY = "Invalid category";
    const INVALID_SORT = "Invalid Sort Option";
    const INVALID_ORDER = "Order Option Is Invalid";

    const LOGIN_SUCCESS = "Login Successful!";
    const REGISTRATION_SUCCESS = "Registration Successful!";
    const USER_UPDATE_SUCCESS = "Information Updated Successfully!";
    const USER_DELETE_SUCCESS = "User Deleted Successfully!";

    const PRODUCT_ADD_SUCCESS = "Product Added Successfully!";
    const PRODUCT_UPDATE_SUCCESS = "Edited Successfully!";

    const USER_EXISTS = "User with this email already exists";
    const INVALID_EMAIL = "Email format is incorrect";
    const EMAIL_REQUIRED = "Email is required";
    const NAME_REQUIRED = "Name is required";
    const NAME_TOO_LONG = "Name is too long";
    const PASSWORDS_DONT_MATCH = "Passwords do not match";
    const PASSWORD_TOO_SHORT = "Password must be at least 6 characters long";
    const PASSWORD_REQUIRED = "Password is required";
    const PASSWORD_CONFIRM_REQUIRED = "Password confirmation is required";

    const USER_DONT_EXIST = "Cannot find user with this email";
    const WRONG_PASSWORD = "Wrong password";
}