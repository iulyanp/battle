<?php

namespace Iulyanp\Battle;

/**
 * Trait OutputTrait
 * @package Iulyanp\Battle
 */
trait OutputTrait
{
    /**
     * @param string $message
     *
     * @return string
     */
    private function write($message = '')
    {
        return $this->output->writeln($message);
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function comment($message = '')
    {
        return $this->output->writeln("<comment>$message</comment>");
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function info($message = '')
    {
        return $this->output->writeln("<info>$message</info>");
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function error($message = '')
    {
        return $this->output->writeln("<error>$message</error>");
    }
}
