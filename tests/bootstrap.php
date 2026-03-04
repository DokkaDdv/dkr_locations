<?php
declare(strict_types=1);

error_reporting(E_ALL);

function failTest(string $message): never {
    throw new RuntimeException($message);
}

function assertTrue(mixed $condition, string $message): void {
    if ($condition !== true) {
        failTest($message);
    }
}

function assertFalse(mixed $condition, string $message): void {
    if ($condition !== false) {
        failTest($message);
    }
}

function assertSame(mixed $expected, mixed $actual, string $message): void {
    if ($expected !== $actual) {
        $details = " Expected: " . var_export($expected, true) . " Actual: " . var_export($actual, true);
        failTest($message . $details);
    }
}

function runSingleTest(string $name, callable $test): void {
    try {
        $test();
        echo "[OK] {$name}" . PHP_EOL;
    } catch (Throwable $exception) {
        fwrite(STDERR, "[FAIL] {$name}: {$exception->getMessage()}" . PHP_EOL);
        exit(1);
    }
}

final class FakePDOStatement {
    private mixed $fetchValue = null;
    private array $fetchAllValue = [];
    private bool $executeReturn = true;

    public array $executedWith = [];

    public function setFetchValue(mixed $value): self {
        $this->fetchValue = $value;
        return $this;
    }

    public function setFetchAllValue(array $value): self {
        $this->fetchAllValue = $value;
        return $this;
    }

    public function setExecuteReturn(bool $value): self {
        $this->executeReturn = $value;
        return $this;
    }

    public function execute(array $params = []): bool {
        $this->executedWith[] = $params;
        return $this->executeReturn;
    }

    public function fetch(): mixed {
        return $this->fetchValue;
    }

    public function fetchAll(): array {
        return $this->fetchAllValue;
    }
}

final class FakePDO {
    private array $preparedStatements = [];
    private array $queryStatements = [];

    public ?string $lastPreparedSql = null;
    public ?string $lastQuerySql = null;

    public function addPreparedStatement(string $sql, FakePDOStatement $statement): void {
        $this->preparedStatements[$sql] = $statement;
    }

    public function addQueryStatement(string $sql, FakePDOStatement $statement): void {
        $this->queryStatements[$sql] = $statement;
    }

    public function prepare(string $sql): FakePDOStatement {
        $this->lastPreparedSql = $sql;

        if (!array_key_exists($sql, $this->preparedStatements)) {
            throw new RuntimeException("No prepared statement configured for SQL: {$sql}");
        }

        return $this->preparedStatements[$sql];
    }

    public function query(string $sql): FakePDOStatement {
        $this->lastQuerySql = $sql;

        if (!array_key_exists($sql, $this->queryStatements)) {
            throw new RuntimeException("No query statement configured for SQL: {$sql}");
        }

        return $this->queryStatements[$sql];
    }
}

function resetTestSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];
}