<?php
namespace Owr\Exception;

class NotFoundException extends \Exception
{
    const RELATIONS_MESSAGE = "No relations found for organization '%s'";
}
