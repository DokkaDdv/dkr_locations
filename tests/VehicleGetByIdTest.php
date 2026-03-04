<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/Vehicle.php';

runSingleTest('Vehicle::getById returns one vehicle by id', function (): void {
    $sql = "SELECT * FROM vehicles WHERE id = ?";
    $expectedVehicle = [
        'id' => 7,
        'marque' => 'Renault',
        'modele' => 'Clio',
    ];

    $statement = (new FakePDOStatement())->setFetchValue($expectedVehicle);

    $pdo = new FakePDO();
    $pdo->addPreparedStatement($sql, $statement);

    $vehicle = new Vehicle($pdo);
    $result = $vehicle->getById(7);

    assertSame($sql, $pdo->lastPreparedSql, 'getById should prepare the expected SQL query.');
    assertSame([[7]], $statement->executedWith, 'getById should execute SQL with the requested id.');
    assertSame($expectedVehicle, $result, 'getById should return fetched vehicle data.');
});