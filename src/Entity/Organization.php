<?php
namespace Owr\Entity;

/**
 * Organization class
 *
 * @package Owr\Entity
 */
class Organization
{
    use Organization\RelationTrait;

    /**
     * @var int Organization's unique identifier
     */
    private $id;

    /**
     * @var string Organization's name
     */
    private $name;

    /**
     * Organization constructor
     *
     * @param int $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    /**
     * Returns organization's ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns organization's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
