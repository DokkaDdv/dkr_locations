<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/User.php';

resetTestSession();

runSingleTest('User::login returns false on invalid credentials', function (): void {
    $sql = "SELECT * FROM users WHERE email = ? AND password = MD5(?)";

    $statement = (new FakePDOStatement())->setFetchValue(false);

    $pdo = new FakePDO();
    $pdo->addPreparedStatement($sql, $statement);

    $user = new User($pdo);
    $result = $user->login('wrong@dkr.com', 'bad-pass');

    assertFalse($result, 'Login should return false when no user is found.');
    assertSame([['wrong@dkr.com', 'bad-pass']], $statement->executedWith, 'Login should execute SQL with submitted credentials.');
    assertSame([], $_SESSION, 'Session should remain empty when login fails.');
});