<?php
namespace Owr\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration to init db structure
 */
class Version20160118095240 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $organizations = <<<SQL
CREATE TABLE `organizations` (
  `id` INTEGER UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`)
) ENGINE = InnoDB
SQL;
        $relations = <<<SQL
CREATE TABLE `relations` (
  `source` INTEGER UNSIGNED NOT NULL,
  `target` INTEGER UNSIGNED NOT NULL,
  `type` ENUM("parent", "sibling", "child") CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`source`, `target`),
  KEY `source` (`source`),
  KEY `target` (`target`),
  FOREIGN KEY (`source`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`target`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
SQL;

        $this->addSql($organizations);
        $this->addSql($relations);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE `relations`');
        $this->addSql('DROP TABLE `organizations`');
    }
}
