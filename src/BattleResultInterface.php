<?php

namespace Iulyanp\Battle;

interface BattleResultInterface
{
    /**
     * @param $round
     *
     * @return void
     */
    public function getRoundResult($round);

    /**
     * @return void
     */
    public function getResult();
}