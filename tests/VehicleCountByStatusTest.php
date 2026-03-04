<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/Vehicle.php';

runSingleTest('Vehicle::countByStatus returns count for given status', function (): void {
    $sql = "SELECT COUNT(*) as total FROM vehicles WHERE statut = ?";

    $statement = (new FakePDOStatement())->setFetchValue(['total' => 3]);

    $pdo = new FakePDO();
    $pdo->addPreparedStatement($sql, $statement);

    $vehicle = new Vehicle($pdo);
    $count = $vehicle->countByStatus('disponible');

    assertSame($sql, $pdo->lastPreparedSql, 'countByStatus should prepare the expected SQL query.');
    assertSame([['disponible']], $statement->executedWith, 'countByStatus should execute SQL with given status.');
    assertSame(3, $count, 'countByStatus should return the total from fetch result.');
});