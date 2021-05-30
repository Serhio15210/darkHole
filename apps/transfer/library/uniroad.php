<?php
/**
 * UNI.ROAD protocol
 *
 * @author Serhii Shkrabak
 * @package Library\Uniroad
 */
namespace Library;
trait Uniroad
{

    private function uni() {
        if (!isset($GLOBALS['uniroad']))
            $GLOBALS['uniroad'] = new \Model\Services\Uniroad;
        return $GLOBALS['uniroad'];
    }
}