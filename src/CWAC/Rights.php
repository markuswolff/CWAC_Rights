<?php

/**
 * Usage:
 * $rights = array('admin.*',
 *                 '-admin.bla',
 *                 'files.open',
 *                 'files.download.really');
 * $user = new UserRights();
 * $user->setRights($rights);
 * echo "admin.honk: ".(int)$user->checkRight('admin.honk')."\n";
 * echo 'admin.bla: '.(int)$user->checkRight('admin.bla')."\n";
 * echo 'files.download.really: '.(int)$user->checkRight('files.download.really')."\n";
 * echo 'files.download.not: '.(int)$user->checkRight('files.download.not')."\n";
 * echo 'files.open: '.(int)$user->checkRight('files.open')."\n";
 */
class CWAC_Rights
{
    /**
     * A list of all permissions granted (or revoked, if prefixed with -).
     *
     * @var array
     */
    private $_rights = array();

    /**
     * Add a single permission.
     *
     * Separate optional hierarchy levels with periods:
     * level1.level2.level3
     *
     * To grant all permissions from a given level forward, use a wildcard:
     * level1.*
     *
     * @param string $right
     */
    public function addRight($right)
    {
        if (!in_array($right, $this->_rights)) {
            $this->_rights[] = $right;
        }
    }

    /**
     * Set permissions by array. Pass an entire array of permissions to define
     * the current set of permissions this instance will operate on. Overwrites
     * *all* previously set permissions.
     *
     * @param string $right
     */
    public function setRights(Array $rights)
    {
        $this->_rights = $rights;
    }

    /**
     * Check if a given permission is granted.
     *
     * @param string $right
     *
     * @return bool True if granted, false otherwise.
     */
    public function checkRight($right)
    {
        $right = strtolower($right);
        $parts = explode('.', $right);
        $match = false;
        foreach ($this->_rights as $r) {
            $r = strtolower($r);
            if ($right == $r) {
                return true;
            }
            $rparts   = explode('.', $r);
            $negative = false;
            if (substr($rparts[0], 0, 1) == '-') {
                $negative  = true;
                $rparts[0] = substr($rparts[0], 1);
            }
            if ($match && !$negative) {
                continue;
            }
            for ($i = 0; $i < count($parts) && $i < count($rparts); $i++) {
                if ($parts[$i] != $rparts[$i] && $rparts[$i] != '*') {
                    if ($i == 0) {
                        break;
                    }
                } else {
                    if (!$negative) {
                        if ($i == count($parts) - 1 || $rparts[$i] == '*') {
                            $match = true;
                        }
                    } elseif ($i == count($rparts) - 1) {
                        $match = false;
                    }
                }
            }
        }
        if ($match) {
            return true;
        }
        return false;
    }

}
