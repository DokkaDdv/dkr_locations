<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/User.php';

resetTestSession();

runSingleTest('User::login stores session data on success', function (): void {
    $sql = "SELECT * FROM users WHERE email = ? AND password = MD5(?)";

    $statement = (new FakePDOStatement())->setFetchValue([
        'id' => 42,
        'email' => 'admin@dkr.com',
        'role' => 'admin',
    ]);

    $pdo = new FakePDO();
    $pdo->addPreparedStatement($sql, $statement);

    $user = new User($pdo);
    $result = $user->login('admin@dkr.com', 'secret');

    assertTrue($result, 'Login should return true when credentials match.');
    assertSame([['admin@dkr.com', 'secret']], $statement->executedWith, 'Login should execute SQL with email and password.');
    assertSame(42, $_SESSION['user_id'] ?? null, 'Session should store user_id.');
    assertSame('admin@dkr.com', $_SESSION['user_email'] ?? null, 'Session should store user_email.');
    assertSame('admin', $_SESSION['user_role'] ?? null, 'Session should store user_role.');
});