<?php

declare(strict_types = 1);

namespace core\provider;

use core\Mythical;
use mysqli;
use pocketmine\Server;

class MySQLProvider {
    
	const DATABASE = "db_156062";

	/** @var Mythical */
	private $core;

	/** @var mysqli */
	private $database;
	/**
	 * MySQLProvider constructor.
	 *
	 * @param Mythical $core
	 */
	public function __construct(Mythical $core) {
		$this->core = $core;
		$this->database = new mysqli("na02-db.cus.mc-panel.net", "db_156062", "53706adbdf", self::DATABASE);
		$this->init();
	}

	public function init(): void {
		$this->database->query("CREATE TABLE IF NOT EXISTS players(xuid VARCHAR(36) PRIMARY KEY, username VARCHAR(16), faction VARCHAR(16) DEFAULT NULL, factionRole TINYINT DEFAULT NULL, balance BIGINT DEFAULT 0, questPoints BIGINT DEFAULT 0, rankId TINYINT DEFAULT 0, permissions VARCHAR(600) DEFAULT '', tags VARCHAR(600) DEFAULT '', currentTag VARCHAR(150) DEFAULT '', kills SMALLINT DEFAULT 0, luckyBlocks SMALLINT DEFAULT 0);");
		$this->database->query("CREATE TABLE IF NOT EXISTS extraData(xuid VARCHAR(36) PRIMARY KEY, username VARCHAR(16), permissions VARCHAR(600) DEFAULT '', rewardCooldown BIGINT DEFAULT 0)");
		$this->database->query("ALTER TABLE extraData ADD COLUMN rewardCooldown BIGINT DEFAULT 0");
		$this->database->query("CREATE TABLE IF NOT EXISTS ipAddress(xuid VARCHAR(36), username VARCHAR(16), ipAddress VARCHAR(20), riskLevel TINYINT);");
		$this->database->query("CREATE TABLE IF NOT EXISTS factions(name VARCHAR(30) NOT NULL, x SMALLINT DEFAULT NULL, y SMALLINT DEFAULT NULL, z SMALLINT DEFAULT NULL, members TEXT NOT NULL, allies TEXT DEFAULT NULL, balance BIGINT DEFAULT 0 NOT NULL, strength BIGINT DEFAULT 100 NOT NULL);");
		$this->database->query("CREATE TABLE IF NOT EXISTS claims(faction VARCHAR(30) NOT NULL, chunkX SMALLINT DEFAULT NULL, chunkZ SMALLINT DEFAULT NULL);");
		$this->database->query("CREATE TABLE IF NOT EXISTS rewards(xuid VARCHAR(36) PRIMARY KEY, username VARCHAR(16), items BLOB DEFAULT NULL);");
		$this->database->query("CREATE TABLE IF NOT EXISTS inboxes(xuid VARCHAR(36) PRIMARY KEY, username VARCHAR(16), items BLOB DEFAULT NULL);");
		$this->database->query("CREATE TABLE IF NOT EXISTS crates(xuid VARCHAR(36) PRIMARY KEY, username VARCHAR(16), rare SMALLINT DEFAULT 0 NOT NULL, legendary SMALLINT DEFAULT 0 NOT NULL, mythic SMALLINT DEFAULT 0 NOT NULL, ultra SMALLINT DEFAULT 0 NOT NULL);");
		$this->database->query("CREATE TABLE IF NOT EXISTS kitCooldowns(xuid VARCHAR(36) PRIMARY KEY, username VARCHAR(16));");
		$this->database->query("CREATE TABLE IF NOT EXISTS homes(xuid VARCHAR(36) NOT NULL, username VARCHAR(16), name VARCHAR(16) NOT NULL, x SMALLINT NOT NULL, y SMALLINT NOT NULL, z SMALLINT NOT NULL, level VARCHAR(30) NOT NULL);");
	}
	/**
	 * @return string
	 */
	public function getMainDatabaseName(): string {
		return self::DATABASE;
	}
	   public static function queryAsync(string $query, string $types = "", array $params = [], ?callable $onComplete = null) : void{
    }
        //public static function querySync(string $query, string $types = "", array $params = []) : array{
        //return [];
    //}

	/**
	 * @return mysqli
	 */
	public function getDatabase(): mysqli {
		return $this->database;
	}
}
