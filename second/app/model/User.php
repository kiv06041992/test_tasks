<?php
namespace app\model;

class User extends Model {
    protected string $tableName = 'user';

    protected int|null $id = null;
    protected string $name = '';
    protected string $email = '';
    protected string $password = '';

}